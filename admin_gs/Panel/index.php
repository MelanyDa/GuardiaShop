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
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>
 
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome (para íconos) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .select-mes-custom {
        border-radius: 20px;
        background: #f8f9fc;
        border: 1px solid #d1d3e2;
        box-shadow: 0 2px 6px rgba(44,73,38,0.07);
        transition: box-shadow 0.2s;
    }
    .select-mes-custom:focus {
        box-shadow: 0 0 0 2px #2c4926;
        border-color: #2c4926;
    }
    .dashboard-title {
        font-size: 2.2rem;
        font-weight: bold;
        background: linear-gradient(90deg, #2c4926 0%, #4e944f 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        
        letter-spacing: 1px;
        text-shadow: 0 2px 8px rgba(44,73,38,0.10);
        margin-bottom: 0;
    }
    .dashboard-icon {
        background: #fff;
        border-radius: 50%;
        padding: 0.4em 0.6em;
        box-shadow: 0 2px 8px rgba(44,73,38,0.10);
        color: #2c4926;
        font-size: 1.5em;
        margin-right: 0.6em;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php
       require_once($_SERVER['DOCUMENT_ROOT'].'/guardiashop/admin_gs/Panel/comun/menu.php');
        ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

           
        <?php
       require_once($_SERVER['DOCUMENT_ROOT'].'/guardiashop/admin_gs/Panel/comun/nav.php');
        ?>
        <?php
        // Conexión a la base de datos
        $mysqli = new mysqli("localhost", "root", "", "guardiashop");

        $mes_seleccionado = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
        $sql_mensual_fisica = "
            SELECT SUM(total_factura) AS total_mensual
            FROM factura_venta_f
            WHERE MONTH(fecha_creacion_registro) = $mes_seleccionado
            AND YEAR(fecha_creacion_registro) = YEAR(CURDATE())
        ";
        $res_mensual_fisica = $mysqli->query($sql_mensual_fisica)->fetch_assoc()['total_mensual'] ?? 0;

        // Ganancias anuales tienda física (usando total_factura)
        $sql_anual_fisica = "
            SELECT SUM(total_factura) AS total_anual
            FROM factura_venta_f
            WHERE YEAR(fecha_creacion_registro) = YEAR(CURDATE())
        ";
        $res_anual_fisica = $mysqli->query($sql_anual_fisica)->fetch_assoc()['total_anual'] ?? 0;

        // Obtener años disponibles en la base de datos
        $anios = [];
        $res_anios = $mysqli->query("SELECT DISTINCT YEAR(fecha_creacion_registro) as anio FROM factura_venta_f ORDER BY anio DESC");
        while ($row = $res_anios->fetch_assoc()) {
            $anios[] = $row['anio'];
        }
        $anio_actual = date('Y');

        // Respuestas pendientes (estado = 'nuevo')
        $sql_pendientes = "SELECT COUNT(*) AS pendientes FROM contactanos WHERE estado = 'nuevo'";
        $res_pendientes = $mysqli->query($sql_pendientes)->fetch_assoc()['pendientes'] ?? 0;

        $mes_seleccionado_online = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
        $sql_mensual_online = "
            SELECT SUM(total_factura) AS total_mensual
            FROM facturas_venta
            WHERE MONTH(fecha_creacion_registro) = $mes_seleccionado_online
            AND YEAR(fecha_creacion_registro) = YEAR(CURDATE())
        ";
        $res_mensual_online = $mysqli->query($sql_mensual_online)->fetch_assoc()['total_mensual'] ?? 0;

        $sql_anual_online = "
            SELECT SUM(total_factura) AS total_anual
            FROM facturas_venta
            WHERE YEAR(fecha_creacion_registro) = YEAR(CURDATE())
        ";
        $res_anual_online = $mysqli->query($sql_anual_online)->fetch_assoc()['total_anual'] ?? 0;
        ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 d-flex align-items-center">
                            <span class="dashboard-icon mr-2"><i class="fas fa-tachometer-alt"></i></span>
                            <span class="dashboard-title">Dashboard</span>
                        </h1>
                        
                    </div>

                    <!-- NUEVAS TARJETAS DE MÉTRICAS -->
                    <div class="row">

                    <?php
                        $meses = [
                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
                            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                        ];
                        $mes_actual = date('n');
                        ?>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Ganancias Mensuales <span class="badge badge-primary">Física</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2" style="gap: 0.5rem;">
                                                <span class="badge badge-primary p-2" style="font-size:1.1em;">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                                <select id="select-mes" class="form-control form-control-sm select-mes-custom" style="width:auto; min-width:120px;">
                                                    <?php foreach ($meses as $num => $nombre): ?>
                                                        <option value="<?php echo $num; ?>" <?php if ($num == $mes_actual) echo 'selected'; ?>>
                                                            <?php echo $nombre; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="valor-ganancia-mes">
                                                $<?php echo number_format($res_mensual_fisica, 0, ',', '.'); ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-store fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ganancias Mensuales Tienda Online -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Ganancias Mensuales <span class="badge badge-info">Online</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2" style="gap: 0.5rem;">
                                                <span class="badge badge-info p-2" style="font-size:1.1em;">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                                <select id="select-mes-online" class="form-control form-control-sm select-mes-custom" style="width:auto; min-width:120px;">
                                                    <?php foreach ($meses as $num => $nombre): ?>
                                                        <option value="<?php echo $num; ?>" <?php if ($num == $mes_actual) echo 'selected'; ?>>
                                                            <?php echo $nombre; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="valor-ganancia-mes-online">
                                                $<?php echo number_format($res_mensual_online, 0, ',', '.'); ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-globe fa-2x text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ganancias Anuales Tienda Física -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Ganancias Anuales <span class="badge badge-success">Física</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2" style="gap: 0.5rem;">
                                                <span class="badge badge-success p-2" style="font-size:1.1em;">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                                <select id="select-anio" class="form-control form-control-sm select-mes-custom" style="width:auto; min-width:100px;">
                                                    <?php foreach ($anios as $anio): ?>
                                                        <option value="<?php echo $anio; ?>" <?php if ($anio == $anio_actual) echo 'selected'; ?>>
                                                            <?php echo $anio; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="valor-ganancia-anio">
                                                $<?php echo number_format($res_anual_fisica, 0, ',', '.'); ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-store-alt fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ganancias Anuales Tienda Online -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Ganancias Anuales <span class="badge badge-warning">Online</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2" style="gap: 0.5rem;">
                                                <span class="badge badge-warning p-2" style="font-size:1.1em;">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                                <select id="select-anio-online" class="form-control form-control-sm select-mes-custom" style="width:auto; min-width:100px;">
                                                    <?php foreach ($anios as $anio): ?>
                                                        <option value="<?php echo $anio; ?>" <?php if ($anio == $anio_actual) echo 'selected'; ?>>
                                                            <?php echo $anio; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="valor-ganancia-anio-online">
                                                $<?php echo number_format($res_anual_online, 0, ',', '.'); ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-globe-americas fa-2x text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Respuestas Pendientes -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Respuestas Pendientes
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $res_pendientes; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-envelope-open-text fa-2x text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta: Usuarios Registrados (Clientes) -->
                        <?php
                        $sql_total_clientes = "SELECT COUNT(*) AS total_clientes FROM usuario WHERE rol = 'cliente'";
                        $res_total_clientes = $mysqli->query($sql_total_clientes)->fetch_assoc()['total_clientes'] ?? 0;
                        ?>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Usuarios Registrados <span class="badge badge-primary">Clientes</span>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $res_total_clientes; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tarjeta: Total de Productos -->
                        <?php
                        $sql_total_productos = "SELECT COUNT(*) AS total_productos FROM productos";
                        $res_total_productos = $mysqli->query($sql_total_productos)->fetch_assoc()['total_productos'] ?? 0;
                        ?>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Total de Productos
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $res_total_productos; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-boxes fa-2x text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                        <!-- Tarjeta: Pedidos Pendientes -->
                    <?php
                    $sql_pedidos_pendientes = "SELECT COUNT(*) AS total_pedidos_pendientes FROM pedido WHERE estado = 'pendiente'";
                    $res_pedidos_pendientes = $mysqli->query($sql_pedidos_pendientes)->fetch_assoc()['total_pedidos_pendientes'] ?? 0;
                    ?>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pedidos Pendientes
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $res_pedidos_pendientes; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-truck-loading fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Gráfica de Ganancias Mensuales -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(90deg, #2c4926 0%, #4e944f 100%); border-radius: 0.5rem 0.5rem 0 0;">
                                    <h6 class="m-0 font-weight-bold text-white" id="titulo-grafica">
                                        Ganancias mensuales por año (<span id="tipo-titulo">Física</span>)
                                    </h6>
                                    <div class="btn-group-custom" role="group" aria-label="Tipo de tienda">
                                        <button type="button" class="btn-tipo-tienda active" id="btn-fisica">
                                            <i class="fas fa-store"></i> Física
                                        </button>
                                        <button type="button" class="btn-tipo-tienda" id="btn-online">
                                            <i class="fas fa-globe"></i> Online
                                        </button>
                                    </div>
                                    <div>
                                        <select id="select-ano-grafica" class="form-control form-control-sm select-mes-custom" style="width:auto; min-width:100px; display:inline-block;">
                                            <?php foreach ($anios as $anio): ?>
                                                <option value="<?php echo $anio; ?>" <?php if ($anio == $anio_actual) echo 'selected'; ?>>
                                                    <?php echo $anio; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-body" style="background: #f8f9fc; ">
                                <div class="chart-area" style="width: 600px; height: 300px; margin: 0 auto;">
                                        <canvas id="graficaGananciasMes" width="600" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <style>
.btn-group-custom {
    display: flex;
    gap: 0.5rem;
}

.btn-tipo-tienda {
    border: none;
    padding: 0.5rem 1.2rem;
    border-radius: 20px;
    font-weight: bold;
    font-size: 1em;
    background: #fff;
    color: #2c4926;
    box-shadow: 0 2px 8px rgba(44,73,38,0.10);
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    outline: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5em;
}

.btn-tipo-tienda#btn-fisica {
    border: 2px solid #2c4926;
}

.btn-tipo-tienda#btn-online {
    border: 2px solid #4e944f;
}

.btn-tipo-tienda.active#btn-fisica {
    background: linear-gradient(90deg, #2c4926 60%, #4e944f 100%);
    color: #fff;
    box-shadow: 0 4px 16px rgba(44,73,38,0.15);
}

.btn-tipo-tienda.active#btn-online {
    background: linear-gradient(90deg, #4e944f 60%, #2c4926 100%);
    color: #fff;
    box-shadow: 0 4px 16px rgba(78,148,79,0.15);
}

.btn-tipo-tienda:not(.active):hover {
    background: #f8f9fc;
    color: #4e944f;
    border-color: #4e944f;
}
</style>
                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between"style="background: linear-gradient(90deg, #2c4926 0%, #4e944f 100%); border-radius: 0.5rem 0.5rem 0 0;">
                                    <h6 class="m-0 font-weight-bold text-white" >Fuentes de ingresos
                                    </h6>
                                    
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle" style="color: #2c4926;"></i> Tienda Física
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle" style="color: #4e944f;"></i> Tienda Online
                                        </span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                    <?php
                    
                    // Conexión a la base de datos
                    $mysqli = new mysqli("localhost", "root", "", "guardiashop");
                    
                    // Producto más vendido
                    $sql_mas = "SELECT p.id_producto, p.nombre, SUM(d.cantidad) AS total_vendido
                    FROM productos p
                    LEFT JOIN detalles_factura_f d ON d.id_producto = p.id_producto
                    GROUP BY p.id_producto, p.nombre
                    HAVING total_vendido > 0
                    ORDER BY total_vendido DESC
                    LIMIT 1";
                    $result_mas = $mysqli->query($sql_mas);
                    $mas_vendido = $result_mas->fetch_assoc();
                    
                    // Producto menos vendido
                    $sql_menos = "SELECT p.id_producto, p.nombre, SUM(d.cantidad) AS total_vendido
                    FROM productos p
                    JOIN detalles_factura_f d ON d.id_producto = p.id_producto
                    GROUP BY p.id_producto, p.nombre
                    HAVING total_vendido > 0
                    ORDER BY total_vendido ASC
                    LIMIT 1";
                    $result_menos = $mysqli->query($sql_menos);
                    $menos_vendido = $result_menos->fetch_assoc();
                    
                    // Producto más vendido tienda online
                    $sql_mas_online = "SELECT p.id_producto, p.nombre, SUM(dp.cantidad) AS total_vendido
                    FROM productos p
                    JOIN detalles_productos dpd ON dpd.id_producto = p.id_producto
                    JOIN detalles_pedido dp ON dp.id_detalles_productos = dpd.id_detalles_productos
                    GROUP BY p.id_producto, p.nombre
                    HAVING total_vendido > 0
                    ORDER BY total_vendido DESC
                    LIMIT 1";
                    $result_mas_online = $mysqli->query($sql_mas_online);
                    $mas_vendido_online = $result_mas_online->fetch_assoc();
                    
                    // Producto menos vendido tienda online
                    $sql_menos_online = "SELECT p.id_producto, p.nombre, SUM(dp.cantidad) AS total_vendido
                    FROM productos p
                    JOIN detalles_productos dpd ON dpd.id_producto = p.id_producto
                    JOIN detalles_pedido dp ON dp.id_detalles_productos = dpd.id_detalles_productos
                    GROUP BY p.id_producto, p.nombre
                    HAVING total_vendido > 0
                    ORDER BY total_vendido ASC
                    LIMIT 1";
                    $result_menos_online = $mysqli->query($sql_menos_online);
                    $menos_vendido_online = $result_menos_online->fetch_assoc();
                    ?>
                    
                    <!-- Producto más vendido -->
                    <div class="col-md-6 mb-4">
                      <div class="card shadow h-100 py-2 border-left-success">
                        <div class="card-body d-flex align-items-center">
                          <div class="mr-3">
                            <i class="fas fa-trophy fa-3x text-success"></i>
                          </div>
                          <div>
                            <h5 class="card-title mb-1">Producto más vendido tienda física</h5>
                            <?php if ($mas_vendido): ?>
                              <h3 class="font-weight-bold text-success"><?php echo $mas_vendido['nombre']; ?></h3>
                              <p class="mb-0">Cantidad vendida: <span><?php echo $mas_vendido['total_vendido']; ?></span></p>
                            <?php else: ?>
                              <p class="text-muted">No hay ventas registradas.</p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Producto menos vendido -->
                    <div class="col-md-6 mb-4">
                      <div class="card shadow h-100 py-2 border-left-danger">
                        <div class="card-body d-flex align-items-center">
                          <div class="mr-3">
                            <i class="fas fa-arrow-down fa-3x text-danger"></i>
                          </div>
                          <div>
                            <h5 class="card-title mb-1">Producto menos vendido tienda física</h5>
                            <?php if ($menos_vendido): ?>
                              <h3 class="font-weight-bold text-danger"><?php echo $menos_vendido['nombre']; ?></h3>
                              <p class="mb-0">Cantidad vendida: <span><?php echo $menos_vendido['total_vendido']; ?></span></p>
                            <?php else: ?>
                              <p class="text-muted">No hay ventas registradas.</p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Producto más vendido tienda online -->
                    <div class="col-md-6 mb-4">
                      <div class="card shadow h-100 py-2 border-left-info">
                        <div class="card-body d-flex align-items-center">
                          <div class="mr-3">
                            <i class="fas fa-trophy fa-3x text-info"></i>
                          </div>
                          <div>
                            <h5 class="card-title mb-1">Producto más vendido tienda online</h5>
                            <?php if ($mas_vendido_online): ?>
                              <h3 class="font-weight-bold text-info"><?php echo $mas_vendido_online['nombre']; ?></h3>
                              <p class="mb-0">Cantidad vendida: <span><?php echo $mas_vendido_online['total_vendido']; ?></span></p>
                            <?php else: ?>
                              <p class="text-muted">No hay ventas registradas.</p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Producto menos vendido tienda online -->
                    <div class="col-md-6 mb-4">
                      <div class="card shadow h-100 py-2 border-left-warning">
                        <div class="card-body d-flex align-items-center">
                          <div class="mr-3">
                            <i class="fas fa-arrow-down fa-3x text-warning"></i>
                          </div>
                          <div>
                            <h5 class="card-title mb-1">Producto menos vendido tienda online</h5>
                            <?php if ($menos_vendido_online): ?>
                              <h3 class="font-weight-bold text-warning"><?php echo $menos_vendido_online['nombre']; ?></h3>
                              <p class="mb-0">Cantidad vendida: <span><?php echo $menos_vendido_online['total_vendido']; ?></span></p>
                            <?php else: ?>
                              <p class="text-muted">No hay ventas registradas.</p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                 </div>

                    

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
           
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="/guardiashop/admin_gs/iniciar_sesion/index.php">Logout</a>
                </div>
            </div>
        </div> 
    </div>

       <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
   
     <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    
<script>
document.getElementById('select-mes').addEventListener('change', function() {
    var mes = this.value;
    fetch('ajax_ganancias_mes.php?mes=' + mes)
        .then(response => response.json())
        .then(data => {
            document.getElementById('valor-ganancia-mes').innerText = '$' + Number(data.total).toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 0});
        });
});

document.getElementById('select-anio').addEventListener('change', function() {
    var anio = this.value;
    fetch('ajax_ganancias_anio.php?anio=' + anio)
        .then(response => response.json())
        .then(data => {
            document.getElementById('valor-ganancia-anio').innerText = '$' + Number(data.total).toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 0});
        });
});

const ctx = document.getElementById('graficaGananciasMes').getContext('2d');
let chartGanancias;
let tipoGrafica = 'fisica'; // por defecto

function cargarGraficaGanancias(anio) {
    let url = tipoGrafica === 'fisica'
        ? 'ajax_ganancias_mensuales.php?anio=' + anio
        : 'ajax_ganancias_mensuales_online.php?anio=' + anio;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            const ganancias = meses.map((_, i) => data[i+1] ? parseFloat(data[i+1]) : 0);

            if (chartGanancias) chartGanancias.destroy();

            chartGanancias = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: meses,
                    datasets: [{
                        label: 'Ganancias ($ COP)',
                        data: ganancias,
                        backgroundColor: tipoGrafica === 'fisica' ? 'rgba(46, 148, 79, 0.2)' : 'rgba(78, 148, 79, 0.2)',
                        borderColor: tipoGrafica === 'fisica' ? '#2c4926' : '#4e944f',
                        borderWidth: 3,
                        pointBackgroundColor: tipoGrafica === 'fisica' ? '#4e944f' : '#2c4926',
                        pointBorderColor: '#fff',
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '$' + context.parsed.y.toLocaleString('es-CO', {minimumFractionDigits: 0}) + ' COP';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString('es-CO') + ' COP';
                                }
                            }
                        }
                    }
                }
            });
        });
}

// Alternar botones
document.getElementById('btn-fisica').addEventListener('click', function() {
    tipoGrafica = 'fisica';
    document.getElementById('btn-fisica').classList.add('active');
    document.getElementById('btn-online').classList.remove('active');
    document.getElementById('tipo-titulo').innerText = 'Física';
    cargarGraficaGanancias(document.getElementById('select-ano-grafica').value);
});

document.getElementById('btn-online').addEventListener('click', function() {
    tipoGrafica = 'online';
    document.getElementById('btn-online').classList.add('active');
    document.getElementById('btn-fisica').classList.remove('active');
    document.getElementById('tipo-titulo').innerText = 'Online';
    cargarGraficaGanancias(document.getElementById('select-ano-grafica').value);
});

// Cambiar año
document.getElementById('select-ano-grafica').addEventListener('change', function() {
    cargarGraficaGanancias(this.value);
});

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    cargarGraficaGanancias(document.getElementById('select-ano-grafica').value);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos de ejemplo, usa los que pasaste desde PHP
    var ctxPie = document.getElementById('myPieChart').getContext('2d');
    var myPieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Tienda Física', 'Tienda Online'],
            datasets: [{
                data: [ingresosFisica, ingresosOnline],
                backgroundColor: ['#2c4926', '#4e944f'],
                borderColor: ['#fff', '#fff'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed || 0;
                            return label + ': $' + value.toLocaleString('es-CO');
                        }
                    }
                }
            }
        }
    });
});
</script>
<script>
    // Supón que tienes estas variables en PHP:
    var ingresosFisica = <?php echo $res_anual_fisica; ?>;
    var ingresosOnline = <?php echo $res_anual_online; ?>;
</script>
<script>
document.getElementById('select-mes-online').addEventListener('change', function() {
    var mes = this.value;
    fetch('ajax_ganancias_mes_online.php?mes=' + mes)
        .then(response => response.json())
        .then(data => {
            document.getElementById('valor-ganancia-mes-online').innerText = '$' + Number(data.total).toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 0});
        });
});

document.getElementById('select-anio-online').addEventListener('change', function() {
    var anio = this.value;
    fetch('ajax_ganancias_anio_online.php?anio=' + anio)
        .then(response => response.json())
        .then(data => {
            document.getElementById('valor-ganancia-anio-online').innerText = '$' + Number(data.total).toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 0});
        });
});
</script>



</body>

</html>
