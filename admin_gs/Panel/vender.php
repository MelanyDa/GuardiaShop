<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Restringir acceso solo a admin, super_admin o vendedor
if (!isset($_SESSION['admin_rol']) || !in_array($_SESSION['admin_rol'], ['admin', 'super_admin', 'vendedor'])) {
    header('Location: /guardiashop/login/login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MODULO DE VENTAS</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,700,900" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
</head>

<body id="page-top">
    <div id="wrapper">
        <?php require_once($_SERVER['DOCUMENT_ROOT'].'/guardiashop/admin_gs/Panel/comun/menu.php'); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once($_SERVER['DOCUMENT_ROOT'].'/guardiashop/admin_gs/Panel/comun/nav.php'); ?>

                <div class="container-fluid">
                    <div class="ventas-header">
                        <h1 class="h3 mb-4" style="color: #2c4926;">
                            <strong>
                                <i class="fas fa-fw fa-dollar-sign"></i>
                                MODULO DE VENTAS PRESENCIAL
                            </strong>
                        </h1>
                    </div>  <?php
                        $conn = new mysqli("localhost", "root", "", "guardiashop");
                        if ($conn->connect_error) {
                            die("Conexión fallida: " . $conn->connect_error);
                        }

                       $productos = $conn->query("
                            SELECT 
                                pr.id_producto, 
                                pr.codigo,
                                pr.nombre AS nombre_producto,
                                c.nombre AS categoria, 
                                s.nombre AS sesion,
                                pi.imagen
                            FROM productos pr
                            INNER JOIN categoria c ON pr.id_categoria = c.id_categoria
                            INNER JOIN sesiones s ON pr.id_sesion = s.id_sesion
                            LEFT JOIN producto_imagen pi ON pr.id_producto = pi.id_producto
                            GROUP BY pr.id_producto
                        ");
                            ?>

                             <div class="card shadow mb-4">
                             <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background-color: #2c4926; color: #fff;">
                                <!-- Título a la izquierda -->
                                <h6 class="m-0 font-weight-bold mb-0" style="color: #fff;">Seleccionar Producto</h6>
                                <!-- Buscador a la derecha -->
                                <div style="width: 300px;">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="background:#e9ecef;">
                                                <i class="fas fa-search"></i>
                                            </span>
                                        </div>
                                        <input type="text" id="buscadorProducto" class="form-control" placeholder="Código o nombre...">
                                    </div>
                                    <div id="sugerenciasProducto" class="list-group position-absolute w-99" style="z-index: 1000;"></div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="ventaForm">
                                    <div class="row">
                                        
                                        <div class="col-md-4 mb-3">
                                            <label  style="color: #000;">Producto</label>
                                            <select id="productoSelect" class="form-control" required>
                                                <option value="">Seleccione un producto</option>
                                                <?php while ($p = $productos->fetch_assoc()): ?>
                                                    <option 
                                                    value="<?php echo $p['id_producto']; ?>"
                                                    data-codigo="<?php echo $p['codigo']; ?>"
                                                    data-categoria="<?php echo $p['categoria']; ?>"
                                                    data-sesion="<?php echo $p['sesion']; ?>"
                                                    data-imagen="<?php echo $p['imagen']; ?>">
                                                    <?php echo $p['nombre_producto']; ?>
                                                </option>
                                                <?php endwhile; ?>
                                            </select>
                                            <div id="imagenProductoContainer" class="mt-3 imagen-izquierda">
                                            <img id="imagenProducto" src="" alt="Imagen del producto" class="img-fluid rounded" style="max-height: 200px; display: none;">
                                        </div>

                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label style="color: #000;">Categoría</label>
                                            <input type="text" id="categoria" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label  style="color: #000;">Sesión</label>
                                            <input type="text" id="sesion" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label  style="color: #000;">Talla</label>
                                            <select id="talla" class="form-control" required>
                                                <!-- Aquí se cargarán dinámicamente las tallas -->
                                            </select>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label  style="color: #000;">Color</label>
                                            <select id="color" class="form-control" required>
                                                <!-- Aquí se cargarán dinámicamente los colores -->
                                            </select>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label  style="color: #000;">Cantidad</label>
                                            <input type="number" class="form-control" id="cantidad" min="1" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label  style="color: #000;">Precio</label>
                                            <input type="text" id="precio" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3 mb-3 d-flex align-items-end">
                                            <button type="button" class="btn w-100" style="background-color:rgb(90, 95, 90); color:#ffff" onclick="agregarAlCarrito()">Agregar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <style>
#tablaCarrito td {
    color: #000 !important;
}
</style>
                        <!-- Tabla de carrito -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3" style="background-color: #2c4926; color: #fff;">
                                <h6 class="m-0 font-weight-bold" >Resumen de Venta</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tablaCarrito">
                                        <thead style="background-color: #2c4926; color: #fff;">     
                                            <tr>
                                               <th>Producto</th>
                                                <th>Categoría</th>
                                                <th>Sesión</th>
                                                <th>Talla</th>
                                                <th>Color</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Subtotal</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        
                                    </table>
                                    <div class="mt-3 text-right">
                                    <p style="color: #000;"><strong>Total:</strong> $<span id="totalVenta">0.00</span></p>
                                </div>
                                </div>
                                <div class="text-right">
                                <button class="btn" style="background-color: #2c4926; color:#fff" data-toggle="modal" data-target="#modalFinalizarVenta">
                                    Terminar venta
                                </button>
                            </div>
                            </div>
                        </div>
                     <!-- Modal para finalizar venta -->
<div class="modal fade" id="modalFinalizarVenta" tabindex="-1" role="dialog" aria-labelledby="modalFinalizarVentaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#2c4926;color:#fff;">
        <h5 class="modal-title" id="modalFinalizarVentaLabel">Datos del cliente</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Formulario visible para capturar datos -->
        <form id="formFinalizarVenta">
          <div class="form-group">
            <label for="cliente_nombre"  style="color: #000;">Nombre completo</label>
            <input type="text" class="form-control" id="cliente_nombre" required>
          </div>
          <div class="form-group">
            <label for="cliente_identificacion"  style="color: #000;">Identificación (NIT/C.C.)</label>
            <input type="text" class="form-control" id="cliente_identificacion" required>
          </div>
          <div class="form-group">
            <label for="cliente_direccion"  style="color: #000;">Dirección</label>
            <input type="text" class="form-control" id="cliente_direccion" required>
          </div>
          <div class="form-group">
            <label for="cliente_correo" style="color: #000;">Correo electrónico</label>

            <input type="email" class="form-control" id="cliente_correo" required>
          </div>
          <div class="form-group">
            <label for="metodo_pago"  style="color: #000;">Método de pago</label>
            <select class="form-control" id="metodo_pago" required>
              <option value="Efectivo" selected>Efectivo</option>

            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn" style="background-color: #2c4926; color:#fff;" onclick="finalizarVenta()">Finalizar compra</button>
      </div>
    </div>
  </div>
  <!-- Formulario oculto para enviar los datos de la factura -->
<form id="formFactura" action="generar_factura.php" method="post"  style="display:none;">
    <input type="hidden" name="datosFactura" id="datosFactura">
</form>
</div>


                </div>
            </div>
        </div>
    </div>


    <!-- JS scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/demo/datatables-demo.js"></script>
    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    
    <script>
document.getElementById('productoSelect').addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    const categoria = selected.getAttribute('data-categoria');
    const sesion = selected.getAttribute('data-sesion');
    const imagen = selected.getAttribute('data-imagen');

    document.getElementById('categoria').value = categoria || '';
    document.getElementById('sesion').value = sesion || '';

    const img = document.getElementById('imagenProducto');
    if (imagen) {
        img.src = imagen;
        img.style.display = 'block';
    } else {
        img.src = '';
        img.style.display = 'none';
    }
});
</script>

    <script>
$(document).ready(function () {
    $('#productoSelect').on('change', function () {
        let productoId = $(this).val();
        let categoria = $(this).find('option:selected').data('categoria');
        let sesion = $(this).find('option:selected').data('sesion');
        $('#categoria').val(categoria);
        $('#sesion').val(sesion);
        $('#talla').html('<option value="">Cargando...</option>');
        $('#color').html('<option value="">Seleccione talla</option>');
        $('#precio').val('');

        if (productoId !== "") {
            $.ajax({
                url: '/guardiashop/admin_gs/Panel/acciones/ventas/obtener_tallas.php',
                method: 'POST',
                data: { id_producto: productoId },
                success: function (data) {
                    $('#talla').html(data);
                }
            });
        }
    });

    $('#talla').on('change', function () {
        let productoId = $('#productoSelect').val();
        let talla = $(this).val();
        $('#color').html('<option value="">Cargando...</option>');
        $('#precio').val('');
        if (talla !== "") {
            $.ajax({
                url: '/guardiashop/admin_gs/Panel/acciones/ventas/obtener_colores.php',
                method: 'POST',
                data: {
                    id_producto: productoId,
                    talla: talla
                },
                success: function (data) {
                    $('#color').html(data);
                }
            });
        }
    });

    $('#color').on('change', function () {
        let productoId = $('#productoSelect').val();
        let talla = $('#talla').val();
        let color = $(this).val();
        if (color !== "") {
            $.ajax({
                url: '/guardiashop/admin_gs/Panel/acciones/ventas/obtener_precio.php',
                method: 'POST',
                data: {
                    id_producto: productoId,
                    talla: talla,
                    color: color
                },
                success: function (precio) {
                    $('#precio').val(precio);
                }
            });
        }
    });

    // SUGERENCIAS DE PRODUCTO
    $('#buscadorProducto').on('input', function () {
        const filtro = $(this).val().toLowerCase();
        const $select = $('#productoSelect');
        const $sugerencias = $('#sugerenciasProducto');
        $sugerencias.empty();
        if (filtro === "") {
            $sugerencias.hide();
            return;
        }
        let haySugerencias = false;
        $select.find('option').each(function () {
            const id = $(this).val().toLowerCase();
            const nombre = $(this).text().toLowerCase();
            const codigo = ($(this).data('codigo') || '').toLowerCase(); // Nuevo: busca por código
            if (
                ((id && id.includes(filtro)) || 
                (nombre && nombre.includes(filtro)) || 
                (codigo && codigo.includes(filtro)))
                && $(this).val() !== ""
            ) {
                haySugerencias = true;
                $sugerencias.append('<a href="#" class="list-group-item list-group-item-action" data-value="' + $(this).val() + '">' + $(this).text() + '</a>');
            }
        });
        if (haySugerencias) {
            $sugerencias.show();
        } else {
            $sugerencias.hide();
        }
    });

    // Al hacer clic en una sugerencia
    $('#sugerenciasProducto').on('click', 'a', function (e) {
        e.preventDefault();
        const valor = $(this).data('value');
        $('#productoSelect').val(valor).trigger('change');
        $('#sugerenciasProducto').empty().hide();
        $('#buscadorProducto').val('');
        // Mostrar la imagen del producto seleccionado
        const option = $('#productoSelect option:selected');
        const imagen = option.data('imagen');
        const img = $('#imagenProducto');
        if (imagen) {
            img.attr('src', imagen).show();
        } else {
            img.attr('src', '').hide();
        }
    });

    // Ocultar sugerencias si se hace clic fuera
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#buscadorProducto, #sugerenciasProducto').length) {
            $('#sugerenciasProducto').empty().hide();
        }
    });

    // Eliminar el elemento del DOM que muestra el IVA en el resumen de venta
    $('#ivaVenta').closest('p').remove();
});

function agregarAlCarrito() {
    const id_producto = $('#productoSelect').val();
    const producto = $('#productoSelect option:selected').text();
    const categoria = $('#categoria').val();
    const sesion = $('#sesion').val();
    const id_tallas = $('#talla').val();
    const talla = $('#talla option:selected').text();
    const id_color = $('#color').val();
    const color = $('#color option:selected').text();
    const cantidad = parseInt($('#cantidad').val());
    const precio = parseFloat($('#precio').val());

    if (!id_producto || !producto || !id_tallas || !talla || !id_color || !color || cantidad < 1 || !precio) {
        Swal.fire("Error", "Completa todos los campos correctamente", "warning");
        return;
    }

    // Validar stock antes de agregar
    $.ajax({
        url: '/guardiashop/admin_gs/Panel/acciones/ventas/validar_stock.php',
        method: 'POST',
        data: { carrito: JSON.stringify([{
            id_producto: id_producto,
            id_tallas: id_tallas,
            id_color: id_color,
            cantidad: cantidad
        }]) },
        dataType: 'json',
        success: function (sinStock) {
            if (sinStock.length > 0) {
                const stock = sinStock[0].stock_disponible;
                Swal.fire(
                    'Stock insuficiente',
                    `Solo hay ${stock} unidades disponibles para esta combinación.`,
                    'warning'
                );
                $('#cantidad').val(stock > 0 ? stock : 1);
                return;
            }

            // Si hay stock suficiente, ahora sí agrega al carrito
            $.ajax({
                url: '/guardiashop/admin_gs/Panel/acciones/ventas/obtener_id_detalle.php',
                method: 'POST',
                data: {
                    id_producto: id_producto,
                    id_tallas: id_tallas,
                    id_color: id_color
                },
                dataType: 'json',
                success: function (resp) {
                    if (!resp.id_detalles_productos) {
                        Swal.fire("Error", "No se encontró la variante seleccionada.", "error");
                        return;
                    }
                    const subtotal = cantidad * precio;
                    const fila = `
                        <tr 
                            data-id_producto="${id_producto}" 
                            data-id_tallas="${id_tallas}" 
                            data-talla="${talla}" 
                            data-id_color="${id_color}" 
                            data-color="${color}"
                            data-id_detalles_productos="${resp.id_detalles_productos}"
                        >
                            <td>${producto}</td>
                            <td>${categoria}</td>
                            <td>${sesion}</td>
                            <td>${talla}</td>
                            <td>${color}</td>
                            <td>${cantidad}</td>
                            <td class="precio">${precio}</td>
                            <td class="subtotal" data-valor="${subtotal}">${subtotal}</td>
                            <td><button class="btn btn-danger btn-sm" onclick="eliminarFila(this)">Eliminar</button></td>
                        </tr>
                    `;
                    $('#tablaCarrito tbody').append(fila);
                    actualizarTotales();
                    limpiarCamposProducto();
                }
            });
        }
    });
}
function limpiarCamposProducto() {
    $('#productoSelect').val('');
    $('#categoria').val('');
    $('#sesion').val('');
    $('#imagenProducto').attr('src', '').hide();
    $('#talla').html('<option value="">Seleccione un producto</option>');
    $('#color').html('<option value="">Seleccione talla</option>');
    $('#cantidad').val('');
    $('#precio').val('');
}


function actualizarTotales() {
    let subtotal = 0;
    $('#tablaCarrito tbody tr').each(function () {
        // Sumar usando el valor numérico, no el texto formateado
        const sub = parseFloat($(this).find('.subtotal').attr('data-valor'));
        subtotal += sub;
    });
    $('#totalVenta').text(subtotal);
}

function eliminarFila(btn) {
    $(btn).closest('tr').remove();
    actualizarTotales();
}

// MODIFICADO: Usar formulario oculto para descargar el PDF
function finalizarVenta() {
    // Obtener datos del formulario
    const clienteNombre = document.getElementById('cliente_nombre').value;
    const clienteIdentificacion = document.getElementById('cliente_identificacion').value;
    const clienteDireccion = document.getElementById('cliente_direccion').value;
    const clienteCorreo = document.getElementById('cliente_correo').value;
    const metodoPago = document.getElementById('metodo_pago').value;

    // Validar campos obligatorios
    if (!clienteNombre || !clienteIdentificacion || !clienteDireccion || !clienteCorreo || !metodoPago) {
        Swal.fire("Error", "Por favor, complete todos los campos del formulario.", "error");
        return;
    }

    // Obtener datos del carrito
    const carrito = [];
    const filas = document.querySelectorAll('#tablaCarrito tbody tr');
    if (filas.length === 0) {
        Swal.fire("Error", "El carrito está vacío.", "error");
        return;
    }

    filas.forEach(fila => {
        const columnas = fila.querySelectorAll('td');
        carrito.push({
            id_producto: fila.getAttribute('data-id_producto'),
            producto: columnas[0].textContent,
            categoria: columnas[1].textContent,
            sesion: columnas[2].textContent,
            id_tallas: fila.getAttribute('data-id_tallas'),
            talla: fila.getAttribute('data-talla'),
            id_color: fila.getAttribute('data-id_color'),
            color: fila.getAttribute('data-color'),
            cantidad: columnas[5].textContent,
            precio: columnas[6].textContent,
            subtotal: columnas[7].textContent,
            id_detalles_productos: fila.getAttribute('data-id_detalles_productos') // <-- NUEVO
        });
    });

    // Validar stock antes de enviar la venta
    $.ajax({
        url: '/guardiashop/admin_gs/Panel/acciones/ventas/validar_stock.php',
        method: 'POST',
        data: { carrito: JSON.stringify(carrito) },
        dataType: 'json',
        success: function (sinStock) {
            if (sinStock.length > 0) {
                let mensaje = "Los siguientes productos no tienen stock suficiente:<br><ul>";
                sinStock.forEach(function(item) {
                    mensaje += `<li>${item.producto} - Talla: ${item.talla} - Color: ${item.color}</li>`;
                });
                mensaje += "</ul>";
                Swal.fire({
                    icon: 'error',
                    title: 'Sin stock',
                    html: mensaje
                }).then(() => {
                    location.reload();
                });
                return;
            } else {
                const total = parseFloat(document.getElementById('totalVenta').textContent.replace(/[^\d.-]+/g, ''));

// Armar objeto de datos
const datos = {
    cliente: {
        nombre: clienteNombre,
        identificacion: clienteIdentificacion,
        direccion: clienteDireccion,
        correo: clienteCorreo
    },
    metodo_pago: metodoPago,
    carrito: carrito,
    subtotal: total, // El subtotal es igual al total mostrado
    total: total     // Agrega la clave total
};

                // Enviar datos por formulario oculto
                document.getElementById('datosFactura').value = JSON.stringify(datos);
                document.getElementById('formFactura').target = '';
                document.getElementById('formFactura').submit();

                // Mostrar mensaje de éxito y recargar
                Swal.fire("Éxito", "Venta exitosa. La factura fue enviada al correo.", "success").then(() => {
                    location.reload();
                });
            }
        },
        error: function () {
            Swal.fire("Error", "No se pudo validar el stock. Intenta de nuevo.", "error");
        }
    });
}

$("#btnAgregar").click(function(e){
    var cantidad = parseInt($("#cantidad").val());
    if (isNaN(cantidad) || cantidad <= 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Cantidad inválida',
            text: 'Debes ingresar una cantidad mayor a cero.'
        });
    }
});

function formatMoneda(valor) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
    }).format(valor);
}

</script>
    <!-- <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>

<?php endif; ?>

<style>
.imagen-izquierda {
    text-align: left !important;
}
</style>

</body>
</html>
