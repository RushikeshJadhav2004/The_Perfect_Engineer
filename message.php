<?php
require 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'developer') {
    header("Location: login.php");
    exit();
}

$developer_id = $_SESSION['user_id']; 
$founder_id = isset($_GET['to']) ? intval($_GET['to']) : 0; 
if ($founder_id === 0) {
    echo "<script>
        alert('You have not selected any founder to chat with. Please go to the Founder Browse page and select a founder.');
        window.location.href = 'developer_dashboard.php';
    </script>";
    exit();
}


// Fetch founder details
try {
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE id = :founder_id AND role = 'founder'");
    $stmt->execute([':founder_id' => $founder_id]);
    $founder = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$founder) {
        die("Founder not found.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_id = $_POST['receiver_id'] ?? null;
    $message = trim($_POST['message'] ?? '');

    if ($receiver_id && !empty($message)) {
        $sender_id = $_SESSION['user_id'];

        $msg_query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)";
        $msg_stmt = $conn->prepare($msg_query);
        $msg_stmt->execute([
            ':sender_id' => $sender_id,
            ':receiver_id' => $receiver_id,
            ':message' => $message
        ]);

        header("Location: message.php?to=" . $founder_id); // Refresh to update chat
        exit();
    }
}

// Fetch conversation history
try {
    $stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id = :developer_id AND receiver_id = :founder_id) 
                            OR (sender_id = :founder_id AND receiver_id = :developer_id) ORDER BY timestamp ASC");
    $stmt->execute([
        ':developer_id' => $developer_id,
        ':founder_id' => $founder_id
    ]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($founder['name']); ?></title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}



body {
    background: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.chat-container {
    width: 1000px;
    background: #fff;
    border: 1px solid #000;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

header {
    background: linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
    color: #fff;
    padding: 15px;
    text-align: center;
    position: relative;
}

.back-btn {
    position: absolute;
    left: 15px;
    top: 15px;
    text-decoration: none;
    color: white;
    font-size: 18px;
}

.chat-box {
    padding: 15px;
    height: 400px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: white;
}

.message {
    padding: 10px 15px;
    max-width: 80%;
    border-radius: 15px;
    font-size: 14px;
    position: relative;
    word-wrap: break-word;
}

.sent {
    background: linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
    color: white;
    align-self: flex-end;
    border-bottom-right-radius: 0;
}

.received {
    background: #e5e5e5;
    color: #333;
    align-self: flex-start;
    border-bottom-left-radius: 0;
}

.message span {
    font-size: 10px;
    margin-top: 5px;
    display: block;
    text-align: right;
    opacity: 0.7;
}

.no-messages {
    text-align: center;
    color: #777;
    margin-top: 20px;
}

.message-form {
    display: flex;
    padding: 10px;
    background: #fff;
    border-top: 1px solid #ddd;
}

textarea {
    flex: 1;
    padding: 10px;
    border-top: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    resize: none;
    outline: none;
    background: #f0f0f0;
}

button {
    background: linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
    color: white;
    border: none;
    padding: 10px 15px;
    margin-left: 10px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: linear-gradient(45deg,rgb(0, 0, 0),rgb(15, 24, 155));
}

</style>
<body>

    <div class="chat-container">
        <header>
            <h2>Chat with <?php echo htmlspecialchars($founder['name']); ?></h2>
            <a href="browse.php" class="back-btn"><i class="bi bi-box-arrow-left"></i> Let's go</a>
        </header>

        <div class="chat-box" id="chatBox">
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="message <?php echo ($msg['sender_id'] == $developer_id) ? 'sent' : 'received'; ?>">
                        <p><?php echo htmlspecialchars($msg['message']); ?></p>
                        <span><?php echo date("d M, H:i", strtotime($msg['timestamp'])); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-messages">No messages yet. Start the conversation!</p>
            <?php endif; ?>
        </div>

        <form method="POST" class="message-form">
            <input type="hidden" name="receiver_id" value="<?php echo $founder_id; ?>">
            <textarea name="message" placeholder="Type your message..." required></textarea>
            <button type="submit">Send</button>
        </form>
    </div>

    <script>
        // Scroll to the bottom of the chat automatically
        document.getElementById("chatBox").scrollTop = document.getElementById("chatBox").scrollHeight;
    </script>

</body>
</html>
