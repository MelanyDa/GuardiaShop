<?php
// editar_producto.php
$conexion = new mysqli("localhost", "root", "", "guardiashop");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
$id = intval($_GET['id']);
$sql = "SELECT * FROM productos WHERE id_producto = $id";
$res = $conexion->query($sql);
$producto = $res->fetch_assoc();

// Obtener categorías
$categorias = [];
$cat_result = $conexion->query("SELECT id_categoria, nombre FROM categoria");
while ($row = $cat_result->fetch_assoc()) {
    $categorias[] = $row;
}

// Obtener sesiones
$sesiones = [];
$ses_result = $conexion->query("SELECT id_sesion, nombre FROM sesiones");
while ($row = $ses_result->fetch_assoc()) {
    $sesiones[] = $row;
}
// Obtener colores
$colores = [];
$color_result = $conexion->query("SELECT id_color, nombre, codigo_hexadecimal FROM color_productos");
while ($row = $color_result->fetch_assoc()) {
    $colores[] = $row;
}
// Obtener tallas
$tallas = [];
$tallas_result = $conexion->query("SELECT id_talla, nombre_talla FROM talla_productos");
if (!$tallas_result) {
    die("Error en la consulta de tallas: " . $conexion->error);
}
while ($row = $tallas_result->fetch_assoc()) {
    $tallas[] = $row;
}
$detalles_edit = $conexion->query("SELECT * FROM detalles_productos WHERE id_producto = $id");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Editar Producto</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-editar-producto {
            max-width: 800px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(44, 73, 38, 0.08);
            padding: 2rem;
        }
        .form-editar-producto h2 {
            color: #2c4926;
            font-weight: bold;
            margin-bottom: 2rem;
        }
        /* Círculos de color para el selector de color asociado */
        .form-editar-producto .color-circle {
            display: inline-block;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            margin-right: 6px;
            vertical-align: middle;
            border: 1.5px solid #ccc;
            cursor: pointer;
            transition: border 0.2s;
        }
        .form-editar-producto .color-circle.selected {
            border: 2.5px solid #2c4926;
        }
    </style>
</head>
<body>
    <div class="container form-editar-producto">
        <h2><i class="fas fa-edit"></i> Editar Producto</h2>
        <form action="acciones/productos/actualizar_producto.php" method="POST"  enctype="multipart/form-data">
            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
            <div class="mb-3">
                
                <?php
                // Obtener la imagen actual
                $img = $conexion->query("SELECT imagen, id_color_asociado FROM producto_imagen WHERE id_producto = {$producto['id_producto']} LIMIT 1");
                $img_row = $img->fetch_assoc();
                $id_color_actual = $img_row['id_color_asociado'] ?? '';
                ?>
            </div>
            <div class="mb-3">
                <label>Nueva imagen (opcional)</label>
                <input type="file" name="nueva_imagen" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label>Código</label>
                <input type="text" name="codigo" class="form-control" value="<?php echo htmlspecialchars($producto['codigo']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
            </div>
            <div class="mb-3">
                <label>Categoría</label>
                <select name="id_categoria" class="form-select" required>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo $cat['id_categoria']; ?>" <?php if ($cat['id_categoria'] == $producto['id_categoria']) echo 'selected'; ?>>
                            <?php echo $cat['nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Sesión</label>
                <select name="id_sesion" class="form-select" required>
                    <?php foreach ($sesiones as $ses): ?>
                        <option value="<?php echo $ses['id_sesion']; ?>" <?php if ($ses['id_sesion'] == $producto['id_sesion']) echo 'selected'; ?>>
                            <?php echo $ses['nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Color asociado a la imagen principal</label>
                <div id="color-circles-group">
                    <?php foreach ($colores as $c): ?>
                        <label style="cursor:pointer;">
                            <input type="radio" name="color_imagen" value="<?php echo $c['id_color']; ?>" style="display:none;"
                                <?php if ($c['id_color'] == $id_color_actual) echo 'checked'; ?>>
                            <span class="color-circle<?php if ($c['id_color'] == $id_color_actual) echo ' selected'; ?>"
                                  style="background:<?php echo htmlspecialchars($c['codigo_hexadecimal'] ?? '#eee'); ?>;"
                                  title="<?php echo htmlspecialchars($c['nombre']); ?>">
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Combinaciones de talla/color/precio/stock -->
            <div id="editar-combinaciones-container">
                <?php while ($comb = $detalles_edit->fetch_assoc()):
                    $id_detalle = $comb['id_detalle'] ?? '';
                    $id_talla = $comb['id_tallas'] ?? '';
                    $id_color = $comb['id_color'] ?? '';
                    $precio = $comb['precio_producto'] ?? '';
                    $stock = $comb['stock'] ?? '';
                ?>
                <div class="row combinacion-item mb-3">
                    <input type="hidden" name="id_detalle[]" value="<?php echo htmlspecialchars($id_detalle); ?>">
                    <div class="col">
                        <label>Talla</label>
                        <select name="talla[]" class="form-select" required>
                            <?php foreach ($tallas as $t): ?>
                                <option value="<?php echo htmlspecialchars($t['id_talla']); ?>" <?php if ($t['id_talla'] == $id_talla) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($t['nombre_talla']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col">
                        <label>Color</label>
                        <select name="color[]" class="form-select" required>
                            <?php foreach ($colores as $c): ?>
                                <option value="<?php echo htmlspecialchars($c['id_color']); ?>" <?php if ($c['id_color'] == $id_color) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($c['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col">
                        <label>Precio</label>
                        <input type="number" name="precio[]" class="form-control" value="<?php echo htmlspecialchars($precio); ?>" required>
                    </div>
                    <div class="col">
                        <label>Stock</label>
                        <input type="number" name="stock[]" class="form-control" value="<?php echo htmlspecialchars($stock); ?>" required>
                    </div>
                    <div class="col-auto d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-combinacion">X</button>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <button type="button" class="btn btn-secondary mb-3" onclick="agregarCombinacion()">Agregar Combinación</button>
            <div class="mb-3">
                <button type="submit" class="btn btn-success">Guardar cambios</button>
                <a href="g_productos.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
    <script>
    function agregarCombinacion() {
        const container = document.getElementById("editar-combinaciones-container");
        const baseItem = container.querySelector(".combinacion-item").cloneNode(true);

        baseItem.querySelectorAll("input").forEach(input => {
            if (input.name === "id_detalle[]") input.remove();
            else input.value = "";
        });
        baseItem.querySelectorAll("select").forEach(select => select.selectedIndex = 0);
        container.appendChild(baseItem);
    }
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-combinacion")) {
            const items = document.querySelectorAll(".combinacion-item");
            if (items.length > 1) {
                e.target.closest(".combinacion-item").remove();
            }
        }
    });
    document.querySelectorAll('#color-circles-group input[type="radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('#color-circles-group .color-circle').forEach(function(circle) {
                circle.classList.remove('selected');
            });
            this.nextElementSibling.classList.add('selected');
        });
    });
    </script>
</body>
</html>