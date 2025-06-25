<?php
require 'db_connect.php';
session_start();

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); // Clear message after displaying

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['message'] = "<div class='error-message'>⚠️ Email and Password are required!</div>";
    } else {
        try {
            // Fetch developer separately
            $stmt = $conn->prepare("SELECT id, name, password FROM developers WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $developer = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($developer && password_verify($password, $developer['password'])) {
                // Set session for developer
                $_SESSION['user_id'] = $developer['id'];
                $_SESSION['name'] = $developer['name'];
                $_SESSION['role'] = 'developer'; 
                $_SESSION['developer_id'] = $developer['id']; 

                header("Location: developer_dashboard.php");
                exit();
            } else {
                $_SESSION['message'] = "<div class='error-message'>⚠️ Invalid email or password!</div>";
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "<div class='error-message'>⚠️ Database error: " . $e->getMessage() . "</div>";
        }
    }
    header("Location: login.php");
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('uploads/glass2.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            color: #fff;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
            padding-right: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: rgb(80, 80, 80);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: 0.3s;
        }

        button:hover {
            background: rgb(0, 0, 0);
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .back-button i {
            margin-right: 5px;
        }

        .back-button:hover {
            background: linear-gradient(45deg, rgb(0, 15, 41), #6a11cb);
            transform: scale(1.1);
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        p a {
            color: #6a11cb;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<a href="index.php" class="back-button">
    <i class="bi bi-box-arrow-left"></i> Back
</a>

<div class="login-container">
    <h2>Developer Login  &nbsp;<i class="fa-solid fa-user-secret"></i></h2>
    <form method="POST" action="">
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>

</body>
</html>
