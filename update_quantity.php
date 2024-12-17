<?php
include 'connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['cart_id'])) {
    $cart_id = $_SESSION['cart_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    try {
        $stmt = $conn->prepare("SELECT price FROM products WHERE id = :product_id");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $price_of_one = $product['price'];
            $total_price = $quantity * $price_of_one;

            $sql = "UPDATE cart_details SET quantity = :quantity, total_price = :total_price
                    WHERE cart_id = :cart_id AND product_id = :product_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);
            $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $conn->prepare("UPDATE cart SET total_cost = (SELECT SUM(total_price) FROM cart_details WHERE cart_id = :cart_id) WHERE id = :cart_id");
            $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $conn->prepare("SELECT total_cost FROM cart WHERE id = :cart_id");
            $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
            $stmt->execute();
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);
            $total_cost = $cart['total_cost'];

            echo json_encode(['success' => true, 'total_price' => $total_price, 'total_cost' => $total_cost]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Product not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
?>
