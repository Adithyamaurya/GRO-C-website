<?php
include 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
echo "<pre>";
print_r($_POST);
echo "</pre>";
echo "User ID: " . $_SESSION['user_id'];

// Fetch cart items for the logged-in user
$user_id = $_SESSION['user_id'];
$cartItems = [];

$sql = "SELECT cart.*, products.name, products.price, products.image, products.seller_id FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $customer_name = $firstName . ' ' . $lastName;
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $pincode = $conn->real_escape_string($_POST['zip_code']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $paymentMethod = $conn->real_escape_string($_POST['payment']);

    // Calculate total amount
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $shippingCost = 5.99;
    $codFee = ($paymentMethod == 'cod') ? 9.00 : 0;
    $total = $subtotal + $shippingCost + $codFee;

    // Insert order into the database
    $sql = "INSERT INTO orders (seller_id, customer_name, total_amount, status,email) VALUES (?, ?, ?, 'Pending',?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isds", $cartItems[0]['seller_id'], $customer_name, $total,$email);
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;

        // Insert order items into the database
        foreach ($cartItems as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $order_id, $product_id, $quantity);
            $stmt->execute();
        }

        // Clear the cart
        $sql = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Redirect to success page
        echo "<script>alert('Order placed successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>