<?php
require 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'developer') {
    header("Location: developer_login.php");
    exit();
}

$developer_id = $_SESSION['user_id'];

// Fetch messages where the logged-in developer is the receiver
$query = "SELECT m.id, m.sender_id, m.message, u.name AS sender_name, m.timestamp 
          FROM messages m
          JOIN users u ON m.sender_id = u.id
          WHERE m.receiver_id = :developer_id
          ORDER BY m.timestamp DESC";

$stmt = $conn->prepare($query);
$stmt->execute([':developer_id' => $developer_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle replies
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_message'])) {
    $reply_to = $_POST['reply_to'];
    $reply_message = trim($_POST['reply_message']);
    
    if (!empty($reply_message)) {
        $reply_query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)";
        $reply_stmt = $conn->prepare($reply_query);
        $reply_stmt->execute([
            ':sender_id' => $developer_id,
            ':receiver_id' => $reply_to,
            ':message' => $reply_message
        ]);
        
        header("Location: developer_inbox.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Inbox</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
            color: #000;
            text-align: center;
            padding: 20px;
        }
        
        .inbox-container {
            max-width: 800px;
            margin: auto;
            background: rgb(255, 255, 255);
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

        /* Message Box */
        .message-box {
            background: rgba(255, 255, 255, 0.2);
            border: none;
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

        /* Reply Form */
        .reply-form {
            display: none;
            margin-top: 10px;
        }

        textarea {
            width: 90%;
            height: 60px;
            padding: 10px ;
            border-radius: 8px;
            border: 1px solid #000;
            background: rgba(255, 255, 255, 0.3);
            color: #000;
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
        .send{
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
        }

        .send-btn:hover {
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(22, 24, 158));
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
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(13, 10, 167));
        }  
    </style>
</head>
<body>
    <div class="inbox-container">
    <a href="developer_dashboard.php" class="backk-button"><i class="bi bi-box-arrow-left"></i> Let's go</a>
        <h2>📩 Developer Inbox</h2>
        
        <?php foreach ($messages as $msg) : ?>
            <div class="message-box">
                <p><strong>👤 From: <?= htmlspecialchars($msg['sender_name']); ?></strong></p>
                <p>📝 <?= htmlspecialchars($msg['message']); ?></p>
                <p><small>⏳ <?= $msg['timestamp']; ?></small></p>
                
                <button class="reply-btn" onclick="showReplyForm('reply-form-<?= $msg['id']; ?>')">💬 Reply</button>
                
                <form method="POST" class="reply-form" id="reply-form-<?= $msg['id']; ?>">
                    <input type="hidden" name="reply_to" value="<?= $msg['sender_id']; ?>">
                    <textarea name="reply_message" required placeholder="Type your reply..."></textarea>
                    <button type="submit" class="send-btn send">📤 Send Reply</button>
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
