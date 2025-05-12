<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Check if email exists
    $check_sql = "SELECT * FROM sellers WHERE email = '$email'";
    $check_stmt = $conn->query($check_sql);

    if ($check_stmt->num_rows > 0) {
        $user = $check_stmt->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['seller_id'] = $user['id'];
            $_SESSION['seller'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['address'] = $user['address'];
            echo "<script>
            localStorage.setItem('isLoggedIn', 'true');
            window.location.href='index.php';
            </script>";
        } else {
            echo "<script>
            alert('Invalid password'); 
            window.location.href='seller_login.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found'); window.location.href='seller_login.php';</script>";
    }
}
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GRO-C - Seller Login/Signup</title>
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

    .auth-form button#authButton {
      background-color: #16a34a;
      color: #ffffff;
    }

    .auth-form button#authButton:hover {
      background-color: #15803d;
    }

    .auth-form button#authButton[data-mode="signup"] {
      background-color: #16a34a;
    }

    .auth-form button#authButton[data-mode="signup"]:hover {
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

    .auth-form .customer-auth-button {
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

    .auth-form .customer-auth-button:hover {
     color: #15803d; 
    }

    @media (max-width: 768px) {
      .auth-content {
        grid-template-columns: 1fr;
        gap: 40px;
      }

      .auth-image {
        display: none; 
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
          <img src="images/seller.png" alt="Seller Login Image" />
        </div>
        <div class="auth-form" id="authForm">
          <h2 id="authTitle">Seller Login</h2>

          <!-- Login Form -->
          <form action="seller_login.php" method="POST" id="loginForm">
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit" id="authButton" data-mode="login">Login</button>
          </form>

          <!-- Registration Form -->
          <form action="seller_register.php" method="POST" id="registerForm" style="display: none;">
            <input type="text" name="username" placeholder="Username" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <input type="password" name="confirm_password" placeholder="Confirm Password" required />
            <button type="submit" id="authButton" data-mode="signup">Sign Up</button>
          </form>
          
          <div class="toggle-auth">
            <span id="toggleText">Don't have an account? </span>
            <a href="#" id="toggleLink" onclick="toggleAuth()">Sign Up</a>
          </div>
          <button class="customer-auth-button" onclick="window.location.href='login.php'">
            Customer Login/Signup
          </button>
        </div>
      </div>
    </div>
  </section>

  <script>
    // Auth State
    let isLogin = true;
    const authTitle = document.getElementById("authTitle");
    const authButton = document.getElementById("authButton");
    const toggleText = document.getElementById("toggleText");
    const toggleLink = document.getElementById("toggleLink");
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");

    // Toggle between Login and Signup
    function toggleAuth() {
      isLogin = !isLogin;
      authTitle.textContent = isLogin ? "Seller Login" : "Sign Up";
      authButton.textContent = isLogin ? "Login" : "Sign Up";
      toggleText.textContent = isLogin ? "Don't have an account? " : "Already have an account? ";
      toggleLink.textContent = isLogin ? "Sign Up" : "Login";
      loginForm.style.display = isLogin ? "block" : "none";
      registerForm.style.display = isLogin ? "none" : "block";
    }
  </script>
  <?php include 'footer.php'; ?>
</body>
</html>