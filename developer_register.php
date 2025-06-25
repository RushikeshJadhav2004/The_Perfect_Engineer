<?php
require 'db_connect.php';
session_start();

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); // Clear message after displaying

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $role = $_POST['role'];

    if (empty($name) || empty($email) || empty($password) || !in_array($role, ['founder', 'developer'])) {
        $_SESSION['message'] = "<div class='error-message'>⚠️ All fields are required!</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "<div class='error-message'>⚠️ Invalid email format!</div>";
    } elseif (strlen($password) < 6) {
        $_SESSION['message'] = "<div class='error-message'>⚠️ Password must be at least 6 characters!</div>";
    } else {
        try {
            // Check if email exists in users or developers table
            $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = :email 
                                          UNION 
                                          SELECT id FROM developers WHERE email = :email");
            $checkEmail->execute([':email' => $email]);

            if ($checkEmail->rowCount() > 0) {
                $_SESSION['message'] = "<div class='error-message'>⚠️ Email already registered! Try logging in.</div>";
            } else {
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                if ($role === 'founder') {
                    // Insert into users table
                    $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':name' => $name,
                        ':email' => $email,
                        ':password' => $hashedPassword,
                        ':role' => $role
                    ]);
                } else {
                    // Insert into developers table (without role column)
                    $sql = "INSERT INTO developers (name, email, password) VALUES (:name, :email, :password)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':name' => $name,
                        ':email' => $email,
                        ':password' => $hashedPassword
                    ]);
                }

                $_SESSION['message'] = "<div class='success-message'>🎉 Registration successful! <a href='developer_login.php'>Login here</a></div>";
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "<div class='error-message'>⚠️ Database error: " . $e->getMessage() . "</div>";
        }
    }
    header("Location: register.php");
    exit();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            /* background: #f4f4f4; */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('uploads/glass.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
        }

        .success-message, .error-message {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    padding: 12px;
    border-radius: 6px;
    width: 100%;
    margin-bottom: 15px;
}

.success-message {
    background: #d1e7dd;
    color: #0f5132;
    border: 1px solid #badbcc;
}

.error-message {
    background: #f8d7da;
    color: #842029;
    border: 1px solid #f5c2c7;
}



        .register-container {
            /* background-color: #fff; */
            padding: 30px;
            border-radius: 70px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            color: #fff;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        input, select {
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
            background:rgb(54, 42, 42);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: 0.3s;
        }

        button:hover {
            background:rgb(0, 0, 0);
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

        /* p a:hover {
            text-decoration: underline;
        } */

        .back-button {
    position: absolute;
    top: 20px;
    left: 20px;
    background: linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
    color: white;
    font-size: 16px;
    font-weight: bold;
    padding: 12px 20px;
    border-radius: 30px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.back-button:hover {
    background: linear-gradient(45deg,rgb(0, 15, 41), #6a11cb);
    transform: scale(1.1);
}

    </style>
</head>
<body>
<a href="index.php" class="back-button">
<i class="bi bi-box-arrow-left"></i> Back
</a>

<div class="register-container">
    <h2>Welcome Developer !!!</h2>

    <!-- Show message only inside the container -->
    <?php if (!empty($message)) echo $message; ?>

    <form method="POST" action="">
        <div class="input-group">
            <input type="text" name="name" placeholder="Full Name" required>
        </div>
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="input-group">
            <select name="role" required>
                <!-- <option value="founder">Founder</option> -->
                <option value="developer">Developer</option>
            </select>
        </div>
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="developer_login.php">Login here</a></p>
</div>

</body>
</html>



