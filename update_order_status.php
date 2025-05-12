<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = intval($_POST['order_id']);
    $new_status = $conn->real_escape_string($_POST['status']);
    $seller_id = $_SESSION['seller_id'];

    $sql = "UPDATE orders SET status='$new_status' WHERE order_id='$order_id' AND seller_id='$seller_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Order #$order_id has been marked as $new_status'); window.location.href='seller_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating order status: " . $conn->error . "'); window.location.href='seller_dashboard.php';</script>";
    }
}
?>