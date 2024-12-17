<?php
include 'connect.php';

$product_id = isset($_GET['id']) ? $_GET['id'] : null;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($product_id) {
    session_start();

    if (!isset($_SESSION['cart_id'])) {
        $stmt = $conn->prepare("INSERT INTO cart (created_date_time, created_by, status, total_cost) VALUES (NOW(), 'admin', 'active', 0)");
        $stmt->execute();
        $_SESSION['cart_id'] = $conn->lastInsertId();
    }

    $cart_id = $_SESSION['cart_id'];

    $stmt = $conn->prepare("SELECT price FROM products WHERE id = :id");
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $price_of_one = $product['price'];
        $total_price = $price_of_one * $quantity;

        $stmt = $conn->prepare("SELECT id, quantity FROM cart_details WHERE cart_id = :cart_id AND product_id = :product_id");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $cart_detail = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart_detail) {
            $new_quantity = $cart_detail['quantity'] + $quantity;
            $new_total_price = $price_of_one * $new_quantity;

            $stmt = $conn->prepare("UPDATE cart_details SET quantity = :quantity, total_price = :total_price WHERE id = :id");
            $stmt->bindParam(':quantity', $new_quantity, PDO::PARAM_INT);
            $stmt->bindParam(':total_price', $new_total_price, PDO::PARAM_STR);
            $stmt->bindParam(':id', $cart_detail['id'], PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("INSERT INTO cart_details (created_date_time, created_by, status, price_of_one, quantity, total_price, cart_id, product_id) VALUES (NOW(), 'admin', 'active', :price_of_one, :quantity, :total_price, :cart_id, :product_id)");
            $stmt->bindParam(':price_of_one', $price_of_one, PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);
            $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
        }

        $stmt = $conn->prepare("SELECT SUM(total_price) AS total_cost FROM cart_details WHERE cart_id = :cart_id");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->execute();
        $total_cost = $stmt->fetch(PDO::FETCH_ASSOC)['total_cost'];

        $stmt = $conn->prepare("UPDATE cart SET total_cost = :total_cost WHERE id = :cart_id");
        $stmt->bindParam(':total_cost', $total_cost, PDO::PARAM_STR);
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: cart.php");
    } else {
        echo "Product not found.";
    }
} else {
    echo "Invalid product ID.";
}
?>
