<?php
include 'connect.php';
session_start();

$cart_id = isset($_SESSION['cart_id']) ? $_SESSION['cart_id'] : null;

if ($cart_id) {
    $stmt = $conn->prepare("SELECT * FROM cart_details WHERE cart_id = :cart_id");
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->execute();
    $cart_details = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT total_cost FROM cart WHERE id = :cart_id");
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->execute();
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_cost = $cart['total_cost'];
} else {
    $cart_details = [];
    $total_cost = 0;
}

$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';

if ($cart_id && $phone && $address) {
    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("SELECT total_cost FROM cart WHERE id = :cart_id");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->execute();
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_cost = $cart['total_cost'];

        $stmt = $conn->prepare("INSERT INTO `orders` (created_date_time, created_by, status, modified_date_time, updated_by, order_status, order_time, total_cost, transaction_status, address_id, customer_id, address, phone) 
                                 VALUES (NOW(), 'admin', 'Pending', NOW(), 'admin', 'Pending', NOW(), :total_cost, 'Pending', NULL, NULL, :address, :phone)");
        $stmt->bindParam(':total_cost', $total_cost, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->execute();
        $order_id = $conn->lastInsertId();

        $stmt = $conn->prepare("SELECT * FROM cart_details WHERE cart_id = :cart_id");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->execute();
        $cart_details = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($cart_details as $item) {
            $stmt = $conn->prepare("INSERT INTO order_details (created_date_time, created_by, status, modified_date_time, updated_by, price_of_one, quantity, total_price, order_id, product_id) 
                                     VALUES (NOW(), 'admin', 'Pending', NOW(), 'admin', :price_of_one, :quantity, :total_price, :order_id, :product_id)");
            $stmt->bindParam(':price_of_one', $item['price_of_one'], PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':total_price', $item['total_price'], PDO::PARAM_STR);
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
            $stmt->execute();
        }

        $stmt = $conn->prepare("UPDATE cart SET total_cost = 0 WHERE id = :cart_id");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM cart_details WHERE cart_id = :cart_id");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->execute();

        $conn->commit();

        echo '<script>alert("Mua hàng thành công!"); window.location.href = "index.php";</script>';
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Failed: " . $e->getMessage();
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
            <img src="images/menu.png" class="menu-icon" onclick="menutoggle()">
        </div>
    </div>

    <div class="small-container cart-page">
        <table>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Thanh toán</th>
            </tr>
            <?php foreach ($cart_details as $item): ?>
                <?php
                $stmt = $conn->prepare("SELECT * FROM products WHERE id = :product_id");
                $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <tr>
                    <td>
                        <div class="cart-info">
                            <img src="<?php echo htmlspecialchars($product['thumbnail']); ?>" alt="Product Image">
                            <div>
                                <p><?php echo htmlspecialchars($product['product_name']); ?></p>
                                <small>Price: <?php echo ($item['price_of_one']); ?></small>
                                <br>
                                <a href="delete_product_cart.php?id=<?php echo htmlspecialchars($item['product_id']); ?>">Remove</a>
                            </div>
                        </div>
                    </td>
                    <td>
                        <input type="number" class="quantity-input" data-product-id="<?php echo htmlspecialchars($item['product_id']); ?>" value="<?php echo htmlspecialchars($item['quantity']); ?>">
                    </td>
                    <td class="total-price"><?php echo ($item['total_price']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="total-price-summary">
            <table>
                <tr>
                    <td>Hóa đơn</td>
                    <td id="invoice-total"><?php echo ($total_cost); ?></td>
                </tr>
                <tr>
                    <td>Tổng cộng</td>
                    <td id="total-summary"><?php echo ($total_cost); ?></td>
                </tr>
            </table>
        </div>

        <div class="checkout-form">
            <h2>Checkout</h2>
            <form id="checkout-form" method="POST">
                <label for="phone">Số điện thoại:</label>
                <input type="text" id="phone" name="phone" required>
                <br>
                <label for="address">Địa chỉ:</label>
                <input type="text" id="address" name="address" required>
                <br>
                <input type="submit" value="Checkout">
            </form>
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
                    <p>Our Purpose Is To Sustainably Make the Pleasure and Benefits of Sports Accessible to the Many</p>
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
            <p class="Copyright">Copyright 2020 - By QuocHuy</p>
        </div>
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

            $(document).ready(function() {
                $('.quantity-input').on('change', function() {
                    var quantity = $(this).val();
                    var product_id = $(this).data('product-id');
                    var row = $(this).closest('tr');
                    var price_of_one = row.find('small').text().replace('Price: ', '');

                    $.ajax({
                        url: 'update_quantity.php',
                        type: 'POST',
                        data: {
                            product_id: product_id,
                            quantity: quantity,
                            price_of_one: price_of_one
                        },
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data.success) {
                                row.find('.total-price').text(data.total_price);
                                $('#invoice-total').text(data.total_cost);
                                $('#total-summary').text(data.total_cost);
                            } else {
                                alert('Failed to update quantity: ' + data.error);
                            }
                        }
                    });
                });
            });
        </script>
</body>

</html>