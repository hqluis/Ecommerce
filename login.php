<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (($password === $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            $userId = $user['id'];
            $cartSql = "SELECT * FROM cart WHERE user_id = :user_id";
            $cartStmt = $conn->prepare($cartSql);
            $cartStmt->bindParam(':user_id', $userId);
            $cartStmt->execute();
            $cart = $cartStmt->fetch(PDO::FETCH_ASSOC);

            if ($cart) {
                $_SESSION['cart_id'] = $cart['id'];
            } else {
                $insertCartSql = "INSERT INTO cart (created_date_time, created_by, status, total_cost, user_id) 
                                  VALUES (NOW(), :created_by, 'active', 0.00, :user_id)";
                $insertCartStmt = $conn->prepare($insertCartSql);
                $insertCartStmt->bindParam(':created_by', $userId);
                $insertCartStmt->bindParam(':user_id', $userId);
                $insertCartStmt->execute();
                
                $newCartId = $conn->lastInsertId();
                $_SESSION['cart_id'] = $newCartId;
            }

            echo "Login successful!";
            header("Location: index.php");
        } else {
            echo "Invalid email or password.";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
