<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title  -->
    <title>GUARDIASHOP</title>

    <!-- Favicon  -->
    <link rel="icon" href="./img/core-img/logoguardiashop.ico">

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="css/core-styleff.css">
    <link rel="stylesheet" href="style.css">
    
</head>

<body>
     <!-- ##### Header Area Start ##### -->
     <?php include './arc/nav.php'; ?>
    <!-- ##### Header Area End ##### -->
            

    <!-- ##### Breadcumb Area Start ##### -->
    <div class="breadcumb_area bg-img" style="background-image: url(img/bg-img/breadcumb.jpg);">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="page-title text-center">
                        <h2 style="color:#444242 ;">Verificar</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Breadcumb Area End ##### -->

    <!-- ##### Checkout Area Start ##### -->
    <div class="checkout_area section-padding-80" style="background-color: #EFD9AB;">
        <div class="container">
            <div class="row">

                <div class="col-12 col-md-6">
                    <div class="checkout_details_area mt-50 clearfix">

                        <div class="cart-page-heading mb-30">
                            <h5 style="color:#444242 ;">Dirección de Envio</h5>
                        </div>

                        <form action="#" method="post">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" style="color:#444242 ;" >Nombres <span style="color: #b78732;">*</span></label>
                                    <input type="text" class="form-control" id="first_name" value="" required style="border: 1px solid #444242; color: #444242; outline: none;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" style="color:#444242 ;" >Apellidos <span style="color: #b78732;">*</span></label>
                                    <input type="text" class="form-control" id="last_name" value="" required style="border: 1px solid #444242; color: #444242; outline: none;">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="street_address" style="color:#444242 ;" >Direccion #1 <span style="color: #b78732;">*</span></label>
                                    <input type="text" class="form-control mb-3" id="street_address" value="" required style="border: 1px solid #444242; color: #444242; outline: none;">
                                    <label for="street_address" style="color:#444242 ;" >Direccion #2 <span style="color: #b78732;">*</span></label>
                                    <input type="text" class="form-control" id="street_address2" value=""  style="border: 1px solid #444242; color: #444242; outline: none;">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="postcode" style="color:#444242 ;" >Codigo postal <span style="color: #b78732;">*</span></label>
                                    <input type="text" class="form-control" id="postcode" value="" require style="border: 1px solid #444242; color: #444242; outline: none;">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="city" style="color:#444242 ;" >Ciudad <span style="color: #b78732;">*</span></label>
                                    <input type="text" class="form-control" id="city" value="" required style="border: 1px solid #444242; color: #444242; outline: none;">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="phone_number" style="color:#444242 ;">Número de teléfono <span style="color: #b78732;">*</span></label>
                                    <input type="text" class="form-control" id="phone_number"  value="" required style="border: 1px solid #444242; color: #444242; outline: none;">
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="email_address" style="color:#444242;">Correo Electrónico <span style="color: #b78732;">*</span></label>
                                    <input type="email" class="form-control" id="email_address" value="" required  style="border: 1px solid #444242; color: #444242; outline: none;">

                                </div>


                                <div class="col-12">
                                    <div class="custom-control custom-checkbox d-block mb-2">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1" style="color: #444242;">Terminos y condiciones</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-5 ml-lg-auto">
                    <div class="order-details-confirmation" style="border: 1px solid #444242; color: #444242; outline: none;">

                        <div class="cart-page-heading">
                            <h5 style="color: #444242;">Tu Pedido</h5>
                            <p style="color: #444242;">Detalles</p>
                        </div>

                        <ul class="order-details-form mb-" style="padding: 0; list-style: none;">
                            <!-- Línea 1 -->
                            <li style="border-bottom: 2px solid #444242; color: #444242; padding: 10px 0;">
                                <span style="color: #444242;">PRODUCTOS</span>
                                <span style="float: right; color: #444242;">TOTAL</span>
                            </li>

                            <!-- Línea 2 -->
                            <li style="border-bottom: 2px solid #444242; color: #444242; padding: 10px 0;">
                                <span style="color: #444242;">Top Cruzado Llama Tropical</span>
                                <span style="float: right; color: #444242;">$375.000</span>
                            </li>

                            <!-- Línea 3 -->
                            <li style="border-bottom: 2px solid #444242; color: #444242; padding: 10px 0;">
                                <span style="color: #444242;">Subtotal</span>
                                <span style="float: right; color: #444242;">$375.000</span>
                            </li>

                            <!-- Línea 4 -->
                            <li style="border-bottom: 2px solid #444242; color: #444242; padding: 10px 0;">
                                <span style="color: #444242;">Envio</span>
                                <span style="float: right; color: #444242;">Free</span>
                            </li>

                            <!-- Línea 5 (sin borde) -->
                            <li style="border-bottom: 2px solid #444242; color: #444242; padding: 10px 0;">
                                <span style="color: #444242;">Total</span>
                                <span style="float: right; color: #444242;">$375.000</span>
                            </li>
                        </ul>


                        <div id="accordion" role="tablist" class="mb-4">
                        <div class="card">
                            <div class="card-header" role="tab" id="headingOne" style="background-color: #EFD9AB;">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <input type="radio" name="metodoPago" class="mr-2" id="radioPaypal">
                                    <label for="radioPaypal" class="mb-0" style="color: #444242;">
                                        <a data-toggle="collapse" href="#collapseOne"  style="color:#444242" aria-expanded="false" aria-controls="collapseOne">
                                             Paypal
                                        </a>
                                    </label>
                                </h6>
                            </div>
                            <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" role="tab" id="headingTwo" style="background-color: #EFD9AB;">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <input type="radio" name="metodoPago" class="mr-2" id="radioCOD">
                                    <label for="radioCOD" class="mb-0" style="color: #444242;">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseTwo"  style="color:#444242" aria-expanded="false" aria-controls="collapseTwo">
                                            Contra entrega
                                        </a>
                                    </label>
                                </h6>
                            </div>
                            <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" role="tab" id="headingThree" style="background-color: #EFD9AB;">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <input type="radio" name="metodoPago" class="mr-2" id="radioTarjeta">
                                    <label for="radioTarjeta" class="mb-0" style="color: #444242;">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseThree" style="color:#444242" style="color:#444242" aria-expanded="false" aria-controls="collapseThree">
                                            Tarjeta de credito
                                        </a>
                                    </label>
                                </h6>
                            </div>
                            <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" role="tab" id="headingFour" style="background-color: #EFD9AB;">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <input type="radio" name="metodoPago" class="mr-2" id="radioTransfer">
                                    <label for="radioTransfer" class="mb-0" style="color: #444242;">
                                        <a class="collapsed" data-toggle="collapse" href="#collapseFour" style="color:#444242" aria-expanded="true" aria-controls="collapseFour">
                                            Transferencia bancaria
                                        </a>
                                    </label>
                                </h6>
                            </div>
                            <div id="collapseFour" class="collapse show" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
                            </div>
                        </div>
                    </div>


                        <a href="#" class="btn essence-btn" style="color: #EFD9AB;">Comprar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Checkout Area End ##### -->

    <!-- ##### Footer Area Start ##### -->
    <?php include './arc/footer.php';?>
    <!-- ##### Footer Area End ##### -->

    <!-- jQuery (Necessary for All JavaScript Plugins) -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Plugins js -->
    <script src="js/plugins.js"></script>
    <!-- Classy Nav js -->
    <script src="js/classy-nav.min.js"></script>
    <!-- Active js -->
    <script src="js/active.js"></script>

</body>

</html>