<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Gro-C </title>
  <link rel="stylesheet" href="css/main.css">
  <script defer src="main.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- <script src="search.js"></script> -->
  
<style> 
    /* Hero Section */
    @keyframes fadeIn {
     from {
       opacity: 0;
     }
     to {
       opacity: 1;
     }
    }
    
    .hero {
      animation: fadeIn 1s ease-in-out;
      background-image: url('images/home_bg2.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      padding: 160px 20px;
      position: relative;
      color: white;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.25); /* Dark overlay for better text visibility */
    }

    .hero-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      align-items: center;
      position: relative;
      z-index: 1;
    }

    .hero h2 {
      font-size: 2.5rem;
      font-weight: bold;
      color: #ffffff;
    }

    .hero p {
      font-size: 1.125rem;
      color: #ffffff;
      margin: 16px 0;
    }

    .hero-buttons {
      display: flex;
      gap: 16px;
    }

    .hero-buttons button {
      padding: 12px 24px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .hero-buttons .primary {
      background-color: #15803d;
      color: #ffffff;
    }

    .hero-buttons .primary:hover {
      background-color: #16a34a;
    }

    .hero-buttons .secondary {
      background-color: transparent;
      border: 1px solid #15803d;
      color: #15803d;
    }

    .hero-buttons .secondary:hover {
      background-color: #15803d;
      color: #ffffff;
    }

    /* Categories Section */
    .categories {
      padding: 40px 0;
      text-align: center; /* Center the text */
    }

    .categories h3 {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 24px;
    }

    .categories-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 20px;
    }

    .category-card {
      background-color: white;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .category-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .category-card i {
      font-size: 2.5rem;
      color: #16a34a;
      margin-bottom: 12px;
    }

    .category-card h4 {
      font-size: 1.125rem;
      font-weight: bold;
      margin-bottom: 4px;
    }

    .category-card p {
      font-size: 0.875rem;
      color: #64748b;
    }

    /* New Styles for About Section */
    .about-section {
      padding: 80px 0;
      background-color: #ffffff;
    }

    .about-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      align-items: center;
    }

    .about-text {
      max-width: 500px;
    }

    .about-text h2 {
      font-size: 2.5rem;
      font-weight: bold;
      color: #1e293b;
      margin-bottom: 16px;
    }

    .about-text p {
      font-size: 1.125rem;
      color: #475569;
      line-height: 1.6;
      margin-bottom: 24px;
    }

    .about-text button {
      padding: 12px 24px;
      background-color: #16a34a;
      color: #ffffff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s ease;
    }

    .about-text button:hover {
      background-color: #15803d;
    }

    .about-images {
      display: flex;
      gap: 24px;
    }

    .about-image img {
      width: 100%;
      height: auto; /* Adjust height to auto */
      object-fit: cover;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
      .about-content {
      grid-template-columns: 1fr;
      gap: 40px;
      }

      .about-text {
      text-align: center;
      }

      .about-images {
      flex-direction: column;
      }

      .hero {
      padding: 100px 0;
      }

      .hero-content {
      grid-template-columns: 1fr;
      text-align: center;
      }
    }

    /* Blog Section */
    .blog {
      padding: 40px 0;
      max-width: 1200px;
      margin: 0 auto;
    }

    .blog .heading {
      text-align: center;
      font-size: 2.5rem;
      margin-bottom: 40px;
    }

    .blog .heading span {
      color: #16a34a;
    }

    .blog .box-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }

    .blog .box {
      background-color: white;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .blog .box:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .blog .box .image {
      height: 200px;
      overflow: hidden;
    }

    .blog .box .image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .blog .box:hover .image img {
      transform: scale(1.1);
    }

    .blog .box .content {
      padding: 20px;
    }

    .blog .box .content .icons {
      margin-bottom: 10px;
    }

    .blog .box .content .icons a {
      color: #16a34a;
      text-decoration: none;
    }

    .blog .box .content .icons a:hover {
      color: #15803d;
    }

    .blog .box .content h3 {
      font-size: 1.5rem;
      margin-bottom: 10px;
    }

    .blog .box .content p {
      font-size: 1rem;
      color: #64748b;
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .blog .box .content .btn {
      display: inline-block;
      padding: 8px 16px;
      background-color: #16a34a;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      transition: background-color 0.3s ease;
    }

    .blog .box .content .btn:hover {
      background-color: #15803d;
    }

    /* New Styles for Review Section */
    .review-section {
      padding: 80px 0;
      background-color: #ffffff;
    }

    .review-section h2 {
      font-size: 2.5rem;
      font-weight: bold;
      color: #1e293b;
      text-align: center;
      margin-bottom: 40px;
    }

    .review-section h2 span {
      color: #16a34a;
    }

    .review-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 24px;
    }

    .review-card {
      padding: 24px;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      background-color: #ffffff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .review-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .review-card p {
      font-size: 1rem;
      color: #475569;
      line-height: 1.6;
      margin-bottom: 16px;
    }

    .user {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .user img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
    }

    .user-info h3 {
      font-size: 1.25rem;
      font-weight: bold;
      color: #1e293b;
      margin-bottom: 4px;
    }

    .stars {
      display: flex;
      gap: 4px;
    }

    .stars span {
      color: #fbbf24;
    }

    @media (max-width: 768px) {
      .review-grid {
      grid-template-columns: 1fr;
      }
    }
    </style>
</head>
<body>
  
  <!-- Hero Section -->
<section class="hero">
  <div class="container hero-content">
    <div>
      <h2>Fresh Groceries, Delivered to Your Door</h2>
      <p>Shop from our wide selection of fresh, high-quality products at great prices.</p>
      <div class="hero-buttons">
        <button class="primary" onclick="scrollToCategories()">Shop Now</button>
        <button class="secondary" onclick="scrollToDeals()">View Deals</button>
      </div>
    </div>
  </div>
</section>


<!-- New About Section -->
<section class="about-section">
  <div class="container">
    <div class="about-content">
      <!-- Left Column: Text -->
      <div class="about-text">
        <h2>About Us</h2>
        <p>
          At Gro-C, we are passionate about providing you with the freshest and highest quality groceries. 
          Our mission is to make healthy living accessible and enjoyable for everyone. 
          From farm to table, we ensure every product meets our strict standards of quality and sustainability.
        </p>
        <button class="primary" onclick="document.querySelector('.review-section').scrollIntoView({ behavior: 'smooth' });">Learn More</button>
      </div>

      <!-- Right Column: Images -->
      <div class="about-images">
        <div class="about-image">
          <img src="images/about1.png" alt="Fresh salad with tomatoes and greens" class="lazy"/>
        </div>
        <div class="about-image">
          <img src="images/about2.png" alt="Fresh salad with tomatoes and greens" class="lazy" />
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Divider -->
<div class="section-divider"></div>

  
  <!-- Categories Section -->
  <section class="categories" id="categories">
  <div class="container">
    <h3>Shop by Category</h3>
    <div class="categories-grid" id="categoriesGrid">
      <a href="products.php?category=fruits" class="category-card">
        <i class="fas fa-apple-alt"></i>
        <h4>Fresh Fruits</h4>
        <p>Seasonal and organic fruits</p>
      </a>
      <a href="products.php?category=vegetables" class="category-card">
        <i class="fas fa-carrot"></i>
        <h4>Vegetables</h4>
        <p>Farm-fresh and pesticide-free</p>
      </a>
      <a href="products.php?category=dairy" class="category-card">
        <i class="fas fa-cheese"></i>
        <h4>Dairy Products</h4>
        <p>Milk, cheese, and more</p>
      </a>
      <a href="products.php?category=bakery" class="category-card">
        <i class="fas fa-bread-slice"></i>
        <h4>Bakery</h4>
        <p>Fresh bread and pastries</p>
      </a>
      <a href="products.php?category=spices" class="category-card">
        <i class="fas fa-pepper-hot"></i>
        <h4>Spices</h4>
        <p>Authentic and aromatic</p>
      </a>
      <a href="products.php?category=beverages" class="category-card">
        <i class="fas fa-coffee"></i>
        <h4>Beverages</h4>
        <p>Juices, teas, and more</p>
      </a>
    </div>
  </div>
</section>
<!-- Divider -->
<div class="section-divider"></div>


<!-- Blog Section -->
<section class="blog" id="blog">
  <h1 class="heading">Our <span>Blog</span></h1>

  <div class="box-container">
    <!-- Blog Post 1 -->
    <div class="box">
      <div class="image">
        <img src="images/blogg1.jpg" alt="Spices" class="lazy">
      </div>
      <div class="content">
        <div class="icons">
          <a href="https://www.instagram.com/nutrihealthsystems">
            <i class="fas fa-user"></i> by Dr. Shikha Sharma
          </a>
        </div>
        <h3>Spices: A Flavorful Fusion</h3>
        <p>You’ve got to try experimenting with spices! They can completely transform your cooking. Just think about how cinnamon adds warmth to desserts or how cumin can bring depth to savory dishes. Mixing different spices can create unforgettable flavors that will wow your taste buds. Trust me, it’s a game changer!</p>
        <a href="https://veritablevegetable.com/blog/" class="btn">Read More</a>
      </div>
    </div>

    <!-- Blog Post 2 -->
    <div class="box">
      <div class="image">
        <img src="images/blogg2.jpg" alt="Fruits" class="lazy">
      </div>
      <div class="content">
        <div class="icons">
          <a href="https://www.rujutadiwekar.com">
            <i class="fas fa-user"></i> by Rujuta Diwekar
          </a>
        </div>
        <h3>Fruits: Nature’s Delights</h3>
        <p>One should dive into the world of fruits! They’re not only refreshing but also loaded with essential nutrients. Consider adding juicy oranges to your salads or indulging in luscious mangoes for dessert. Each fruit brings its own unique taste and health perks. Believe me, embracing more fruits will elevate your meals!</p>
        <a href="https://www.paradise-fruits.de/en/paradise-fruits-blog/" class="btn">Read More</a>
      </div>
    </div>

    <!-- Blog Post 3 -->
    <div class="box">
      <div class="image">
        <img src="images/blogg3.jpg" alt="Milk" class="lazy">
      </div>
      <div class="content">
        <div class="icons">
          <a href="https://www.instagram.com/luke_coutinho">
            <i class="fas fa-user"></i> by Luke Coutinho
          </a>
        </div>
        <h3>The Joy of Milk</h3>
        <p>Exploring dairy products like paneer and milk is a delicious adventure! Rich in calcium and protein, they’re essential for a balanced diet. Creamy paneer can elevate curries, while a glass of chilled milk makes a perfect post-workout refreshment. Incorporating more dairy can truly enhance meals and boost overall health!</p>
        <a href="https://www.youreverydaycook.com/blog/homemade-fresh-paneer-with-milk-powder" class="btn">Read More</a>
      </div>
    </div>
  </div>
</section>
<!-- Divider -->
<div class="section-divider"></div>


  <!-- Review Section -->
  <section class="review-section">
    <div class="container">
      <h2>Customer's <span>Review</span></h2>
      <div class="review-grid">
        <!-- Review 1 -->
        <div class="review-card">
          <p>"Amazing service and I love supporting local vendors while enjoying high-quality groceries delivered right to my doorstep. The convenience is unmatched!"</p>
          <div class="user">
            <img src="images/review-1.png" alt="Aakash Singh" class="lazy" />
            <div class="user-info">
              <h3>Aakash Singh</h3>
              <div class="stars">
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Review 2 -->
        <div class="review-card">
          <p>"They offer an impressive range of products from local farmers, and the delivery is always on time. Highly recommend!"</p>
          <div class="user">
            <img src="images/review-2.png" alt="Rajath Kumari" class="lazy" />
            <div class="user-info">
              <h3>Adithya Maurya</h3>
              <div class="stars">
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Review 3 -->
        <div class="review-card">
          <p>"A lifesaver for busy families! This website has made grocery shopping so easy. Local produce, fair prices, and no more long trips to the store!"</p>
          <div class="user">
            <img src="images/review-3.png" alt="Dilip Prajapati" class="lazy" />
            <div class="user-info">
              <h3>Mangesh Gupta</h3>
              <div class="stars">
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
              </div>
            </div>
          </div>
        </div>
        <div class="review-card">
          <p>The fruits and vegetables are always fresh, and I appreciate how it promotes small local businesses. Great initiative!</p>
          <div class="user">
            <img src="images/review-4.png" alt="Aakash Singh" class="lazy" />
            <div class="user-info">
              <h3>Shivam Divakar</h3>
              <div class="stars">
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Review 2 -->
        <div class="review-card">
          <p>"They offer an impressive range of products from local farmers, and the delivery is always on time. Highly recommend!"</p>
          <div class="user">
            <img src="images/review-5.png" alt="Rajath Kumari" class="lazy" />
            <div class="user-info">
              <h3>Rajath Shetty</h3>
              <div class="stars">
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Review 3 -->
        <div class="review-card">
          <p>"Eco-friendly and community-focused!
            By sourcing locally, they reduce carbon footprint and support the community. Fantastic service and mission!"</p>
          <div class="user">
            <img src="images/review-6.png" alt="Dilip Prajapati" class="lazy" />
            <div class="user-info">
              <h3>Roshini</h3>
              <div class="stars">
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
                <span>⭐</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    // Functions
    function scrollToCategories() {
      document.getElementById("categories").scrollIntoView({ behavior: "smooth" });
    }

    function scrollToDeals() {
      document.getElementById("blog").scrollIntoView({ behavior: "smooth" });
    }
  </script>
  <?php include 'footer.php'; ?>
</body>
</html>
