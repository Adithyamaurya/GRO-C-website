<?php
include 'db.php';
include 'header.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['seller'])) {
    header("Location: seller_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_address'])) {
    $new_address = $conn->real_escape_string($_POST['address']);
    $new_zip_code = $conn->real_escape_string($_POST['zip_code']);
    $new_phone = $conn->real_escape_string($_POST['phone']);
    $seller_email = $_SESSION['email'];

    $sql = "UPDATE sellers SET address='$new_address', zip_code='$new_zip_code', phone='$new_phone' WHERE email='$seller_email'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['address'] = $new_address;
        $_SESSION['zip_code'] = $new_zip_code;
        $_SESSION['phone'] = $new_phone;
        echo "<script>alert('Address updated successfully'); window.location.href='seller_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating address: " . $conn->error . "');</script>";
    }
}

// Fetch seller orders for dashboard statistics
$seller_id = isset($_SESSION['seller_id']) ? $_SESSION['seller_id'] : 0;

// Get total orders count
$total_sql = "SELECT 
    COUNT(*) as total_orders,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_orders,
    SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed_orders
    FROM orders WHERE seller_id = '$seller_id'";
$total_result = $conn->query($total_sql);
$order_stats = $total_result->fetch_assoc();


// Fetch all orders with product details
$all_orders_sql = "
    SELECT 
        o.order_id, 
        o.order_date, 
        o.status, 
        o.total_amount, 
        o.customer_name, 
        oi.product_id, 
        oi.quantity, 
        p.name AS product_name
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE o.seller_id = ?
    ORDER BY o.order_date DESC";
$stmt = $conn->prepare($all_orders_sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$all_orders_result = $stmt->get_result();

// Group orders and their items
$Orders = [];
while ($row = $all_orders_result->fetch_assoc()) {
    $order_id = $row['order_id'];
    if (!isset($Orders[$order_id])) {
        $Orders[$order_id] = [
            'order_id' => $row['order_id'],
            'order_date' => $row['order_date'],
            'status' => $row['status'],
            'total_amount' => $row['total_amount'],
            'customer_name' => $row['customer_name'],
            'items' => []
        ];
    }
    $Orders[$order_id]['items'][] = [
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity']
    ];
}
$stmt->close();

// Get products added by the seller
$products_sql = "SELECT * FROM products WHERE seller_id = '$seller_id'";
$products_result = $conn->query($products_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GRO-C - Seller Dashboard</title>
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

    /* Order card styles */
    .dashboard-orders {
       max-width: 1200px;
       margin: 0 auto;
    }
    #allOrders {
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

    .status-button, .cancel-button  {
      padding: 8px 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      display: flex;  
      align-items: center;
      justify-content: center;
      margin-top: 10px;
    }
    .status-button {
      background-color: #16a34a;
      color: #ffffff;
    }


    .status-button:hover {
      background-color: #15803d;
    }

    .status-button i {
      margin-right: 6px;
    }

    .cancel-button {
   background-color: #ef4444;
   color: #ffffff;
   }

   .cancel-button:hover {
    background-color: #dc2626;
   }
    


   .status-button i, .cancel-button i {
      margin-right: 6px;
    }

    .stats-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-bottom: 20px;
    }

    /* Stat card styles */
    .stat-card {
      background-color: #f9fafb;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      border-top: 4px solid #16a34a;
      transition: transform 0.3s ease;
    }

    
    .stat-card h3 {
      font-size: 1rem;
      color: #4b5563;
      margin-bottom: 8px;
    }

    .stat-card .number {
      font-size: 2rem;
      font-weight: bold;
      color: #16a34a;
    }

    .status-tag {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .status-pending {
      background-color: #fef3c7;
      color: #d97706;
    }

    .status-completed {
      background-color: #d1fae5;
      color: #16a34a;
    }

    .status-shipped {
      background-color: #dbeafe;
      color: #2563eb;
    }

    .status-canceled {
      background-color: #fee2e2;
      color: #ef4444;
    }
    
    /* My Products Section */
    .product-card {
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 16px;
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin-bottom: 20px;
    }

    #myProducts {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
    }

    .product-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .product-card h4 {
      font-size: 1.125rem;
      font-weight: bold;
      margin-bottom: 8px;
      color: #16a34a;
    }

    .product-card p {
      font-size: 1rem;
      color: #4b5563;
      margin-bottom: 12px;
    }

    .edit-button, .delete-button {
      display: inline-block;
      padding: 8px 16px;
      border-radius: 4px;
      text-decoration: none;
      color: #ffffff;
      font-weight: 500;
      transition: background-color 0.3s ease;
      margin-right: 8px;
    }
    /* 
    .edit-button {
      background-color: #2563eb;
    }

    .edit-button:hover {
      background-color: #1d4ed8;
    } */

    .delete-button {
      background-color: #ef4444;
    }

    .delete-button:hover {
      background-color: #dc2626;
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

      .stats-container {
        grid-template-columns: 1fr;
      }

      .status-button, .cancel-button {
        position: static;
        margin-top: 10px;
        width: 100%;
        margin-right: 0;
      }

      .cancel-button {
        margin-top: 8px;
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
                <p><i class="fas fa-user"></i> <strong>Username:</strong> <span id="username"><?php echo isset($_SESSION['seller']) ? $_SESSION['seller'] : ''; ?></span></p>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <span id="email"><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?></span></p>
              </div>
            </div>

            <div class="address-section">
              <h2>Store Address</h2>
              <form action="seller_dashboard.php" method="POST" class="address-form">
                <input type="text" name="address" id="address" placeholder="Enter your store address" value="<?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?>" required />
                <input type="text" name="zip_code" id="zip_code" placeholder="Enter your ZIP code" value="<?php echo isset($_SESSION['zip_code']) ? $_SESSION['zip_code'] : ''; ?>" required/>
                <input type="text" name="phone" id="phone" placeholder="Enter your phone number" value="<?php echo isset($_SESSION['phone']) ? $_SESSION['phone'] : ''; ?>" required/>
                <button type="submit" name="update_address"><i class="fas fa-map-marker-alt"></i> Update </button>
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
          <img src="images/dashboard.png" alt="Seller Dashboard Image" />
        </div>
      </div>
      
      <!-- Order Statistics Section -->
      <div class="dashboard-orders">
        <div class="dashboard-card">
          <h2>Order Statistics</h2>
          <div class="stats-container">
            <div class="stat-card">
              <h3>Total Orders</h3>
              <div class="number"><?php echo $order_stats['total_orders'] ?? 0; ?></div>
            </div>
            <div class="stat-card">
              <h3>Pending Orders</h3>
              <div class="number"><?php echo $order_stats['pending_orders'] ?? 0; ?></div>
            </div>
            <div class="stat-card">
              <h3>Completed Orders</h3>
              <div class="number"><?php echo $order_stats['completed_orders'] ?? 0; ?></div>
            </div>
          </div>
        </div>
      
        <!-- All Orders Section -->
        <div class="dashboard-card">
          <h2>All Orders</h2>
          <div id="allOrders">
    <?php if (count($Orders) > 0): ?>
        <?php foreach ($Orders as $order): ?>
            <div class="order-card">
                <h4>Order #<?php echo $order['order_id']; ?></h4>
                <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($order['order_date'])); ?></p>
                <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p><strong>Total:</strong> ₹<?php echo number_format($order['total_amount'], 2); ?></p>
                <p><strong>Status:</strong> 
                    <span class="status-tag 
                        <?php 
                            switch ($order['status']) {
                                case 'Pending': echo 'status-pending'; break;
                                case 'Completed': echo 'status-completed'; break;
                                case 'Shipped': echo 'status-shipped'; break;
                                case 'Canceled': echo 'status-canceled'; break;
                                default: echo 'status-pending';
                            }
                        ?>">
                        <?php echo $order['status']; ?>
                    </span>
                </p>
                <p><strong>Items:</strong></p>
                <ul>
                    <?php foreach ($order['items'] as $item): ?>
                        <li>
                            <?php echo htmlspecialchars($item['product_name']); ?> (x<?php echo $item['quantity']; ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php if ($order['status'] == 'Pending'): ?>
                    <form action="update_order_status.php" method="POST" style="display:inline-block;">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <input type="hidden" name="status" value="Shipped">
                        <button type="submit" class="status-button"><i class="fas fa-truck"></i> Mark as Shipped</button>
                    </form>
                    <form action="update_order_status.php" method="POST" style="display:inline-block;">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <input type="hidden" name="status" value="Canceled">
                        <button type="submit" class="cancel-button"><i class="fas fa-times"></i> Cancel</button>
                    </form>
                <?php elseif ($order['status'] == 'Shipped'): ?>
                    <form action="update_order_status.php" method="POST" style="display:inline-block;">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <input type="hidden" name="status" value="Completed">
                        <button type="submit" class="status-button"><i class="fas fa-check"></i> Mark as Completed</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</div>

        <!-- Products Section -->
        <div class="dashboard-card">
          <h2>My Products</h2>
          <div id="myProducts">
            <?php
            if ($products_result && $products_result->num_rows > 0) {
              while($product = $products_result->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<h4>' . htmlspecialchars($product['name']) . '</h4>';
                echo '<p>Price: ₹' . htmlspecialchars($product['price']) . '</p>';
                echo '<p>Category: ' . htmlspecialchars($product['category']) . '</p>';
                echo '<p>Weight: ' . htmlspecialchars($product['weight']) . '</p>';
                echo '<a href="delete_product.php?product_id=' . $product['id'] . '" class="delete-button" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>';
                echo '</div>';
              }
            } else {
              echo '<p>No products found.</p>';
            }
            ?>
          </div>
        </div>
      </div>
    </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>