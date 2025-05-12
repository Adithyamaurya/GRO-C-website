<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match'); window.location.href='seller_login.php';</script>";
        exit();
    }

    $check_sql = "SELECT * FROM sellers WHERE email = '$email'";
    $check_stmt = $conn->query($check_sql);

    if ($check_stmt->num_rows > 0) {
        echo "<script>alert('Email already exists'); 
        window.location.href='seller_login.php';
        </script>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new seller
    $sql = "INSERT INTO sellers (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    session_start();
    $_SESSION['seller']=$username;

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Account created successfully'); 
        window.location.href='index.php';
        window.sellerlogged=1;
        localStorage.setItem('sellerlogged',window.sellerlogged);
        </script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "'); window.location.href='seller_login.php';</script>";
    }
}
?>