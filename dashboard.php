<?php
include 'db.php';
include 'header.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$OrdersSql = "
    SELECT 
        o.order_id, 
        o.order_date, 
        o.status, 
        o.total_amount, 
        oi.product_id, 
        oi.quantity, 
        p.name AS product_name
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE o.email = ?
    ORDER BY o.order_date DESC";
$stmt = $conn->prepare($OrdersSql);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$OrdersResult = $stmt->get_result();

// Group orders and their items
$Orders = [];
while ($row = $OrdersResult->fetch_assoc()) {
    $order_id = $row['order_id'];
    if (!isset($Orders[$order_id])) {
        $Orders[$order_id] = [
            'order_id' => $row['order_id'],
            'order_date' => $row['order_date'],
            'status' => $row['status'],
            'total_amount' => $row['total_amount'],
            'items' => []
        ];
    }
    $Orders[$order_id]['items'][] = [
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity']
    ];
}
$stmt->close();

// Update address functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_address'])) {
    $new_address = $conn->real_escape_string($_POST['address']);
    $new_zip_code = $conn->real_escape_string($_POST['zip_code']);
    $user_email = $_SESSION['email'];

    $sql = "UPDATE users SET address='$new_address', zip_code='$new_zip_code' WHERE email='$user_email'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['address'] = $new_address;
        $_SESSION['zip_code'] = $new_zip_code;
        echo "<script>alert('Address updated successfully'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating address: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GRO-C - Dashboard</title>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script defer src="main.js"></script>
  <style>
    @keyframes fadeIn {
     from {
       opacity: 0;
     }
     to {
       opacity: 1;
     }
    }
    .dashboard-section {
      animation: fadeIn 0.5s ease-in-out;
      padding: 40px 0;
    }

    .dashboard-top {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      align-items: start;
      margin-bottom: 40px;
    }

    /* Reversed order: content on left, image on right */
    .dashboard-image {
      order: 2;
    }
    
    .dashboard-profile {
      order: 1;
    }
    
    .dashboard-image img {
      width: 100%;
      height: auto;
      border-radius: 8px;
     
    }

    .dashboard-orders {
      max-width: 800px;
      margin: 0 auto;
    }

    .dashboard-card {
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 24px;
      width: 100%;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .dashboard-card h2 {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 20px;
      color: #16a34a;
      display: inline-block;
    }

    .profile-section {
      margin-bottom: 24px;
    }

    .profile-info {
      background-color: #f9fafb;
      padding: 16px;
      border-radius: 6px;
      margin-bottom: 20px;
      border-left: 4px solid #16a34a;
    }

    .profile-info p {
      margin-bottom: 12px;
      display: flex;
      align-items: center;
    }

    .profile-info p:last-child {
      margin-bottom: 0;
    }

    .profile-info strong {
      min-width: 100px;
      display: inline-block;
    }

    .profile-info i {
      color: #16a34a;
      margin-right: 10px;
      font-size: 16px;
      width: 20px;
      text-align: center;
    }

    .address-section {
      margin-bottom: 24px;
    }

    .address-form {
      background-color: #f9fafb;
      padding: 16px;
      border-radius: 6px;
      border-left: 4px solid #16a34a;
    }

    .address-form input {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #e2e8f0;
      border-radius: 4px;
      margin-bottom: 16px;
      font-size: 15px;
      transition: border-color 0.3s;
    }

    .address-form input:focus {
      border-color: #16a34a;
      outline: none;
      box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2);
    }

    .address-form button {
      padding: 10px 18px;
      background-color: #16a34a;
      color: #ffffff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
    }

    .address-form button:hover {
      background-color: #15803d;
    }

    .address-form button i {
      margin-right: 8px;
    }

    .logout-section {
      margin-top: 20px;
    }

    .logout-button {
      width: 100%;
      padding: 12px 24px;
      background-color: #ef4444;
      color: #ffffff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-size: 1rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .logout-button:hover {
      background-color: #dc2626;
    }

    .logout-button i {
      margin-right: 8px;
    }

    .dashboard-orders {
      max-width: 1200px;
      margin: 0 auto;
    }
    #orders {
   display: grid;
   grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
   gap: 20px;
    }
    .order-card {
      
      background-color: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 16px;
      margin-bottom: 16px;
      position: relative;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .order-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
     }
    .order-card h4 {
      font-size: 1.125rem;
      font-weight: bold;
      margin-bottom: 12px;
      color: #16a34a;
    }

    .order-card p {
      margin-bottom: 6px;
    }
    
.order-card ul {
  margin-top: 10px;
  padding-left: 20px;
}

.order-card ul li {
  font-size: 0.9rem;
  color: #4b5563;
  margin-bottom: 6px;
}

    .cancel-button {
      padding: 8px 16px;
      background-color: #ef4444;
      color: #ffffff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 10px;
    }

    .cancel-button:hover {
      background-color: #dc2626;
    }

    .cancel-button i {
      margin-right: 6px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .dashboard-top {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .dashboard-image {
        display: none; /* Hide the image on smaller screens */
      }
      
      .dashboard-profile {
        order: 1;
      }

      .profile-info strong {
        min-width: 80px;
      }
    }
  </style>
</head>
<body style="background-color:rgb(199, 223, 206)  ;">
  
  <section class="dashboard-section">
    <div class="container">
      <!-- Top section: Profile info (left) and Image (right) -->
      <div class="dashboard-top">
        <div class="dashboard-profile">
          <div class="dashboard-card">
            <div class="profile-section">
              <h2>Profile Information</h2>
              <div class="profile-info">
                <p><i class="fas fa-user"></i> <strong>Username:</strong> <span id="username"><?php  echo isset($_SESSION['user']) ? $_SESSION['user'] : ''; ?></span></p>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <span id="email"><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?></span></p>
              </div>
            </div>

            <div class="address-section">
              <h2>Shipping Address</h2>
              <form action="dashboard.php" method="POST" class="address-form">
                <input type="text" name="address" id="address" placeholder="Enter your shipping address" value="<?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?>" required />
                <input type="text" name="zip_code" id="zip_code" placeholder="Enter your ZIP code" value="<?php echo isset($_SESSION['zip_code']) ? $_SESSION['zip_code'] : ''; ?>" required/>
                <button type="submit" name="update_address"><i class="fas fa-map-marker-alt"></i> Update Address</button>
              </form>
            </div>
            
            <div class="logout-section">
              <form action="logout.php" method="POST">
                <button type="submit" class="logout-button"><i class="fas fa-sign-out-alt"></i> Logout</button>
              </form>
            </div>
          </div>
        </div>
        
        <div class="dashboard-image">
          <img src="images/dashboard.png" alt="Dashboard Image" />
        </div>
      </div>
    
      <div class="dashboard-orders">
    <div class="dashboard-card">
        <h2>Orders</h2>
        <div id="orders">
            <?php if (count($Orders) > 0): ?>
                <?php foreach ($Orders as $order): ?>
                    <div class="order-card">
                        <h4>Order #<?php echo $order['order_id']; ?></h4>
                        <p><strong>Date:</strong> <?php echo $order['order_date']; ?></p>
                        <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
                        <p><strong>Total:</strong> ₹<?php echo number_format($order['total_amount'], 2); ?></p>
                        <p>Items:</p>
                        <ul>
                            <?php foreach ($order['items'] as $item): ?>
                                <li>
                                    <?php echo $item['product_name']; ?> (x<?php echo $item['quantity']; ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if ($order['status'] != 'Canceled' && $order['status'] != 'Completed'): ?>
                            <button class="cancel-button">
                                <a href="cancel_order.php?order_id=<?php echo $order['order_id']; ?>">Cancel Order</a>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>  
    </div>   
  </section>   
  <?php include 'footer.php'; ?>    
</body>    
</html>