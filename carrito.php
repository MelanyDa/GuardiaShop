<?php
session_start();
require_once 'login/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GUARDIASHOP - Mi Carrito</title>
    <link rel="icon" href="./img/core-img/logoguardiashop.ico">
    <link rel="stylesheet" href="css/core-styleff.css"> 
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/carrito-style.css"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        :root {
            --primary-color: #b78732; /* Color principal de tu marca */
            --secondary-color:rgb(155, 139, 67); /* Un color oscuro para texto/fondos */
            --danger-color:rgb(155, 47, 35);
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --dark-gray: #6c757d;
            --white: #fff;
            --text-color: #212529;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        html {
            font-size: 16px; /* Base para unidades REM, más flexible */
            scroll-behavior: smooth;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--light-gray);
            margin: 0;
            padding: 0;
            font-size: 0.9rem; /* Ajusta el tamaño base del texto para el body si es necesario */
        }

        #cart-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        #cart-container h1 {
            text-align: center;
            color: var(--secondary-color);
            margin-bottom: 25px;
            font-size: 2rem;
        }

        /* Estilos de la tabla del carrito */
        #cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        #cart-table th,
        #cart-table td {
            text-align: left;
            padding: 12px 10px;
            border-bottom: 1px solid var(--medium-gray);
            vertical-align: middle;
        }

        #cart-table th {
            background-color: var(--light-gray);
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 0.9em;
            text-transform: uppercase;
        }

        #cart-table td {
            font-size: 0.95em;
        }

        .cart-item-img {
            width: 100px; /* Tamaño base para desktop */
            height: 100px;
            object-fit: cover;
            border-radius: var(--border-radius);
            border: 1px solid var(--medium-gray);
        }

        .cart-btn {
            background: none;
            border: 1px solid transparent;
            color: var(--dark-gray);
            cursor: pointer;
            padding: 5px 8px;
            font-size: 1em;
            border-radius: 4px;
            transition: background-color 0.2s, color 0.2s;
        }
        .cart-btn:hover {
            background-color: var(--medium-gray);
        }
        .cart-btn.modify-btn { color: #2980b9; font-size: 1.3em; }
        .cart-btn.remove-btn { color: var(--danger-color); font-size: 1.3em; }
        .cart-btn.quantity-btn {
            background-color: var(--light-gray);
            border: 1px solid var(--medium-gray);
            padding: 6px 10px;
        }
        .cart-btn.quantity-btn:hover {
            background-color: var(--medium-gray);
        }


        #cart-table input[type="number"] {
            width: 50px;
            text-align: center;
            padding: 6px;
            border: 1px solid var(--medium-gray);
            border-radius: 4px;
            margin: 0 5px;
            -moz-appearance: textfield; /* Para Firefox */
        }
        #cart-table input[type="number"]::-webkit-outer-spin-button,
        #cart-table input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }


        .cart-empty-message {
            text-align: center;
            padding: 40px 20px;
            font-size: 1.2em;
            color: var(--dark-gray);
        }
        .cart-empty-message td { /* Asegurar que ocupe todo el ancho */
            border-bottom: none !important;
        }


        #cart-total {
            text-align: right;
            font-size: 1.4em;
            font-weight: bold;
            margin-bottom: 25px;
            color: var(--secondary-color);
        }
        #cart-total span {
            color: var(--primary-color);
        }

        .cart-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Para que los botones se apilen en pantallas pequeñas */
            gap: 10px; /* Espacio entre botones */
        }

        .cart-actions button {
            padding: 12px 20px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 1em;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }
        .cart-actions button:active {
            transform: scale(0.98);
        }

        .btn-shop {
            background-color: var(--secondary-color);
            color: var(--white);
        }
        .btn-shop:hover {
            background-color:rgb(171, 159, 55);
        }

        .btn-clear {
            background-color:rgb(189, 44, 44); /* Rojo suave */
            color: var(--white);
        }
        .btn-clear:hover {
            background-color: #c62828;
        }

        .btn-checkout {
            background-color: #25601d; /* Verde oscuro */
            color: var(--white);
        }
        .btn-checkout:hover {
            background-color: #194312;
        }

        /* Modal Styles */
        .modal-bg {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5); /* Semi-transparent background */
            z-index: 10000; /* Muy alto para estar encima de todo */
            align-items: center;
            justify-content: center;
            padding: 15px; /* Espacio para que el modal no toque los bordes en móvil */
            box-sizing: border-box;
        }

        .modal-content {
            background: var(--white);
            padding: 25px 30px;
            border-radius: var(--border-radius);
            min-width: 300px;
            max-width: 500px; /* Máximo ancho del modal */
            width: 95%; /* Para pantallas pequeñas */
            box-shadow: var(--box-shadow);
            position: relative; /* Para el botón de cerrar */
        }
        .modal-content h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: var(--secondary-color);
            font-size: 1.5rem;
        }

        .modal-content label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--dark-gray);
            font-size: 0.9em;
        }

        .modal-content input[type="text"], /* Si tuvieras */
        .modal-content input[type="number"], /* Si tuvieras */
        .modal-content select {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border-radius: 6px;
            border: 1px solid var(--medium-gray);
            box-sizing: border-box; /* Importante para que padding no aumente el width */
            font-size: 0.95em;
        }
        .modal-content .btn,
        .modal-content button[type="submit"] { /* Para el botón del form */
            display: block;
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius);
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .modal-content .btn:hover,
        .modal-content button[type="submit"]:hover {
            background-color: #a0752c;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 1.8em;
            color: var(--dark-gray);
            cursor: pointer;
            line-height: 1;
        }
        .close-modal:hover {
            color: var(--danger-color);
        }

        #modal-color-options-carrito {
            display: flex;
            flex-wrap: wrap; /* Para que se ajusten si hay muchos colores */
            gap: 8px;
            margin-bottom: 15px;
        }
        #modal-color-options-carrito > div { /* Los círculos de color */
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
            box-shadow: 0 0 0 1px rgba(0,0,0,0.1) inset; /* Borde sutil */
            transition: border-color 0.2s;
        }
         #modal-color-options-carrito > div.active, /* Añadir una clase 'active' con JS */
         #modal-color-options-carrito > div[style*="border: 2px solid var(--primary-color)"], /* Para tu estilo actual */
         #modal-color-options-carrito > div:hover {
            border-color: var(--primary-color) !important; /* Asegurar que sobreescriba el inline style */
        }


        /* ---- Responsive Table Styling ---- */
        @media screen and (max-width: 768px) {
            #cart-table thead {
                display: none; /* Ocultar cabecera en móviles */
            }

            #cart-table tr {
                display: block;
                margin-bottom: 20px;
                border: 1px solid var(--medium-gray);
                border-radius: var(--border-radius);
                padding: 10px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            }

            #cart-table td {
                display: flex; /* Usar flex para alinear label y content */
                justify-content: space-between; /* Espacio entre label y content */
                align-items: center;
                padding: 8px 5px;
                border-bottom: 1px dashed var(--medium-gray); /* Línea separadora más sutil */
                text-align: right; /* Alinear contenido a la derecha */
                font-size: 0.9em;
            }
            #cart-table td:last-child {
                border-bottom: none;
            }

            #cart-table td::before {
                content: attr(data-label); /* Usaremos un atributo data-label */
                font-weight: bold;
                text-align: left; /* Alinear label a la izquierda */
                margin-right: 10px; /* Espacio entre label y valor */
                color: var(--secondary-color);
            }

            .cart-item-img {
                width: 70px; /* Imagen más pequeña en móvil */
                height: 70px;
                margin-right: auto; /* Para empujar la imagen a la izquierda si el td es flex */
            }
            /* Casos especiales para algunas celdas */
            #cart-table td:nth-of-type(1) { /* Imagen */
                justify-content: center; /* Centrar la imagen */
            }
            #cart-table td:nth-of-type(1)::before {
                display: none; /* No necesitamos label para la imagen si es obvia */
            }

            #cart-table td:nth-of-type(4) div { /* Cantidad */
                margin-left: auto; /* Empujar controles de cantidad a la derecha */
            }
            #cart-table input[type="number"] {
                width: 40px;
                padding: 5px;
            }

            .cart-actions {
                flex-direction: column; /* Apilar botones */
            }
            .cart-actions button {
                width: 100%; /* Botones ocupan todo el ancho */
            }
            .cart-actions .btn-shop {
                order: 3; /* Cambiar orden si es necesario, ej. Seguir comprando al final */
            }
            .cart-actions .btn-clear {
                order: 2;
            }
            .cart-actions .btn-checkout {
                order: 1;
            }

            #cart-total {
                font-size: 1.2em;
                text-align: center; /* Centrar total en móvil */
            }
        }
    </style>
</head>

<body>
    <?php include './arc/nav.php'; ?>

    <div id="cart-container">
        <h1>Mi Carrito</h1>
        <table id="cart-table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Talla</th>
                    <th>Color</th>
                    <th>Modificar</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="cart-items-table">
                <!-- Aquí se generan los productos con JS -->
            </tbody>
        </table>
        <div id="cart-total" style="display:none;">
            Total: $<span id="total-amount">0.00</span>
        </div>
        <div class="cart-actions">
            <button class="btn-shop" onclick="seguirComprando()" style="display:none;">Seguir comprando</button>
            <button id="btn-clear-cart" class="btn-clear" onclick="clearCart()" style="display:none;">Vaciar carrito</button>
            <button class="btn-checkout" onclick="checkout()" style="display:none;">Finalizar compra</button>
        </div>
    </div>

    <!-- Modal para modificar producto (el antiguo, considerar si aún es necesario o se unifica con el nuevo) -->
    <div class="modal-bg" id="modal-bg">
        <div class="modal-content" id="modal-content-old"> {/* Cambié ID para evitar conflictos si se mantiene */}
            <span class="close-modal" onclick="cerrarModal()">×</span>
            <h3>Modificar producto (Antiguo)</h3>
            <form id="form-modificar-producto">
                <input type="hidden" id="modal-index">
                <div>
                    <label for="modal-talla">Talla:</label>
                    <select id="modal-talla" required>
                        <option value="">Selecciona talla</option>
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                </div>
                <div>
                    <label for="modal-color">Color:</label>
                    <select id="modal-color" required>
                        <option value="">Selecciona color</option>
                        <option value="Negro">Negro</option>
                        <option value="Blanco">Blanco</option>
                        <option value="Rojo">Rojo</option>
                        <option value="Azul">Azul</option>
                        <option value="Verde">Verde</option>
                        <option value="Amarillo">Amarillo</option>
                    </select>
                </div>
                <button type="submit" class="btn">Actualizar</button>
            </form>
        </div>
    </div>

    <!-- Modal de Modificar Producto en Carrito (el más completo) -->
    <div id="modal-modificar-carrito" class="modal-bg"> 
      <div class="modal-content">
        <span class="close-modal" onclick="cerrarModalModificarCarrito()">×</span>
        <h3>Modificar producto</h3>
        <div style="text-align:center; margin-bottom: 15px;">
          <img id="modal-img-carrito" src="assets/images/placeholder.png" alt="Producto" style="width:150px;height:150px;object-fit:cover;border-radius:var(--border-radius);margin-bottom:10px; border: 1px solid var(--medium-gray);">
        </div>
        <form id="form-modificar-carrito">
          <input type="hidden" id="modal-index-carrito">
          <div>
            <label>Color:</label>
            <div id="modal-color-options-carrito">
            </div>
          </div>
          <div>
            <label for="modal-talla-carrito">Talla:</label>
            <select id="modal-talla-carrito" required></select>
          </div>
          <button type="submit" class="btn">Actualizar</button>
        </form>
      </div>
    </div>

      <?php
        echo "<script>console.log('PHP id_usuario:', '" . (isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 'NO') . "');</script>";
        ?>

    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        // Variable global para la última consulta de detalles del producto
        window.dataUltimaConsultaProducto = null; 

        function updateCartDisplay() {
            const cartTableBody = document.getElementById('cart-items-table');
            const cartTotalDiv = document.getElementById('cart-total');
            const btnShop = document.querySelector('.btn-shop');
            const btnClear = document.getElementById('btn-clear-cart');
            const btnCheckout = document.querySelector('.btn-checkout');

            cartTableBody.innerHTML = ''; // Limpiar tabla

            if (cart.length === 0) {
                cartTableBody.innerHTML = `<tr><td colspan="8"><div class="cart-empty-message">No tienes productos en el carrito.</div></td></tr>`;
                cartTotalDiv.style.display = 'none';
                btnCheckout.style.display = 'none';
                btnClear.style.display = 'none';
                btnShop.style.display = 'inline-block'; // Mostrar "Seguir comprando"
                return;
            } else {
                cartTotalDiv.style.display = 'block';
                btnCheckout.style.display = 'inline-block';
                btnClear.style.display = 'inline-block';
                btnShop.style.display = 'inline-block'; // Puede estar visible siempre o según lógica
            }

            cart.forEach((item, index) => {
                let nombreBase = item.name;
                let color = item.color || '';
                let talla = item.talla || '';

                const match = nombreBase.match(/^(.*?)\s*-\s*(.*?)\s*-\s*Talla\s*(.+)$/i);
                if (match) {
                    nombreBase = match[1].trim();
                    color = color || match[2].trim();
                    talla = talla || match[3].trim();
                }

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td data-label="Imagen:"><img src="${item.imageUrl || 'assets/images/placeholder.png'}" alt="${nombreBase}" class="cart-item-img"></td>
                    <td data-label="Nombre:" style="font-weight:500;">${nombreBase}</td>
                    <td data-label="Precio:" style="font-weight:600;">$${(item.price * item.quantity).toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                    <td data-label="Cantidad:">
                        <div style="display:flex;align-items:center;justify-content:center;gap:5px;">
                            <button class="cart-btn quantity-btn" onclick="decreaseQuantity(${index})" title="Restar">-</button>
                            <input type="number" min="1" value="${item.quantity}" 
                                onchange="actualizarCantidad(${index}, this.value)" 
                                onblur="actualizarCantidad(${index}, this.value)"
                                onkeydown="if(event.key==='Enter'){ this.blur(); event.preventDefault(); }">
                            <button class="cart-btn quantity-btn" onclick="increaseQuantity(${index})" title="Sumar">+</button>
                        </div>
                    </td>
                    <td data-label="Talla:">${talla || '-'}</td>
                    <td data-label="Color:">${color || '-'}</td>
                    <td data-label="Modificar:">
                        <button class="cart-btn modify-btn" title="Modificar" onclick="abrirModalModificarCarrito(${index})">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                    <td data-label="Acción:">
                        <button class="cart-btn remove-btn" onclick="removeItem(${index})" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                `;
                cartTableBody.appendChild(tr);
            });

            // Total
            const totalPrice = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            document.getElementById('total-amount').textContent = totalPrice.toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        }

        // Modal antiguo (si aún lo usas)
        function abrirModal(index) {
            const modalBg = document.getElementById('modal-bg'); // El modal antiguo
            const item = cart[index];
            document.getElementById('modal-index').value = index;
            document.getElementById('modal-talla').value = item.talla || '';
            document.getElementById('modal-color').value = item.color || '';
            modalBg.style.display = 'flex';
        }
        function cerrarModal() {
            document.getElementById('modal-bg').style.display = 'none';
        }
         // Formulario del modal antiguo
        document.getElementById('form-modificar-producto').addEventListener('submit', function(e) {
            e.preventDefault();
            const index = parseInt(document.getElementById('modal-index').value);
            const talla = document.getElementById('modal-talla').value;
            const color = document.getElementById('modal-color').value;
            if (cart[index]) {
                cart[index].talla = talla;
                cart[index].color = color;
                saveCart();
                updateCartDisplay();
                cerrarModal();
                Swal.fire('Producto actualizado', 'La talla y el color fueron modificados.', 'success');
            }
        });


        // Modal de modificar carrito (el nuevo y más completo)
        async function abrirModalModificarCarrito(index) {
            const item = cart[index];
            if (!item) {
                console.error("Item no encontrado en el carrito para el índice:", index);
                Swal.fire('Error', 'No se pudo encontrar el producto en el carrito.', 'error');
                return;
            }
            document.getElementById('modal-index-carrito').value = index;
            document.getElementById('modal-img-carrito').src = item.imageUrl || 'assets/images/placeholder.png';
            document.getElementById('modal-img-carrito').alt = item.name;

            try {
                const resp = await fetch(`shop.php?accion=obtener_detalles_producto&id_producto=${item.idProducto}`);
                if (!resp.ok) {
                    throw new Error(`Error HTTP ${resp.status} al obtener detalles.`);
                }
                const data = await resp.json();
                window.dataUltimaConsultaProducto = data; // Guardar para usar en submit

                const colorOptionsContainer = document.getElementById('modal-color-options-carrito');
                colorOptionsContainer.innerHTML = '';
                let colorSeleccionadoInicial = item.color;
                let tallasParaColorInicial = [];

                if (data.colores_disponibles && data.colores_disponibles.length > 0) {
                    data.colores_disponibles.forEach((colorInfo) => {
                        const colorDiv = document.createElement('div');
                        colorDiv.style.backgroundColor = colorInfo.codigo_hex;
                        colorDiv.title = colorInfo.nombre_color;
                        // Aplicar borde si es el color actual del item
                        if (item.color === colorInfo.nombre_color) {
                            colorDiv.style.border = '2px solid var(--primary-color)';
                            // Actualizar imagen y tallas para el color seleccionado inicialmente
                            document.getElementById('modal-img-carrito').src = (colorInfo.imagenes_del_color && colorInfo.imagenes_del_color[0]) || item.imageUrl || 'assets/images/placeholder.png';
                            tallasParaColorInicial = colorInfo.tallas_en_este_color;
                        }

                        colorDiv.onclick = () => {
                            document.getElementById('modal-img-carrito').src = (colorInfo.imagenes_del_color && colorInfo.imagenes_del_color[0]) || item.imageUrl || 'assets/images/placeholder.png';
                            Array.from(colorOptionsContainer.children).forEach(sibling => sibling.style.border = '2px solid transparent'); // Quitar borde de otros
                            colorDiv.style.border = '2px solid var(--primary-color)'; // Poner borde al seleccionado
                            poblarTallasCarrito(colorInfo.tallas_en_este_color, null); // null para que seleccione la primera talla disponible para el nuevo color
                            // Guardar color seleccionado en un atributo temporal del modal o una variable global si es más fácil
                            document.getElementById('modal-modificar-carrito').setAttribute('data-current-color', colorInfo.nombre_color);
                        };
                        colorOptionsContainer.appendChild(colorDiv);
                    });
                    poblarTallasCarrito(tallasParaColorInicial, item.talla); // Poblar tallas para el color inicial
                     document.getElementById('modal-modificar-carrito').setAttribute('data-current-color', colorSeleccionadoInicial);

                } else if (data.variantes_sin_color_especifico && data.variantes_sin_color_especifico.length > 0) {
                    colorOptionsContainer.innerHTML = '<small style="color: var(--dark-gray);">Color único / No aplica</small>';
                    poblarTallasCarrito(data.variantes_sin_color_especifico, item.talla);
                    document.getElementById('modal-img-carrito').src = (data.producto_base.imagenes_generales && data.producto_base.imagenes_generales[0]) || item.imageUrl || 'assets/images/placeholder.png';
                    document.getElementById('modal-modificar-carrito').setAttribute('data-current-color', ''); // Sin color específico
                } else {
                    colorOptionsContainer.innerHTML = '<small style="color: var(--dark-gray);">No hay opciones de color.</small>';
                    poblarTallasCarrito([], item.talla); // Sin tallas si no hay colores
                    document.getElementById('modal-modificar-carrito').setAttribute('data-current-color', '');
                }
                document.getElementById('modal-modificar-carrito').style.display = 'flex';
            } catch (error) {
                console.error("Error al abrir modal de modificación:", error);
                Swal.fire('Error', `No se pudieron cargar los detalles del producto: ${error.message}`, 'error');
            }
        }

        function poblarTallasCarrito(tallasDisponibles, tallaActual) {
            const tallaSelect = document.getElementById('modal-talla-carrito');
            tallaSelect.innerHTML = '';
            if (tallasDisponibles && tallasDisponibles.length > 0) {
                tallasDisponibles.forEach(talla => {
                    const opt = document.createElement('option');
                    opt.value = talla.nombre_talla;
                    opt.textContent = talla.nombre_talla;
                    if (talla.stock <= 0) { // Opcional: deshabilitar si no hay stock
                        opt.disabled = true;
                        opt.textContent += ' (Agotado)';
                    }
                    tallaSelect.appendChild(opt);
                });
                // Intenta seleccionar la talla actual si está disponible, sino la primera válida
                if (tallaActual && tallasDisponibles.some(t => t.nombre_talla === tallaActual && t.stock > 0)) {
                    tallaSelect.value = tallaActual;
                } else {
                     // Selecciona la primera talla disponible con stock
                    const primeraTallaConStock = tallasDisponibles.find(t => t.stock > 0);
                    if (primeraTallaConStock) {
                        tallaSelect.value = primeraTallaConStock.nombre_talla;
                    } else if (tallasDisponibles.length > 0) {
                         tallaSelect.value = tallasDisponibles[0].nombre_talla; //Fallback a la primera si todas están agotadas
                    } else {
                        tallaSelect.innerHTML = '<option value="">No hay tallas</option>';
                    }
                }
            } else {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'No hay tallas disponibles';
                tallaSelect.appendChild(opt);
                tallaSelect.value = '';
            }
        }

        document.getElementById('form-modificar-carrito').addEventListener('submit', function(e) {
            e.preventDefault();
            const index = parseInt(document.getElementById('modal-index-carrito').value);
            const nuevaTalla = document.getElementById('modal-talla-carrito').value;
            
            // Obtener color seleccionado del div con borde o del atributo data-current-color
            const colorDivActivo = document.querySelector('#modal-color-options-carrito > div[style*="border: 2px solid var(--primary-color)"]');
            let nuevoColor = '';
            if (colorDivActivo) {
                nuevoColor = colorDivActivo.title;
            } else {
                // Fallback si no se encuentra el div activo (ej. producto sin opciones de color)
                nuevoColor = document.getElementById('modal-modificar-carrito').getAttribute('data-current-color') || '';
            }

            const nuevaImagen = document.getElementById('modal-img-carrito').src;

            if (!cart[index]) {
                Swal.fire('Error', 'El producto a modificar no existe en el carrito.', 'error');
                return;
            }
            
            // --- IMPORTANTE: Buscar el id_detalles_productos correcto ---
            let nuevoIdDetalle = cart[index].id_detalles_productos; // Mantener el actual por defecto
            const datosProducto = window.dataUltimaConsultaProducto;

            if (datosProducto) {
                let varianteEncontrada = null;
                if (datosProducto.colores_disponibles && datosProducto.colores_disponibles.length > 0) {
                    const colorObj = datosProducto.colores_disponibles.find(c => c.nombre_color === nuevoColor);
                    if (colorObj && colorObj.tallas_en_este_color) {
                        varianteEncontrada = colorObj.tallas_en_este_color.find(t => t.nombre_talla === nuevaTalla);
                    }
                } else if (datosProducto.variantes_sin_color_especifico && datosProducto.variantes_sin_color_especifico.length > 0) {
                     varianteEncontrada = datosProducto.variantes_sin_color_especifico.find(t => t.nombre_talla === nuevaTalla);
                }

                if (varianteEncontrada && varianteEncontrada.id_detalles_productos) {
                    if (varianteEncontrada.stock <= 0 && varianteEncontrada.id_detalles_productos !== cart[index].id_detalles_productos) {
                         Swal.fire('Agotado', 'La talla seleccionada para este color está agotada.', 'warning');
                         return; // No actualizar si está agotado y es una variante diferente
                    }
                    nuevoIdDetalle = varianteEncontrada.id_detalles_productos;
                } else if (nuevaTalla) { // Solo si se seleccionó una talla y no se encontró variante
                    Swal.fire('No disponible', 'La combinación de talla y color seleccionada no está disponible.', 'warning');
                    return; // No actualizar si la combinación no es válida
                }
            }
            
            // Verificar si la nueva combinación ya existe en el carrito (excluyendo el item actual)
            const existeEnCarrito = cart.find((item, i) => 
                i !== index &&
                item.idProducto === cart[index].idProducto &&
                item.id_detalles_productos === nuevoIdDetalle
            );

            if (existeEnCarrito) {
                Swal.fire({
                    title: 'Producto ya existente',
                    text: 'Esta combinación de producto, talla y color ya está en tu carrito. ¿Deseas combinar las cantidades?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, combinar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Sumar cantidad al existente y eliminar el actual
                        existeEnCarrito.quantity += cart[index].quantity;
                        cart.splice(index, 1); // Eliminar el item que se estaba modificando
                        saveCart();
                        updateCartDisplay();
                        cerrarModalModificarCarrito();
                        Swal.fire('Combinado', 'Las cantidades se han sumado al producto existente.', 'success');
                    }
                });
                return; // Salir para no continuar con la actualización normal
            }


            // Actualizar el item en el carrito
            cart[index].talla = nuevaTalla;
            cart[index].color = nuevoColor;
            cart[index].imageUrl = nuevaImagen;
            cart[index].id_detalles_productos = nuevoIdDetalle;
            
            saveCart();
            updateCartDisplay();
            cerrarModalModificarCarrito();
            Swal.fire('Producto actualizado', 'Se modificó la talla, el color y/o la imagen.', 'success');
        });

        function cerrarModalModificarCarrito() {
            document.getElementById('modal-modificar-carrito').style.display = 'none';
            window.dataUltimaConsultaProducto = null; // Limpiar datos al cerrar
        }

        function actualizarCantidad(index, value) {
            let cantidad = parseInt(value);
            if (isNaN(cantidad) || cantidad < 1) {
                cantidad = 1;
            }
            const item = cart[index];
            fetch('envio/validar_stock_tienda.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify([{...item, quantity: cantidad}])
            })
            .then(res => res.json())
            .then(data => {
                if (data.sin_stock && data.sin_stock.length > 0) {
                    Swal.fire(
                        'Stock insuficiente',
                        `Solo hay ${data.sin_stock[0].stock_disponible} unidades disponibles de ${item.name} (${item.color}, ${item.talla})`,
                        'warning'
                    );
                    cart[index].quantity = data.sin_stock[0].stock_disponible > 0 ? data.sin_stock[0].stock_disponible : 1;
                    saveCart();
                    updateCartDisplay();
                } else {
                    cart[index].quantity = cantidad;
                    saveCart();
                    updateCartDisplay();
                }
            });
        }

        function increaseQuantity(index) {
          const item = cart[index];
          // Validar stock antes de aumentar
          fetch('envio/validar_stock_tienda.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify([{...item, quantity: item.quantity + 1}])
          })
          .then(res => res.json())
          .then(data => {
            // Recarga el carrito desde localStorage para evitar inconsistencias
            cart = JSON.parse(localStorage.getItem('cart')) || cart;
            if (data.sin_stock && data.sin_stock.length > 0) {
              Swal.fire(
                'Stock insuficiente',
                `Solo hay ${data.sin_stock[0].stock_disponible} unidades disponibles de ${item.name} (${item.color}, ${item.talla})`,
                'warning'
              );
            } else {
              cart[index].quantity++;
              saveCart();
              updateCartDisplay();
            }
          })
          .catch(() => {
            Swal.fire('Error', 'No se pudo validar el stock. Intenta de nuevo.', 'error');
          });
        }

        function decreaseQuantity(index) {
            if (cart[index].quantity > 1) {
                cart[index].quantity--;
                saveCart();
                updateCartDisplay();
            } else {
                // Si la cantidad es 1 y se presiona "-", preguntar antes de eliminar
                removeItem(index); 
            }
        }

        function removeItem(index) {
            Swal.fire({
                title: '¿Eliminar producto?',
                text: "¿Estás seguro de que quieres eliminar este producto del carrito?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger-color)',
                cancelButtonColor: 'var(--dark-gray)',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    cart.splice(index, 1);
                    saveCart();
                    updateCartDisplay();
                    Swal.fire('Eliminado', 'El producto fue eliminado del carrito.', 'success');
                }
            });
        }

        function clearCart() {
            Swal.fire({
                title: '¿Vaciar carrito?',
                text: "¿Estás seguro de que quieres eliminar todos los productos?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e57373', // Rojo suave
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, vaciar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    cart = [];
                    saveCart();
                    updateCartDisplay();
                    Swal.fire('Carrito vacío', 'Todos los productos fueron eliminados.', 'success');
                }
            });
        }

       function saveCart() {
  localStorage.setItem('cart', JSON.stringify(cart));
}
        function seguirComprando() {
            window.location.href = 'shop.php'; // O la página de tu catálogo
        }

        function checkout() {
            if (cart.length === 0) {
                Swal.fire('Carrito vacío', 'Agrega productos antes de finalizar la compra.', 'info');
                return;
            }
            if (!usuarioLogueado) { 
                Swal.fire({
                    icon: 'warning',
                    title: 'Debes iniciar sesión',
                    text: 'Para realizar tu compra debes iniciar sesión.',
                    confirmButtonText: 'Iniciar sesión'
                }).then((result) => {
                    if (result.isConfirmed) {
                        saveCart(); 
                        window.location.href = 'login/login.php?redirect=envio/envio.php';
                    }
                });
                return;
            }
            // Si está logueado, intentar guardar en servidor
            fetch('guardar_carrito.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
                body: JSON.stringify(cart),
                credentials: 'same-origin' 
            })
            .then(res => {
                if (!res.ok) {
                    return res.text().then(text => { throw new Error("Error del servidor: " + text) });
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.href = 'envio/envio.php';
                } else {
                    Swal.fire('Error', data.message || 'No se pudo guardar el carrito en el servidor. Intenta de nuevo.', 'error');
                }
            })
            .catch(error => {
                console.error('Error en checkout:', error);
                Swal.fire('Error de Conexión', 'Hubo un problema al conectar con el servidor: ' + error.message, 'error');
            });
        }

        const usuarioLogueado = <?php echo isset($_SESSION['id_usuario']) ? 'true' : 'false'; ?>;
        // Deberías usar id_usuario o una variable de sesión más robusta que solo el nombre.

      

        // Inicializar la visualización del carrito al cargar la página
        document.addEventListener('DOMContentLoaded', () => {
            updateCartDisplay();
        });

    </script>

    <?php include './arc/footer.php';?>
</body>
</html>
