<?php include 'header.php';
include 'db.php'; 

// Get query parameters for search and category filtering
$query = isset($_GET['query']) ? $_GET['query'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : null;

// Build the SQL query
$sql = "SELECT * FROM products";
$params = [];
$types = "";

if (!empty($query) && !empty($category)) {
    $sql .= " WHERE name LIKE ? AND category = ?";
    $params[] = "%$query%";
    $params[] = $category;
    $types .= "ss";
} elseif (!empty($query)) {
    $sql .= " WHERE name LIKE ?";
    $params[] = "%$query%";
    $types .= "s";
} elseif (!empty($category)) {
    $sql .= " WHERE category = ?";
    $params[] = $category;
    $types .= "s";
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GRO-C - Products</title>
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
 .products {
  animation: fadeIn 1s ease-in-out;
 padding: 40px  0;
 }
 
 .products h3 {
   font-size: 2rem;
   font-weight: bold;
   margin-bottom: 24px;
 }

 .products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
  }
  
  .product-card {
    max-width: 300px;
    background-color: #ffffff;
   border: 1px solid #e2e8f0;
    border-radius: 8px;
   padding: 16px;
   text-align: center;
   transition: transform 0.3s ease, box-shadow 0.3s ease;
 }
 
 .product-card:hover {
   transform: translateY(-4px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
 }
 .product-image {
   cursor: pointer;
   position: relative;
   overflow: hidden;
   border-radius: 4px;
 }
  
 .product-image img {
   transition: transform 0.3s ease;
 }
 
 .product-image:hover img {
   transform: scale(1.05);
 }
  
 .product-image::after {
   content: "View Details";
   position: absolute;
   bottom: 0;
   left: 0;
   right: 0;
   background: rgba(22, 163, 74, 0.8);
    color: white;
   text-align: center;
   padding: 8px;
   transform: translateY(100%);
   transition: transform 0.3s ease;
 }
 
 .product-image:hover::after {
   transform: translateY(0);
  }
 .product-card img {
   width: 100%;
   height: 150px;
   object-fit: cover;
   border-radius: 4px;
   margin-bottom: 12px;
  }

 .product-card h4 {
   font-size: 1.125rem;
   font-weight: bold;
   margin-bottom: 8px;
 } 
 
 .product-card p {
   font-size: 1rem;
   color: #16a34a;
   margin-bottom: 12px;
  }
 
 .product-card button {
   width: 100%;
   padding: 8px;
   background-color: #16a34a;
   color: #ffffff;
   border: none;
   border-radius: 4px;
   cursor: pointer;
    transition: background-color 0.3s ease;
 }
 
 .product-card button:hover {
   background-color: #15803d;
 }
 
  .filter-bar {
    justify-content: center;
    align-items: center;
   margin-bottom: 20px;
   display: flex;
   gap: 10px;
 }

 .filter-bar select {
   padding: 8px;
   border: 1px solid #e2e8f0;
   border-radius: 4px;
   width: 40%;
   margin-bottom: 10px;
 }

 .filter-bar button {
   padding: 8px 16px;
   background-color: #16a34a;
   color: white;
   border: none;
   border-radius: 4px;
   cursor: pointer;
 }

 .filter-bar button:hover {
   background-color: #15803d;
 }
</style>
</head>
<body style="background-color:rgb(199, 223, 206);">
  <!-- Products Section -->
  <section class="products">
    <div class="container">
      <h3>All Products</h3>

      <!-- Search and Filter Bars -->
      <form method="GET" action="products.php" class="filter-bar">
        <select name="category" onchange="this.form.submit()">
          <option value="">All Categories</option>
          <option value="fruits" <?php echo $category == 'fruits' ? 'selected' : ''; ?>>Fruits</option>
          <option value="vegetables" <?php echo $category == 'vegetables' ? 'selected' : ''; ?>>Vegetables</option>
          <option value="dairy" <?php echo $category == 'dairy' ? 'selected' : ''; ?>>Dairy</option>
          <option value="bakery" <?php echo $category == 'bakery' ? 'selected' : ''; ?>>Bakery</option>
          <option value="spices" <?php echo $category == 'spices' ? 'selected' : ''; ?>>Spices</option>
          <option value="groceries" <?php echo $category == 'groceries' ? 'selected' : ''; ?>>Groceries</option>
          <option value="herbs" <?php echo $category == 'herbs' ? 'selected' : ''; ?>>Herbs</option>
          <option value="cooking essentials" <?php echo $category == 'cooking essentials' ? 'selected' : ''; ?>>Cooking Essentials</option> <?php echo $category == 'snacks' ? 'selected' : ''; ?>>Snacks</option>
          <option value="meat" <?php echo $category == 'meat' ? 'selected' : ''; ?>>Meat</option>
          <option value="seafood" <?php echo $category == 'seafood' ? 'selected' : ''; ?>>Seafood</option>
          <option value="condiments" <?php echo $category == 'condiments' ? 'selected' : ''; ?>>Condiments</option>
        </select>
      </form>

      <div class="products-grid" id="productsGrid">
      <?php
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo "<div class='product-card'>";
            echo "<div class='product-image' onclick=\"window.location.href='product-details.php?id=" . $row["id"] . "'\">";
            echo "<img src='" . $row["image"] . "' alt='" . $row["name"] . "'>";
            echo "</div>";
            echo "<h4>" . $row["name"] . "</h4>";
            echo "<p>₹" . $row["price"] . " <span style='text-decoration: line-through; color: #64748b;'>₹" . $row["prevprice"] . "</span></p>";
            echo "<p>" . $row["weight"] . "</p>";
            echo "<form method='post' action='add_to_cart.php'>
                     <input type='hidden' name='product_id' value='" . $row["id"] . "'>
                     <button type='submit' class='add-to-cart-btn'>Add to Cart</button>
                  </form>";
            echo "</div>";
          }
        } else {
          echo "<p>No products found.</p>";
        }

        $conn->close();
        ?>
      </div>
    </div>
  </section>

    <?php include 'footer.php'; ?>
</body>
</html>