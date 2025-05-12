<?php
include 'db.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['seller'])) {
    header("Location: seller_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $image = $conn->real_escape_string($_POST['image']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $prevprice = $conn->real_escape_string($_POST['prevprice']);
    $weight = $conn->real_escape_string($_POST['weight']);
    $category = $conn->real_escape_string($_POST['category']);
    $seller_id = $_SESSION['seller_id'];
    $sql = "INSERT INTO products (name, image, description, price, prevprice, weight, category, seller_id) VALUES ('$name', '$image', '$description', '$price', '$prevprice', '$weight', '$category', '$seller_id')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product added successfully'); window.location.href='seller_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Product - GRO-C</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <link rel="stylesheet" href="css/main.css">
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

    .add-product-form {
      animation: fadeIn 0.5s ease-in-out;
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 24px;
      margin-top: 40px;
    }

    .add-product-form h2 {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 16px;
      color: #16a34a;
      text-align: center;
    }

    #addProductForm{
        background-color: #f9fafb;
      padding: 16px;
      border-radius: 6px;
      border-left: 4px solid #16a34a;
    }
    .form-group {
      margin-bottom: 16px;
    }

    
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
      padding: 10px 12px;
      border: 1px solid #e2e8f0;
      border-radius: 4px;
      margin-bottom: 16px;
      font-size: 15px;
      transition: border-color 0.3s;
    }

    .form-group input:focus, 
    .form-group textarea:focus,
    .form-group select:focus{
      border-color: #16a34a;
      outline: none;
      box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2);
    }
    .form-group button {
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

    .form-group button:hover {
      background-color: #15803d;
    }
  </style>
</head>
<body>
  

<section class="add-product">
    <div class="container">
      <div class="add-product-form">
        <h2>Add New Product</h2>
        <form id="addProductForm" action="sell.php" method="post">
          <div class="form-group">
           
            <input type="text" id="name" name="name" placeholder="Enter product name" required>
          </div>
          <div class="form-group">
            <input type="text" id="image" name="image" placeholder="Enter image URL" required>
          </div>
          <div class="form-group">
            <textarea id="description" name="description" rows="3" placeholder="Enter product description" required></textarea>
          </div>
          <div class="form-group">
            <input type="number" id="price" name="price" placeholder="Enter price" required>
          </div>
          <div class="form-group">
            <input type="number" id="prevprice" name="prevprice" placeholder="Enter previous price" required>
          </div>
          <div class="form-group">
            <input type="text" id="weight" name="weight" placeholder="Enter weight/volume" required>
          </div>
          <div class="form-group">
            <select id="category" name="category" required>
              <option value="Bakery" placeholder="Select category">Bakery</option>
              <option value="Spices">Spices</option>
              <option value="Groceries">Groceries</option>
              <option value="Fruits">Fruits</option>
              <option value="Cooking Essentials">Cooking Essentials</option>
              <option value="Herbs">Herbs</option>
              <option value="Vegetables" >Vegetables</option>
              <option value="Condiments">Condiments</option>
              <option value="Seafood">Seafood</option>
              <option value="Dairy">Dairy</option>
              <option value="Meat">Meat</option>
            </select>
          </div>
          <div class="form-group">
            <button type="submit">Add Product</button>
          </div>
        </form>
      </div>
    </div>
  </section>
<?php include 'footer.php'; ?>
</body>
</html>
