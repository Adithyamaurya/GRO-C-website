<?php
session_start();

// Check if product_id is set
if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    // Get quantity (default to 1 if not specified)
    $quantity = isset($_POST['quantity']) ? floatval($_POST['quantity']) : 1;
    
    include 'db.php';
    
    // Fetch product details from database
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        if (isset($_SESSION['cart'][$product_id])) {
            // Update quantity if product exists
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Add product to cart if it doesn't exist
            $_SESSION['cart'][$product_id] = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            );
        }

        // If user is logged in, update the database
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $user_id, $product_id, $quantity);
            $stmt->execute();
        }
        
        // Redirect back to referring page or to cart
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: cart.php');
        }
    } else {
        echo "Product not found";
    }
    
    $conn->close();
} else {
    echo "Invalid request";
}
?>