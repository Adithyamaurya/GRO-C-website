<?php
session_start();
include 'db.php';

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    if ($user_id) {
        // Remove item from the database cart
        $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    } else {
        // Remove item from the session cart
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }
}

header('Location: cart.php');
exit;
?>