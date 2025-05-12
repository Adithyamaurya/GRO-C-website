<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Login successful
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['address'] = $user['address'];
            $_SESSION['userloginnoti'] = 1;
            setcookie("userid", $user['id'], time() + (300 * 24 * 60 * 60), '/');
            echo "<script>
            localStorage.setItem('isLoggedIn', 'true');   
            window.location.href='index.php';         
            </script>";
            // Store cart data into the database if it exists in the session variable.
          if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
              $product_id = $item['product_id'];
              $quantity = $item['quantity'];

              $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)
                      ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";
              $stmt = $conn->prepare($sql);
              $stmt->bind_param("iii", $_SESSION['user_id'], $product_id, $quantity);
              $stmt->execute();
            }
        }
        } else {
            echo "<script>
            alert('Wrong Password!!!');
            window.location.href='login.php';
            </script>";
        }
    } else {
        echo "<script>
        alert('Email not found!!!');
        window.location.href='login.php';
        </script>";
    }
}
?>

<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GRO-C - Login/Signup</title>
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
    /* Auth Section (Modified for Image Integration) */
    .auth-section {
      padding: 40px 0;
    }
    .auth-section h3 {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 24px;
    }

    .auth-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      align-items: center;
    }
    .auth-image img {
      width: 100%;
      height: auto;
      border-radius: 8px;
      }

    .auth-form {
      animation: fadeIn 0.5s ease-in-out;
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 24px;
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .auth-form h2 {
      font-size: 1.75rem;
      font-weight: bold;
      margin-bottom: 16px;
      color: #16a34a; 
    }

    .auth-form input {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #838586;
      border-radius: 4px;
      margin-bottom: 16px;
    }

    .auth-form button {
      
      padding: 12px 24px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-size: 1rem;
    }

    .auth-form button#resigisterButton,#loginButton{
      background-color: #16a34a;
      color: #ffffff;
    }

    .auth-form button#loginButton:hover,#resigisterButton:hover {
      background-color: #15803d;
    }

    .auth-form button#resigisterButton,#loginButton[data-mode="signup"] {
      background-color: #16a34a;
    }

    .auth-form button#resigisterButton,#loginButton[data-mode="signup"]:hover {
      background-color: #15803d; 
    }

    .auth-form .toggle-auth {
      margin-top: 16px;
      font-size: 0.875rem;
    }

    .auth-form .toggle-auth a {
      color: #16a34a;
      text-decoration: none;
    }

    .auth-form .toggle-auth a:hover {
      text-decoration: underline;
    }

    /* Seller Auth Button */
    .auth-form .seller-auth-button {
      width: 100%;
      padding: 12px 24px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-size: 1rem;
      background-color:rgb(255, 255, 255); 
      color: #16a34a ;
      margin-top: 16px;
    }

    .auth-form .seller-auth-button:hover {
     color: #15803d; /* Darker green on hover */
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .auth-content {
        grid-template-columns: 1fr;
        gap: 40px;
      }

      .auth-image {
        display: none; /* Hide the image on smaller screens */
      }
    }
  </style>
</head>
<body style="background-color:rgb(199, 223, 206)  ;">
  

  <!-- Login/Signup Section -->
  <section class="auth-section">
    <div class="container">
      <h3>Login / Signup</h3>
      <div class="auth-content">
        <div class="auth-image">
          <img src="images/seller.png" alt="Login Image" />
        </div>
        <div class="auth-form" id="authForm">
          <h2 id="authTitle">User Login</h2>

          <form action="login.php" method="POST" id="loginForm">
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit" id="loginButton">Login</button>
          </form>
          
          <!-- Separate form for registration -->
          <form action="register.php" method="POST" id="registerForm" style="display: none;">
            <input type="text" name="username" placeholder="Username" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <input type="password" name="confirm_password" placeholder="Confirm Password" required />
            <input type="text" name="address" placeholder="Address" required />
            <button type="submit" id="resigisterButton">Sign Up</button>
          </form>

          <div class="toggle-auth">
            <span id="toggleText">Don't have an account? </span>
            <a href="#" id="toggleAuth">Sign Up</a>
          </div>
          <a href="seller_login.php" class="seller-auth-button">
            Seller Login/Signup
          </a>
        </div>
      </div>
    </div>
  </section>

  <script>
    const toggleAuth = document.getElementById('toggleAuth');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const authTitle = document.getElementById('authTitle');
    const toggleText = document.getElementById('toggleText');

    toggleAuth.addEventListener('click', (e) => {
      e.preventDefault();
      const isLogin = loginForm.style.display !== 'none';
      
      if (isLogin) {
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
        authTitle.textContent = 'User Registration';
        toggleText.textContent = 'Already have an account? ';
        toggleAuth.textContent = 'Login';
      } else {
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
        authTitle.textContent = 'User Login';
        toggleText.textContent = "Don't have an account? ";
        toggleAuth.textContent = 'Sign Up';
      }
    });
  </script>
  <?php include 'footer.php'; ?>

</body>
</html>
