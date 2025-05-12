<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    if ($user_id) {
        if ($action == 'increase') {
            $sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
        } elseif ($action == 'decrease') {
            $sql = "UPDATE cart SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ?";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();

        // Remove item if quantity is zero
        $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ? AND quantity <= 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    } else {
        if (isset($_SESSION['cart'][$product_id])) {
            if ($action == 'increase') {
                $_SESSION['cart'][$product_id]['quantity']++;
            } elseif ($action == 'decrease') {
                $_SESSION['cart'][$product_id]['quantity']--;
                if ($_SESSION['cart'][$product_id]['quantity'] <= 0) {
                    unset($_SESSION['cart'][$product_id]);
                }
            }
        }
    }
}

header('Location: cart.php');
exit();
?>