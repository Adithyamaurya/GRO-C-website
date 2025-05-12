<?php
include 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the order ID is provided
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
    $user_id = $_SESSION['user_id'];
    // Update the order status to "Canceled"
        $cancel_sql = "UPDATE orders SET status = 'Canceled' WHERE order_id = ?";
        $stmt = $conn->prepare($cancel_sql);
        $stmt->bind_param("i", $order_id);

        if ($stmt->execute()) {
            echo "<script>alert('Order cancelled successfully!'); window.location.href='dashboard.php';</script>";
        } else {
            echo "<script>alert('Error cancelling order: " . $conn->error . "'); window.location.href='dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Order not found or unauthorized access!'); window.location.href='dashboard.php';</script>";
    }

    $stmt->close();


$conn->close();
?>