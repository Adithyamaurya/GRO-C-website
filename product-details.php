<?php include 'header.php';
include 'db.php';
// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details from the database
$product = null;
if ($product_id > 0) {
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Details - GRO-C</title>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script defer src="main.js"></script>
  <script src="search.js"></script>

<style>
   @keyframes fadeIn {
     from {
       opacity: 0;
     }
     to {
       opacity: 1;
     }
    }
   /* Enhanced Product Details Styles */
   .product-details {
      animation: fadeIn 0.5s ease-in-out;
      padding: 60px 0;
      
    }

    .product-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      background-color: #ffffff;
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .product-image {
      position: relative;
      overflow: hidden;
      border-radius: 12px;
      background-color: #f1f5f9;
      padding: 20px;
    }

    .product-image img {
      width: 100%;
      height: auto;
      border-radius: 8px;
      transition: transform 0.3s ease;
    }

    .product-image:hover img {
      transform: scale(1.05);
    }

    .product-info {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .product-title {
      font-size: 2.5rem;
      font-weight: bold;
      color: #1e293b;
      line-height: 1.2;
    }

    .product-description {
      font-size: 1.1rem;
      color: #475569;
      line-height: 1.6;
    }

    .product-meta {
      display: flex;
      flex-direction: column;
      gap: 12px;
      padding: 20px;
      background-color: #f1f5f9;
      border-radius: 8px;
    }

    .product-meta p {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.95rem;
      color: #475569;
    }

    .product-meta p i {
      color: #16a34a;
      width: 20px;
      text-align: center;
    }

    .product-price {
      display: flex;
      align-items: center;
      gap: 16px;
      font-size: 1.75rem;
      color: #16a34a;
      font-weight: bold;
    }

    .product-price .original-price {
      font-size: 1.25rem;
      color: #64748b;
      text-decoration: line-through;
    }

  #productPrevPrice {
    font-size: 1.25rem;
    color: #64748b;
    text-decoration: line-through;
    position: relative;
    margin-left: 8px;
    font-weight: 500;
  }

  /* Optional: Add a more stylish strikethrough */
  #productPrevPrice::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    width: 100%;
    height: 1.5px;
    background-color: #ef4444;
    transform: rotate(-5deg);
    opacity: 0.8;
  }

  /* Optional: Add a subtle shadow for depth */
  #productPrevPrice {
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
  }

  /* Optional: Add a transition for hover effect */
  #productPrevPrice {
    transition: all 0.2s ease;
  }

  #productPrevPrice:hover {
    opacity: 0.8;
    transform: translateY(-1px);
  }

    

    .quantity-control {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin: 0;
    }

    .quantity-control label {
      font-weight: 500;
      color: #1e293b;
    }

    .quantity-control select {
      width: 100%;
      padding: 8px;
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      background-color: white;
      font-size: 1rem;
      transition: border-color 0.2s ease;
    }

    .quantity-control select:focus {
      outline: none;
      border-color: #16a34a;
    }

    .price-calculator {
      margin-top: 0;
      padding: 16px;
      background-color: #f1f5f9;
      border-radius: 12px;
    }

    .price-calculator p {
      margin: 8px 0;
      font-size: 1.1rem;
      color: #475569;
    }

    .price-calculator .total-price {
      font-size: 1.5rem;
      font-weight: bold;
      color: #16a34a;
    }

    .add-to-cart-btn {
      width: 100%;
      padding: 8px;
      background-color: #16a34a;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 0.9rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    .add-to-cart-btn:hover {
      background-color: #15803d;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
    }

    .add-to-cart-btn:active {
      transform: translateY(0);
    }

    .add-to-cart-btn i {
      font-size: 1rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .product-container {
        grid-template-columns: 1fr;
        gap: 40px;
        padding: 20px;
      }

      .product-title {
        font-size: 2rem;
      }

      .product-price {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body style="background-color:rgb(199, 223, 206)  ;">
  
    <!-- Product Details Section -->
  <section class="product-details">
    <div class="product-container" id="productContainer">
      <?php if ($product): ?>
        <div class="product-image">
          <img id="productImage" src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
        </div>
        <div class="product-info">
          <h1 class="product-title" id="productName"><?php echo htmlspecialchars($product['name']); ?></h1>
          <p class="product-description" id="productDescription"><?php echo htmlspecialchars($product['description']); ?></p>
          
          <div class="product-meta">
            <p><i class="fas fa-tag"></i>Category: <span id="productCategory"><?php echo htmlspecialchars($product['category']); ?></span></p>
            <p><i class="fas fa-weight-hanging"></i>Weight/Volume: <span id="productWeight"><?php echo htmlspecialchars($product['weight']); ?></span></p>
          </div>
          
          <div class="product-price">
            ₹<span id="productPrice"><?php echo htmlspecialchars($product['price']); ?></span>
            <span class="original-price">₹<span id="productPrevPrice"><?php echo htmlspecialchars($product['prevprice']); ?></span></span>
          </div>
          
          <div class="quantity-control">
            <label for="quantity">Select Quantity (kg):</label>
            <select id="quantity" name="quantity">
              <option value="0.5">0.5 kg</option>
              <option value="1">1 kg</option>
              <option value="1.5">1.5 kg</option>
              <option value="2">2 kg</option>
              <option value="3">3 kg</option>
              <option value="5">5 kg</option>
            </select>
          </div>
          
          <div class="price-calculator">
             <p>Total Price: <span class="total-price">₹<span id="totalPrice"><?php echo htmlspecialchars($product['price']); ?></span></span></p>
               <form method="post" action="add_to_cart.php">
                  <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                  <input type="hidden" name="quantity" id="quantityValue" value="1">
                  <button type="submit" class="add-to-cart-btn" id="addToCartBtn">
                  <i class="fas fa-cart-plus"></i>
                  Add to Cart
                  </button>
               </form>
            </div>
         </div>
      <?php else: ?>
         <p>Product not found</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- <script>
    // Get product ID from URL
    // const urlParams = new URLSearchParams(window.location.search);
    // const productId = urlParams.get('id');

    // Product Data (same as in products.html)
    // const products = [
    
    //     {
    //       id: 1,
    //       name: "Bread",
    //       image: "images/product-1.jpg",
    //       description: "A loaf of fresh bread made from whole wheat flour. Perfect for sandwiches and toast.",
    //       price: 50,
    //       prevprice: 60,
    //       weight: "500g",
    //       category: "Bakery"
    //     },
    //     {
    //       id: 2,
    //       name: "Elaichi",
    //       image: "images/product-2.jpg",
    //       description: "A pack of fresh elaichi (cardamom) pods. Adds a fragrant aroma to your dishes.",
    //       price: 300,
    //       prevprice: 350,
    //       weight: "100g",
    //       category: "Spices"
    //     },
    //     {
    //       id: 3,
    //       name: "Grains",
    //       image: "images/product-3.png",
    //       description: "A variety of grains including rice, wheat, and barley. Essential for a balanced diet.",
    //       price: 599,
    //       prevprice: 699,
    //       weight: "1kg",
    //       category: "Groceries"
    //     },
    //     {
    //       id: 4,
    //       name: "Watermelon",
    //       image: "images/product-4.jpg",
    //       description: "A juicy watermelon, perfect for a refreshing summer treat.",
    //       price: 30,
    //       prevprice: 40,
    //       weight: "1.5kg",
    //       category: "Fruits"
    //     },
    //     {
    //       id: 5,
    //       name: "Oil",
    //       image: "images/product-5.png",
    //       description: "A bottle of pure cooking oil, ideal for frying and baking.",
    //       price: 70,
    //       prevprice: 80,
    //       volume: "1L",
    //       category: "Cooking Essentials"
    //     },
    //     {
    //       id: 6,
    //       name: "Mint",
    //       image: "images/product-6.jpg",
    //       description: "A bunch of fresh mint leaves, perfect for garnishing and adding flavor to your dishes.",
    //       price: 20,
    //       prevprice: 30,
    //       weight: "100g",
    //       category: "Herbs"
    //     },
    //     {
    //       id: 7,
    //       name: "Cabbage",
    //       image: "images/product-7.png",
    //       description: "A fresh cabbage, great for salads and stir-fries.",
    //       price: 50,
    //       prevprice: 60,
    //       weight: "1kg",
    //       category: "Vegetables"
    //     },
    //     {
    //       id: 8,
    //       name: "Salt",
    //       image: "images/product-8.png",
    //       description: "A pack of iodized salt, essential for seasoning your dishes.",
    //       price: 40,
    //       prevprice: 50,
    //       weight: "1kg",
    //       category: "Cooking Essentials"
    //     },
    //     {
    //       id: 9,
    //       name: "MDH Masala",
    //       image: "images/product-9.png",
    //       description: "A pack of MDH Masala, a blend of spices for adding flavor to your dishes.",
    //       price: 30,
    //       prevprice: 40,
    //       weight: "100g",
    //       category: "Spices"
    //     },
    //     {
    //       id: 10,
    //       name: "Atta",
    //       image: "images/product-10.png",
    //       description: "High-quality wheat grains, ideal for making flour and various dishes.",
    //       price: 599,
    //       prevprice: 699,
    //       weight: "1kg",
    //       category: "Grains"
    //     },
    //     {
    //       id: 11,
    //       name: "Ketchup",
    //       image: "images/product-11.png",
    //       description: "A bottle of tangy and delicious ketchup, perfect for adding flavor to your meals.",
    //       price: 69,
    //       prevprice: 100,
    //       volume: "500ml",
    //       category: "Condiments"
    //     },
    //     {
    //       id: 12,
    //       name: "Potato",
    //       image: "images/product-12.png",
    //       description: "Fresh potatoes, versatile and essential for many dishes.",
    //       price: 40,
    //       prevprice: 50,
    //       weight: "1kg",
    //       category: "Vegetables"
    //     },
    //     {
    //       id: 13,
    //       name: "Tomato",
    //       image: "images/product-13.png",
    //       description: "Fresh tomatoes, great for salads, sauces, and cooking.",
    //       price: 60,
    //       prevprice: 80,
    //       weight: "1kg",
    //       category: "Vegetables"
    //     },
    //     {
    //       id: 14,
    //       name: "Fish",
    //       image: "images/product-14.png",
    //       description: "Fresh fish, perfect for a variety of dishes.",
    //       price: 200,
    //       prevprice: 250,
    //       weight: "1kg",
    //       category: "Seafood"
    //     },
    //     {
    //       id: 15,
    //       name: "Yogurt",
    //       image: "images/product-15.png",
    //       description: "Fresh and creamy yogurt, perfect for a healthy snack or cooking.",
    //       price: 249,
    //       prevprice: 300,
    //       weight: "500g",
    //       category: "Dairy"
    //     },
    //     {
    //       id: 16,
    //       name: "Chicken Meat",
    //       image: "images/product-16.png",
    //       description: "Fresh chicken meat, perfect for a variety of dishes.",
    //       price: 1000,
    //       prevprice: 1200,
    //       weight: "1kg",
    //       category: "Meat"
    //     },
    //     {
    //       id: 17,
    //       name: "Milk",
    //       image: "images/product-17.png",
    //       description: "Fresh milk from local farms, rich in nutrients.",
    //       price: 94,
    //       prevprice: 96,
    //       volume: "1L",
    //       category: "Dairy"
    //     },
    //     {
    //       id: 18,
    //       name: "Paneer",
    //       image: "images/product-18.png",
    //       description: "Fresh paneer made from cow's milk, perfect for Indian dishes.",
    //       price: 549,
    //       prevprice: 600,
    //       weight: "1kg",
    //       category: "Dairy"
    //     }
    //   ]

    // ;

    // Find the selected product
    // const product = products.find(p => p.id == productId);

    // Update page content
    if (product) {
      document.getElementById('productName').textContent = product.name;
      document.getElementById('productDescription').textContent = product.description;
      document.getElementById('productCategory').textContent = product.category;
      document.getElementById('productWeight').textContent = product.weight || product.volume;
      document.getElementById('productPrice').textContent = product.price;
      document.getElementById('productPrevPrice').textContent = product.prevprice; // Add this line
      document.getElementById('productImage').src = product.image;
      
      // Update total price when quantity changes
      const quantityInput = document.getElementById('quantity');
      const totalPriceElement = document.getElementById('totalPrice');
      
      const updateTotalPrice = () => {
        const quantity = parseFloat(quantityInput.value);
        const totalPrice = (product.price * quantity).toFixed(2);
        totalPriceElement.textContent = totalPrice;
      };
      
      quantityInput.addEventListener('input', updateTotalPrice);
      updateTotalPrice(); // Initial calculation
    } else {
      document.getElementById('productContainer').innerHTML = '<p>Product not found</p>';
    }
    // Setup event listener for add to cart button
    document.getElementById('addToCartBtn').addEventListener('click', () => {
      if (product) {
        const quantity = parseFloat(document.getElementById('quantity').value);
        addToCart(product, quantity);
        updateCartCount();
 
      }
    });

    // Initialize Cart Count
    updateCartCount();

  </script> -->
  <script>
    // Update total price when quantity changes
    const quantityInput = document.getElementById('quantity');
    const totalPriceElement = document.getElementById('totalPrice');
    const productPrice = <?php echo $product ? $product['price'] : 0; ?>;

    const updateTotalPrice = () => {
      const quantity = parseFloat(quantityInput.value);
      const totalPrice = (productPrice * quantity).toFixed(2);
      totalPriceElement.textContent = totalPrice;
    };

    quantityInput.addEventListener('input', updateTotalPrice);
    updateTotalPrice(); // Initial calculation
  </script>
</body>
</html>
