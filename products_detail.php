<?php
include 'connect.php';
$query = 'SELECT id, product_name, price, sale_price, thumbnail FROM products WHERE status = 1';
$stmt = $conn->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$product_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($product_id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Product not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printd T-Shirt - RedStore</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <a href="index.php"><img src="images/logo.png" width="125px"></a>
            </div>
            <nav>
                <ul id="MenuItems">
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="products.php">Sản phẩm</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Liên hệ</a></li>
                    <li><a href="account.php">Tài khoản</a></li>
                </ul>
            </nav>
            <a href="cart.html"><img src="images/cart.png" width="30px" height="30px"></a>
            <img src="images/menu.png" class="menu-icon"
                onclick="menutoggle()">
        </div>
    </div>
    </div>

    <!-- ---------- single Products detail ----------- -->

    <div class="small-container single-product">
        <div class="row">
            <div class="col-2">
                <img src="<?php echo $product['thumbnail']; ?>" width="100%" id="productImg">
            </div>
            <div class="col-2">
                <p>Trang chủ / <?php echo $product['product_name']; ?></p>
                <h1><?php echo $product['product_name']; ?></h1>
                <h4><?php echo number_format($product['price'], 0, ',', '.'); ?> VND</h4>
                <input type="number" value="1">
                <a href="add_to_cart.php?id=<?php echo $product['id']; ?>" class="btn">Thêm vào giỏ hàng</a>
                <h3>Thông tin sản phẩm <i class="fa fa-indent"></i></h3>
                <br>
                <p><?php echo $product['description']; ?></p>
            </div>
        </div>
    </div>

    <!-- ----- title------------- -->
    <div class="small-container">
        <div class="row row2">
            <h2>Sản phẩm liên quan</h2>
            <p>View More</p>
        </div>
    </div>

    <!-- ---------------Products----------------- -->
    <div class="small-container">
        <div class="row row-2">
            <h2>All Products</h2>
            <select>
                <option>Default Shop</option>
                <option>Sort by price</option>
                <option>Sort by popularity</option>
                <option>Sort by Rating</option>
                <option>Sort by Sale</option>
            </select>
        </div>

        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-4">
                    <a href="products_detail.php?id=<?= $product['id'] ?>"><img src="<?= $product['thumbnail'] ?>" alt="Image"></a>
                    <h4><?= htmlspecialchars($product['product_name']) ?></h4>
                    <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star-o"></i>
                    </div>
                    <p>$<?= number_format($product['sale_price'], 2) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="page-btn">
            <span>1</span>
            <span>2</span>
            <span>3</span>
            <span>4</span>
            <span>&#8594;</span>
        </div>
    </div>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col-1">
                    <h3>Download Our App</h3>
                    <p>Download App for Android and ios mobile phone</p>
                    <div class="app-logo">
                        <img src="images/play-store.png">
                        <img src="images/app-store.png">
                    </div>
                </div>
                <div class="footer-col-2">
                    <img src="images/logo-white.png">
                    <p>Our Purpose Is To Sustainably Make the Pleasure and
                        Benefits of Sports Accessible to the Many</p>
                </div>
                <div class="footer-col-3">
                    <h3>Useful Links</h3>
                    <ul>
                        <li>Coupons</li>
                        <li>Blog Post</li>
                        <li>Return Policy</li>
                        <li>Join Affiliate</li>
                    </ul>
                </div>
                <div class="footer-col-4">
                    <h3>Follow us</h3>
                    <ul>
                        <li>Facebook</li>
                        <li>Twitter</li>
                        <li>Instagram</li>
                        <li>Youtube </li>
                    </ul>
                </div>
            </div>
            <hr>
            <p class="Copyright">Copyright 2020 - By QuocHuy</p>
        </div>
    </div>
    <!-- ------------------- js for toggle menu-------------- -->
    <script>
        var MenuItems = document.getElementById("MenuItems");

        MenuItems.style.maxHeight = "0px";

        function menutoggle() {
            if (MenuItems.style.maxHeight == "0px") {
                MenuItems.style.maxHeight = "200px";
            } else {
                MenuItems.style.maxHeight = "0px";
            }
        }
    </script>

    <!-- ------------------- JS for  product gallery------------------------         -->
    <script>
        var ProductImg = document.getElementById("productImg");
        var SmallImg = document.getElementsByClassName("small-img");

        SmallImg[0].onclick = function() {
            ProductImg.src = SmallImg[0].src;
        }
        SmallImg[1].onclick = function() {
            ProductImg.src = SmallImg[1].src;
        }
        SmallImg[2].onclick = function() {
            ProductImg.src = SmallImg[2].src;
        }
        SmallImg[3].onclick = function() {
            ProductImg.src = SmallImg[3].src;
        }
    </script>
</body>

</html>