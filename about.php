<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RedStore | About Us</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <div class="header">
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
    </div>

    <!-- About Section -->
    <div class="about-section">
        <div class="small-container">
            <div class="row">
                <div class="col-2">
                    <h1>About Us</h1>
                    <p>At RedStore, our mission is to provide high-quality, stylish products that enhance your workout and everyday life. Our team is dedicated to ensuring customer satisfaction through exceptional service and a wide range of products to meet all your needs.</p>
                </div>
                <div class="col-2">
                    <img src="images/about-us.jpg" alt="About Us Image">
                </div>
            </div>
        </div>
    </div>

    <!-- Our Team Section -->
    <div class="team-section">
        <div class="small-container">
            <h2 class="title">Meet Our Team</h2>
            <div class="row">
                <div class="col-3">
                    <img src="images/team-1.jpg" alt="Team Member">
                    <h3>Le Duong</h3>
                    <p>CEO & Founder</p>
                </div>

            </div>
        </div>
    </div>

    <!-- Our Story Section -->
    <div class="story-section">
        <div class="small-container">
            <h2 class="title">Our Story</h2>
            <p>RedStore was founded with the vision of bringing high-quality, affordable products to fitness enthusiasts everywhere. Since our inception, we've been committed to innovation and excellence, continuously expanding our product line to meet the evolving needs of our customers.</p>
        </div>
    </div>

    <!-- Our Values Section -->
    <div class="values-section">
        <div class="small-container">
            <h2 class="title">Our Values</h2>
            <div class="row">
                <div class="col-4">
                    <h3>Quality</h3>
                    <p>We prioritize quality in every product we offer, ensuring you receive the best value for your money.</p>
                </div>
                <div class="col-4">
                    <h3>Innovation</h3>
                    <p>We strive to stay ahead of trends and continuously bring innovative products to our customers.</p>
                </div>
                <div class="col-4">
                    <h3>Customer Satisfaction</h3>
                    <p>Your satisfaction is our top priority. We are dedicated to providing excellent customer service and support.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col-1">
                    <h3>Download Our App</h3>
                    <p>Download App for Android and iOS mobile phone</p>
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
                        <li>Youtube</li>
                    </ul>
                </div>
            </div>
            <hr>
            <p class="Copyright">Copyright 2024 - By Leduong</p>
        </div>
    </div>

    <!-- JavaScript for Toggle Menu -->
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
</body>

</html>