<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $retype_password = $_POST['retype_password'];
    $phone = $_POST['phone'];
    $created_date_time = date("Y-m-d H:i:s");

    if ($password !== $retype_password) {
        echo "Passwords do not match!";
    } else {
        try {
            $sql = "INSERT INTO users (email, password, phone_number, created_date_time) 
                    VALUES (:email, :password, :phone, :created_date_time)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':created_date_time', $created_date_time);
            $stmt->execute();
            
            $user_id = $conn->lastInsertId();

            $insertCartSql = "INSERT INTO cart (created_date_time, created_by, status, total_cost, user_id) 
                              VALUES (:created_date_time, :created_by, 'active', 0.00, :user_id)";
            $insertCartStmt = $conn->prepare($insertCartSql);
            $insertCartStmt->bindParam(':created_date_time', $created_date_time);
            $insertCartStmt->bindParam(':created_by', $user_id);
            $insertCartStmt->bindParam(':user_id', $user_id);
            $insertCartStmt->execute();
            
            header("Location: account.php");

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
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
            <a href="cart.php"><img src="images/cart.png" width="30px" height="30px"></a>
            <img src="images/menu.png" class="menu-icon"
                onclick="menutoggle()">
        </div>
    </div>
    <!-- ------------Account-page------------------- -->
    <div class="account-page">
        <div class="container">
            <div class="row">
                <div class="col-2">
                    <img src="images/image1.png" width="100%">
                </div>

                <div class="col-2">
                    <div class="form-container">
                        <div class="form-btn">
                            <span onclick="login()">Đăng nhập</span>
                            <span onclick="register()">Đăng ký</span>
                            <hr id="Indicator">
                        </div>

                        <form id="LoginForm" action="login.php" method="POST">
                            <input type="email" placeholder="Email" name="email" required>
                            <input type="password" placeholder="Password" name="password" required>
                            <button type="submit" class="btn">Đăng nhập</button>
                            <a href="#">Quên mật khẩu</a>
                        </form>

                        <form id="RegForm" action="" method="POST">
                            <input type="email" placeholder="Email" name="email" required>
                            <input type="password" placeholder="Password" name="password" required>
                            <input type="password" placeholder="Retype Password" name="retype_password" required>
                            <input type="text" placeholder="Phone" name="phone" required>
                            <button type="submit" class="btn">Đăng ký</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ------------footer----------- -->

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
        <!-- ------------------- js for Account form-------------- -->

        <script>
            var LoginForm = document.getElementById("LoginForm");
            var RegForm = document.getElementById("RegForm");
            var Indicator = document.getElementById("Indicator");

            function register() {
                RegForm.style.transform = "translateX(0px)";
                LoginForm.style.transform = "translateX(0px)";
                Indicator.style.transform = "translateX(100px)";

            }

            function login() {
                RegForm.style.transform = "translateX(300px)";
                LoginForm.style.transform = "translateX(300px)";
                Indicator.style.transform = "translateX(0px)";
            }
        </script>

</body>

</html>