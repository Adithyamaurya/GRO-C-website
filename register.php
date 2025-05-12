
<?php
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST"){     
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);
    $address = $conn->real_escape_string($_POST['address']);

    // Validate passwords match
    if ($password !== $confirm_password) {
        header("Location: login.html?register_error=Passwords do not match");
        exit();
    }

    // Check if email already exists
    $check_sql = "SELECT * FROM users WHERE email = '$email'";
    $check_stmt = $conn->query($check_sql);
    
    if ($check_stmt->num_rows > 0) {
        echo "<script>
        alert('Username Already Exists');
        window.location.href='login.php';
        </script>";
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $sql = "INSERT INTO users (username, email, password, address) VALUES ( '$username', '$email', '$hashed_password', '$address')";
    session_start();
    $_SESSION['user']=$username;

    if($conn->query($sql)===TRUE){

        echo "<script>
        alert('Account Created Successfully, LOGIN to access!!!');
        window.location.href='index.php';
        window.userlogged=1;
        localStorage.setItem('userlogged',window.userlogged);
        </script>";
       
        }  
        else{
            echo "Error ".$sql.$conn->error;
        }
    
}
?>
