<?php
include 'db.php';
include 'header.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$cartItems = [];

if ($user_id) {
    $sql = "SELECT cart.*, products.name, products.price, products.image FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }
} else {
    $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GRO-C - Cart</title>
  <link rel="stylesheet" href="css/main.css">
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
    .cart {
      animation: fadeIn 0.5s ease-in-out;
      padding: 40px 0;
    }
    .cart h3 {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 24px;
    }
    .cart-item {
      display: grid;
      grid-template-columns: auto 1fr auto auto auto;
      gap: 24px;
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 16px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      align-items: center;
    }

    .cart-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .cart-item img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
    }

    .cart-item-details {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .cart-item h4 {
      font-size: 1.25rem;
      font-weight: bold;
      margin-bottom: 4px;
    }

    .estimated-delivery {
      font-size: 0.875rem;
      color: #64748b;
    }

    .quantity-controls {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .quantity-controls button {
      width: 32px;
      height: 32px;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      background-color: #ffffff;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .quantity-controls button:hover {
      background-color: #f8fafc;
    }

    .quantity-display {
      font-weight: 600;
      min-width: 40px;
      text-align: center;
    }

    .price {
      font-size: 1.25rem;
      font-weight: bold;
      color: #16a34a;
    }

    .remove-button {
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      background-color: #fee2e2;
      color: #ef4444;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .remove-button:hover {
      background-color: #fecaca;
    }

    .cart-summary {
      margin-top: 32px;
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 24px;
    }

    .cart-actions {
      display: flex;
      gap: 16px;
      margin-top: 24px;
    }

    .continue-shopping {
      padding: 12px 24px;
      background-color: #ffffff;
      border: 2px solid #16a34a;
      color: #16a34a;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .continue-shopping:hover {
      background-color: #f0fdf4;
    }

    .checkout-button {
      padding: 12px 24px;
      background-color: #16a34a;
      color: #ffffff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .checkout-button:hover {
      background-color: #15803d;
    }

    .empty-cart {
      text-align: center;
      padding: 48px;
      background-color: #ffffff;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
    }

    .empty-cart h4 {
      font-size: 1.5rem;
      margin-bottom: 16px;
    }
  </style>
</head>
<body style="background-color:rgb(199, 223, 206);">
   <!-- Cart Section -->
   <section class="cart">
    <div class="container">
      <h3>Your Cart</h3>
      <div class="cart-items" id="cartItems">
        <?php
          if (empty($cartItems)) {
          echo "<div class='empty-cart'>
                  <h4>Your cart is empty</h4>
                  <button class='continue-shopping' onclick=\"window.location.href='products.php'\">
                    Start Shopping
                  </button>
                </div>";
        } else {
          $subtotal = 0;
          foreach ($cartItems as $index => $item) {
            $quantity = $item['quantity'];
            $itemTotal = $item['price'] * $quantity;
            $subtotal += $itemTotal;
            echo "<div class='cart-item'>
                    <img src='{$item['image']}' alt='{$item['name']}' />
                    <div class='cart-item-details'>
                      <h4>{$item['name']}</h4>
                      <p class='estimated-delivery'>Estimated delivery: " . date('l, F j', strtotime('+1 day')) . "</p>
                    </div>
                    <div class='quantity-controls'>
                      <form method='post' action='update_cart.php'>
                        <input type='hidden' name='product_id' value='{$item['product_id']}'>
                        <button type='submit' name='action' value='decrease'>-</button>
                        <span class='quantity-display'>{$quantity}</span>
                        <button type='submit' name='action' value='increase'>+</button>
                      </form>
                    </div>
                    <p class='price'>₹" . number_format($itemTotal, 2) . "</p>
                    <form method='post' action='remove_from_cart.php'>
                      <input type='hidden' name='product_id' value='{$item['product_id']}'>
                      <button type='submit' class='remove-button'>Remove</button>
                    </form>
                  </div>";
          }
          echo "<div class='cart-summary'>
                  <h4>Order Summary</h4>
                  <div class='cart-total'>
                    <p>Subtotal: ₹<span id='cartSubtotal'>" . number_format($subtotal, 2) . "</span></p>
                    <p>Shipping: ₹<span id='shippingCost'>5.99</span></p>
                    <h4>Total: ₹<span id='cartTotal'>" . number_format($subtotal + 5.99, 2) . "</span></h4>
                  </div>
                  <div class='cart-actions'>
                    <button class='continue-shopping' onclick=\"window.location.href='products.php'\">
                      Continue Shopping
                    </button>
                    <button class='checkout-button' onclick=\"window.location.href='checkout.php'\">
                      Proceed to Checkout
                    </button>
                  </div>
                </div>";
        }
        ?>
      </div>
    </div>
  </section>
  <?php include 'footer.php'; ?>
</body>
</html>
