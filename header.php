<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<header>
  <div class="container header-content">
    <div class="logo">
      <span>🍏</span>
      <span>GRO-C</span>
    </div>
    <nav>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </nav>

    <div class="search-container">
      <form method="GET" action="products.php">
        <input type="text" name="query" placeholder="Search products..." required>
        <button type="submit"><i class="fa fa-search"></i></button>
      </form>
    </div>

    <div class="user-actions">
      <?php if (isset($_SESSION['user'])): ?>
        <button onclick="window.location.href='dashboard.php'" class="login-button">Profile</button>
      <?php elseif (isset($_SESSION['seller'])): ?>
        <button onclick="window.location.href='seller_dashboard.php'" class="login-button">Dashboard</button>
        <button class="cart-button" onclick="window.location.href='sell.php'">🛒 Sell</button>
      <?php else: ?>
        <button onclick="window.location.href='login.php'" class="login-button">Login</button>
      <?php endif; ?>
      
      <?php if (!isset($_SESSION['seller'])): ?>
        <button class="cart-button" onclick="window.location.href='cart.php'">
          🛒 Cart
          <span class="badge" id="cartCount">
            <?php
            if (isset($_SESSION['cart'])) {
              echo count($_SESSION['cart']);
            } else {
              echo 0;
            }
            ?>
          </span>
        </button>
      <?php endif; ?>

      <?php if (isset($_SESSION['user']) && $_SESSION['email'] == 'admin_groc@gmail.com'): ?>
        <script>window.location.href='admin_dashboard.php';</script>
      <?php endif; ?>

    </div>
    <?php if (isset($_SESSION['user'])): ?>
    <div style="position: fixed; bottom: 30px; right: 30px; border-radius: 50%;">
      <button class="menu-button" onclick="window.location.href='groot.php'" style="font-size: 24px; background-color: #fff; border: 2px solid #ccc;border-radius: 10%; padding: 10px; z-index: 2; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        🍏
      </button>
   
    </div> 
    <?php endif; ?>
  </div>
</header>
