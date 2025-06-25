<?php
require 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'founder') {
    header("Location: founder_login.php");
    exit();
}

$founder_id = $_SESSION['user_id'];

// Fetch all developers for dropdown
$dev_query = "SELECT id, name FROM developers";
$dev_stmt = $conn->prepare($dev_query);
$dev_stmt->execute();
$developers = $dev_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_id = $_POST['developer_id'] ?? null;
    $message = trim($_POST['message'] ?? '');

    if ($receiver_id && !empty($message)) {
        $msg_query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)";
        $msg_stmt = $conn->prepare($msg_query);
        $msg_stmt->execute([
            ':sender_id' => $founder_id,
            ':receiver_id' => $receiver_id,
            ':message' => $message
        ]);
        $success = "Message sent successfully!";
    } else {
        $error = "Please select a developer and enter a message.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
       /* Modern UI Styling */
body {
    font-family: 'Poppins', sans-serif;
    background: #f4f4f4;
    color: #000;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    background: rgba(255, 255, 255, 0.15);
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    width: 800px;
    text-align: center;
    backdrop-filter: blur(10px);
}

h2 {
    margin-bottom: 15px;
    color: #000;
    font-size: 1.8rem;
}

/* Form Elements */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    text-align: left;
    font-weight: bold;
    font-size: 1rem;
    color:rgb(24, 24, 24);
}

input, select, textarea, button {
    width: 95%;
    padding: 10px;
    font-size: 1rem;
    border-radius: 8px;
   
    outline: none;
}

select, input, textarea {
    background: rgba(255, 255, 255, 0.2);
    color: #000;
    border:1px `rgb(255, 255, 255);
    transition: all 0.3s ease;
}

input::placeholder, textarea::placeholder {
    color: #000;
}

select:hover, input:hover, textarea:hover {
    background: rgba(255, 255, 255, 0.3);
}

textarea {
    height: 100px;
    resize: none;
}

/* Button Styles */
.send-btn {
    background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
    color: #fff;
    font-weight: bold;
    border: none;
    cursor: pointer;
    margin-left:20px;
    transition: transform 0.2s ease-in-out, background 0.3s;
}

.send-btn:hover {
    background:linear-gradient(45deg,rgb(0, 0, 0),rgb(19, 16, 153));
    transform: scale(1.05);
}

/* Message Alerts */
.message {
    padding: 12px;
    border-radius: 6px;
    font-size: 1rem;
    margin-bottom: 10px;
    animation: fadeIn 0.5s ease-in;
}

.success {
    background: #4CAF50;
    color: #fff;
}

.error {
    background: #FF5252;
    color: #fff;
}

/* Smooth Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.backk-button {
           background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
            color: white;
            padding: 5px 8px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
            position: absolute;
            top: 20px;
            left:20px;
        }
        .backk-button:hover {
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(15, 39, 170));
        }  

    </style>
</head>
<body>

    <div class="container">
    <a href="founder_dashboard.php" class="backk-button"><i class="bi bi-box-arrow-left"></i> Let's go</a>

        <h2>Send a Message</h2>

        <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p class='message success'>$success</p>"; ?>

        <form method="POST">
            <label for="search">Search Developer:</label>
            <input type="text" id="search" class="search-box" placeholder="Type to search...">

            <label for="developer">Select Developer:</label>
            <select name="developer_id" id="developer-select" required>
                <option value="">-- Select Developer --</option>
                <?php foreach ($developers as $dev) : ?>
                    <option value="<?= $dev['id']; ?>"><?= htmlspecialchars($dev['name']); ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="message">Message:</label>
            <textarea name="message" placeholder="Type your message..." required></textarea>

            <button type="submit" class="send-btn">Send Message</button>
        </form>
    </div>

    <script>
        document.getElementById("search").addEventListener("input", function () {
            let searchValue = this.value.toLowerCase();
            let select = document.getElementById("developer-select");
            let options = select.getElementsByTagName("option");

            for (let i = 1; i < options.length; i++) {
                let optionText = options[i].textContent.toLowerCase();
                if (optionText.includes(searchValue)) {
                    options[i].style.display = "block";
                } else {
                    options[i].style.display = "none";
                }
            }
        });
    </script>

</body>
</html>
