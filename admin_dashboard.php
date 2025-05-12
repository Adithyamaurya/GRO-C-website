<?php
session_start();
include 'db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['email'] != 'admin_groc@gmail.com') {
    header("Location: login.php");
    exit();
}

// Fetch users, sellers, and products
$users_sql = "SELECT * FROM users";
$users_result = $conn->query($users_sql);

$sellers_sql = "SELECT * FROM sellers";
$sellers_result = $conn->query($sellers_sql);

$products_sql = "SELECT * FROM products";
$products_result = $conn->query($products_sql);

// Handle deletion requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_user'])) {
        $user_id = intval($_POST['user_id']);
        $sql = "DELETE FROM users WHERE id = $user_id";
        $conn->query($sql);
    } elseif (isset($_POST['delete_seller'])) {
        $seller_id = intval($_POST['seller_id']);
        $sql = "DELETE FROM sellers WHERE id = $seller_id";
        $conn->query($sql);
    } elseif (isset($_POST['delete_product'])) {
        $product_id = intval($_POST['product_id']);
        $sql = "DELETE FROM products WHERE id = $product_id";
        $conn->query($sql);
    }
    header("Location: admin_dashboard.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GRO-C - Admin Dashboard</title>
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
    .container {
      animation: fadeIn 0.5s ease-in-out;
      padding: 40px 0;
      
    }

    .container h3 {
        font-size: 2rem;
      font-weight: bold;
      margin-bottom: 24px;
    }
    .users, .products{
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 24px;
      width: 100%;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    h1, h2 {
      color: #333;
    }

    table {
        border: 1px solid #ddd;
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 10px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    button {
      background-color: #ff4d4d;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
    }

    button:hover {
      background-color: #ff1a1a;
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
  </style>
</head>
<body style="background-color:rgb(199, 223, 206)  ;">
  <div class="container">
    <h3>Admin Dashboard</h3>
    
    <section class="users">
      <h2>Manage Users</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($user = $users_result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $user['id']; ?></td>
              <td><?php echo $user['username']; ?></td>
              <td><?php echo $user['email']; ?></td>
              <td>
                <form method="POST" action="admin_dashboard.php" style="display:inline;">
                  <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                  <button type="submit" name="delete_user" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </section>

    <section class="products">
      <h2>Manage Products</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($product = $products_result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $product['id']; ?></td>
              <td><?php echo $product['name']; ?></td>
              <td><?php echo $product['price']; ?></td>
              <td>
                <form method="POST" action="admin_dashboard.php" style="display:inline;">
                  <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                  <button type="submit" name="delete_product" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </table>
        <div class="logout-section">
              <form action="logout.php" method="POST">
                <button type="submit" class="logout-button"><i class="fas fa-sign-out-alt"></i> Logout</button>
              </form>
            </div>
    </section>
   
  </div>
</body>
</html>