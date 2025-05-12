<?php
include 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and is a seller
if (!isset($_SESSION['seller_id'])) {
    echo "<script>alert('Unauthorized access!'); window.location.href='seller_login.php';</script>";
    exit();
}

// Check if the product ID is provided
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    $seller_id = $_SESSION['seller_id'];

    // Verify that the product belongs to the logged-in seller
    $verify_sql = "SELECT * FROM products WHERE id = ? AND seller_id = ?";
    $stmt = $conn->prepare($verify_sql);
    $stmt->bind_param("ii", $product_id, $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Delete the product
        $delete_sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $product_id);

        if ($stmt->execute()) {
            echo "<script>alert('Product deleted successfully!'); window.location.href='seller_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error deleting product: " . $conn->error . "'); window.location.href='seller_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Product not found or unauthorized access!'); window.location.href='seller_dashboard.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request!'); window.location.href='seller_dashboard.php';</script>";
}

$conn->close();
?>