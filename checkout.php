<?php 

include 'db.php';
include 'header.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
// Fetch user data for the logged-in user
$user_id = $_SESSION['user_id'];
$userData = [];

$sql = "SELECT email, address, zip_code, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $userData = $result->fetch_assoc();
}

// Fetch cart items for the logged-in user
$cartItems = [];
$sql = "SELECT cart.*, products.name, products.price, products.image FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?";
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
 $sql = "INSERT INTO orders (customer_id, total_amount, status) VALUES (?, ?, 'Pending')";
 $stmt = $conn->prepare($sql);
 $stmt->bind_param("id", $user_id, $total);
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
     echo "<script>alert('Order placed successfully!'); window.location.href='index.php';</script>";
 } else {
     echo "Error: " . $sql . "<br>" . $conn->error;
 }
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GRO-C - Checkout</title>
  <link rel="stylesheet" href="css/main.css">
  <script defer src="main.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    @keyframes fadeIn {
     from {
       opacity: 0;
     }
     to {
       opacity: 1;
     }
    }
    .checkout-container {
      animation: fadeIn 0.5s ease-in-out;
      display: grid;
      grid-template-columns: 1fr 400px;
      gap: 32px;
      padding: 40px 0;
    }

    @media (max-width: 968px) {
      .checkout-container {
        grid-template-columns: 1fr;
      }
    }

    .checkout-form {
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 24px;
    }

    .form-section {
      margin-bottom: 32px;
    }

    .form-section h4 {
      font-size: 1.25rem;
      font-weight: bold;
      margin-bottom: 16px;
      padding-bottom: 8px;
      border-bottom: 2px solid #e2e8f0;
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .form-full {
      grid-column: 1 / -1;
    }

    .form-group {
      margin-bottom: 16px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      font-size: 1rem;
    }

    .payment-methods {
      display: grid;
      gap: 16px;
    }

    .payment-method {
      position: relative;
      padding: 16px;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .payment-method:hover {
      border-color: #16a34a;
    }

    .payment-method input[type="radio"] {
      position: absolute;
      opacity: 0;
    }

    .payment-method label {
      display: flex;
      align-items: center;
      gap: 12px;
      cursor: pointer;
    }

    .payment-method .radio-circle {
      width: 20px;
      height: 20px;
      border: 2px solid #e2e8f0;
      border-radius: 50%;
      position: relative;
    }

    .payment-method input[type="radio"]:checked + label .radio-circle {
      border-color: #16a34a;
    }

    .payment-method input[type="radio"]:checked + label .radio-circle::after {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 10px;
      height: 10px;
      background-color: #16a34a;
      border-radius: 50%;
    }

    .payment-method i {
      font-size: 24px;
    }

    .payment-details {
      margin-top: 16px;
      padding-top: 16px;
      border-top: 1px solid #e2e8f0;
      display: none;
    }

    .payment-method input[type="radio"]:checked ~ .payment-details {
      display: block;
    }

    .order-summary {
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 24px;
      align-self: start;
      position: sticky;
      top: 24px;
    }

    .order-items {
      margin: 16px 0;
      padding: 16px 0;
      border-top: 1px solid #e2e8f0;
      border-bottom: 1px solid #e2e8f0;
    }

    .order-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 12px;
    }

    .order-item-details {
      display: flex;
      gap: 12px;
    }

    .order-item img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
    }

    .order-total {
      margin-top: 16px;
      padding-top: 16px;
      border-top: 1px solid #e2e8f0;
    }

    .total-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
    }

    .total-row.final {
      font-size: 1.25rem;
      font-weight: bold;
      color: #16a34a;
    }

    .place-order-btn {
      width: 100%;
      padding: 16px;
      margin-top: 24px;
      background-color: #16a34a;
      color: #ffffff;
      border: none;
      border-radius: 6px;
      font-size: 1.125rem;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .place-order-btn:hover {
      background-color: #15803d;
    }

    .place-order-btn:disabled {
      background-color: #94a3b8;
      cursor: not-allowed;
    }
  </style>
</head>
<body style="background-color:rgb(199, 223, 206)  ;">
  <div class="container">
    <section class="checkout-container">
      <div class="checkout-form">
      <form id="checkoutForm" method="POST" action="place_order.php">
          <!-- Shipping Information -->
          <div class="form-section">
          <h4>Shipping Information</h4>
            <div class="form-grid">
              <div class="form-group">
                <input type="text" id="firstName" name="firstName" value="<?php echo isset($userData['first_name']) ? htmlspecialchars($userData['first_name']) : ''; ?>" placeholder="Enter your first name" required>
              </div>
              <div class="form-group">
                <input type="text" id="lastName" name="lastName" value="<?php echo isset($userData['last_name']) ? htmlspecialchars($userData['last_name']) : ''; ?>" placeholder="Enter your last name" required>
              </div>
              <div class="form-group form-full">
                <input type="email" id="email" name="email" value="<?php echo isset($userData['email']) ? htmlspecialchars($userData['email']) : ''; ?>" placeholder="Enter your email" required>
              </div>
              <div class="form-group form-full">
                <input type="text" id="address" name="address" value="<?php echo isset($userData['address']) ? htmlspecialchars($userData['address']) : ''; ?>" placeholder="Enter your address" required>
              </div>
              <div class="form-group">
                <input type="text" id="pincode" name="zip_code" value="<?php echo isset($userData['zip_code']) ? htmlspecialchars($userData['zip_code']) : ''; ?>" required pattern="[0-9]{6}" placeholder="Enter your zipCode">
              </div>
              <div class="form-group">
                <input type="tel" id="phone" name="phone" value="<?php echo isset($userData['phone']) ? htmlspecialchars($userData['phone']) : ''; ?>" required pattern="[0-9]{10}" placeholder="Enter your phone number">
              </div>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="form-section">
            <h4>Payment Method</h4>
            <div class="payment-methods">
              <!-- UPI -->
              <div class="payment-method">
                <input type="radio" name="payment" id="upi" value="upi" required>
                <label for="upi">
                  <span class="radio-circle"></span>
                  <i class="fas fa-mobile-alt"></i>
                  UPI Payment
                </label>
                <div class="payment-details">
                  <div class="form-group">
                    <label for="upiId">UPI ID</label>
                    <input type="text" id="upiId" placeholder="username@upi">
                  </div>
                </div>
              </div>

              <!-- Credit/Debit Card -->
              <div class="payment-method">
                <input type="radio" name="payment" id="card" value="card">
                <label for="card">
                  <span class="radio-circle"></span>
                  <i class="fas fa-credit-card"></i>
                  Credit/Debit Card
                </label>
                <div class="payment-details">
                  <div class="form-group">
                    <label for="cardNumber">Card Number</label>
                    <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456">
                  </div>
                  <div class="form-grid">
                    <div class="form-group">
                      <label for="expiry">Expiry Date</label>
                      <input type="text" id="expiry" placeholder="MM/YY">
                    </div>
                    <div class="form-group">
                      <label for="cvv">CVV</label>
                      <input type="password" id="cvv" placeholder="123">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Cash on Delivery -->
              <div class="payment-method">
                <input type="radio" name="payment" id="cod" value="cod">
                <label for="cod">
                  <span class="radio-circle"></span>
                  <i class="fas fa-money-bill-wave"></i>
                  Cash on Delivery
                </label>
                <div class="payment-details">
                  <p>Pay with cash upon delivery. Additional fee of ₹9 applies.</p>
                </div>
              </div>
            </div>
          </div>

          <button type="submit" class="place-order-btn" onclick="window.location.href='place_order.php'">Place Order</button>
        </form>
      </div>

      <!-- Order Summary -->
      <div class="order-summary">
        <h4>Order Summary</h4>
        <div class="order-items">
          <?php
          $subtotal = 0;
          foreach ($cartItems as $item) {
              $itemTotal = $item['price'] * $item['quantity'];
              $subtotal += $itemTotal;
              echo '<div class="order-item">';
              echo '<div class="order-item-details">';
              echo '<img src="' . $item['image'] . '" alt="' . $item['name'] . '">';
              echo '<div>';
              echo '<p>' . $item['name'] . '</p>';
              echo '<small>Quantity: ' . $item['quantity'] . '</small>';
              echo '</div>';
              echo '</div>';
              echo '<span>₹' . number_format($itemTotal, 2) . '</span>';
              echo '</div>';
          }
          ?>
        </div>
        <div class="order-total">
          <div class="total-row">
            <span>Subtotal</span>
            <span>₹<?php echo number_format($subtotal, 2); ?></span>
          </div>
          <div class="total-row">
            <span>Shipping</span>
            <span>₹5.99</span>
          </div>
          <div class="total-row" id="codFeeRow" style="display: none;">
            <span>COD Fee</span>
            <span>₹9.00</span>
          </div>
          <div class="total-row final">
            <span>Total</span>
            <span id="total">₹<?php echo number_format($subtotal + 5.99, 2); ?></span>
          </div>
        </div>
      </div>
    </section>
  </div>

   <script>
    // Update total when payment method changes
document.querySelectorAll('input[name="payment"]').forEach(input => {
  input.addEventListener('change', (e) => {
    const codFeeRow = document.getElementById('codFeeRow');
    codFeeRow.style.display = e.target.value === 'cod' ? 'flex' : 'none';
    updateTotal();
  });
});

function updateTotal() {
  // Get the subtotal from the page
  const subtotalText = document.querySelector('.total-row span:nth-child(2)').textContent;
  const subtotal = parseFloat(subtotalText.replace('₹', ''));
  
  // Define shipping cost and COD fee
  const shippingCost = 5.99;
  const codFee = 9.00;
  
  // Check if COD is selected
  const paymentMethod = document.querySelector('input[name="payment"]:checked')?.value;
  const total = subtotal + shippingCost + (paymentMethod === 'cod' ? codFee : 0);
  
  // Update the total display
  document.getElementById("total").textContent = `₹${total.toFixed(2)}`;
}
  </script>
  <?php include 'footer.php'; ?>
</body>
</html>