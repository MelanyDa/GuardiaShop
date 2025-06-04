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
    <!-- ##### Right Side Cart End ##### -->

    <!-- ##### Single Product Details Area Start ##### -->
    <section class="single_product_details_area d-flex align-items-center" style="background-color: #EFD9AB;">

        <!-- Single Product Thumb -->
        <div class="single_product_thumb clearfix">
                <img src="img/productos/gorras(m)/g5.jpeg" alt="">
        </div>

        <!-- Single Product Description -->
        <div class="single_product_desc clearfix" >
            <span style="color:#444242">mango</span>
            <a href="cart.html">
                <h2 style="color:#444242">American Soul</h2>
            </a>
            <p class="product-price" style="color: #2c4926;">$265.000</p>
            <p class="product-desc" style="color:#444242">Descripcion</p>
            <p class="product-desc"></p>

            <!-- Form -->
            <form class="cart-form clearfix" method="post">
                <!-- Select Box -->
                <div class="select-box d-flex mt-50 mb-30">
                    <select name="select" id="productSize" class="mr-5">
                        <option value="value" style="color:#444242">Size: XL</option>
                        <option value="value"style="color:#444242">Size: X</option>
                        <option value="value"style="color:#444242">Size: M</option>
                        <option value="value"style="color:#444242">Size: S</option>
                    </select>
                    <select name="select" id="productColor">
                        <option value="value"style="color:#444242">Color: negro</option>
                        <option value="value"style="color:#444242">Color: blanco</option>
                        <option value="value"style="color:#444242">Color: rojo</option>
                        <option value="valuestyle="color:#444242"">Color: morado</option>
                    </select>
                </div>
                <!-- Cart & Favourite Box -->
                <div class="cart-fav-box d-flex align-items-center">
                    <!-- Cart -->
                    <button type="submit" name="addtocart" value="5" class="btn essence-btn">añadir a carrito</button>
                    <!-- Favourite -->
                    <div class="product-favourite ml-4">
                        <a href="#" class="favme fa fa-heart" onclick="this.style.color='#2c4926'; return false;"></a>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- ##### Single Product Details Area End ##### -->

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