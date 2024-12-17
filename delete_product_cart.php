<?php
include 'connect.php';
session_start();

if (isset($_GET['id']) && isset($_SESSION['cart_id'])) {
    $product_id = $_GET['id'];
    $cart_id = $_SESSION['cart_id'];

    try {
        $sql = "DELETE FROM cart_details WHERE cart_id = :cart_id AND product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "UPDATE cart SET total_cost = (SELECT SUM(total_price) FROM cart_details WHERE cart_id = :cart_id) WHERE id = :cart_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: cart.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
