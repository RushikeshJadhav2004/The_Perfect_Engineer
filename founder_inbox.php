<?php
require 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'founder') {
    header("Location: founder_login.php");
    exit();
}

$founder_id = $_SESSION['user_id'];

// Fetch messages sent to the logged-in founder
$query = "SELECT m.id, m.message, m.timestamp, d.name AS sender_name, m.sender_id 
          FROM messages m
          JOIN developers d ON m.sender_id = d.id
          WHERE m.receiver_id = :founder_id 
          ORDER BY m.timestamp DESC";

$stmt = $conn->prepare($query);
$stmt->execute([':founder_id' => $founder_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_message'])) {
    $reply_to = $_POST['reply_to'];
    $reply_message = trim($_POST['reply_message']);
    
    if (!empty($reply_message)) {
        $reply_query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)";
        $reply_stmt = $conn->prepare($reply_query);
        $reply_stmt->execute([
            ':sender_id' => $founder_id,
            ':receiver_id' => $reply_to,
            ':message' => $reply_message
        ]);
        header("Location: founder_inbox.php"); 
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Founder Inbox</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
            color: #000;
            text-align: center;
            padding: 20px;
            scroll-behavior: smooth;
        }
        
        .inbox-container {
            max-width: 800px;
            margin: auto;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        h2 {
            margin-bottom: 20px;
            font-size: 28px;
            color:rgb(0, 0, 0);
        }

        .message-box {
            background: rgba(255, 255, 255, 0.2);
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
            text-align: left;
        }

        .message-box:hover {
            transform: translateY(-5px);
        }

        .message-box p {
            margin: 8px 0;
        }

        .reply-btn {
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
            color: #333;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .reply-btn:hover {
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(18, 59, 170));
        }

        .reply-form {
            display: none;
            margin-top: 10px;
        }

        textarea {
            width: 90%;
            height: 60px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #000;
            background: rgba(255, 255, 255, 0.3);
            color: gray;
            font-size: 14px;
            resize: none;
        }

        .send-btn {
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
            transition: background 0.3s ease;
        }

        .send-btn:hover {
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(25, 15, 161));
        }

        /* Buttons */
        .reply-btn {
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
            color: white;
            border:1px solid black;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .reply-btn:hover {
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(27, 29, 151));
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
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(17, 19, 139));
            text-decoration: none;
        } 
    </style>
</head>
<body>
    <div class="inbox-container">
    <a href="founder_dashboard.php" class="backk-button"><i class="bi bi-box-arrow-left"></i> Let's go</a>
        <h2>📩 Founder Inbox</h2>
        
        <?php foreach ($messages as $msg) : ?>
            <div class="message-box">
                <p><strong>👤 From: <?= htmlspecialchars($msg['sender_name']); ?></strong></p>
                <p>📝 <?= htmlspecialchars($msg['message']); ?></p>
                <p><small>⏳ <?= $msg['timestamp']; ?></small></p>
                
                <button class="reply-btn" onclick="showReplyForm('reply-form-<?= $msg['id']; ?>')">💬 Reply</button>
                
                <form method="POST" class="reply-form" id="reply-form-<?= $msg['id']; ?>">
                    <input type="hidden" name="reply_to" value="<?= $msg['sender_id']; ?>">
                    <textarea name="reply_message" required placeholder="Type your reply..."></textarea>
                    <button type="submit" class="send-btn">📤 Send Reply</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    
    <script>
        function showReplyForm(id) {
            document.getElementById(id).style.display = 'block';
        }
    </script>
</body>
</html>
