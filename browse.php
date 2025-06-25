<?php
require 'db_connect.php';
session_start();

// Fetch all founders from the database
try {
    $sql = "SELECT id, name, email, company_name, role_description, required_tech_stack FROM users WHERE role = 'founder' ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $founders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Founders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Global Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
            color: black;
            text-align: center;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 40px;
            margin-top:50px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Founder Cards - Glassmorphism Effect */
        .founders-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .founder-card {
            background: rgb(39, 37, 37);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: left;
            color: white;
            
        }

        .founder-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
        }

        .founder-card h3 {
            margin: 0 0 10px;
            font-size: 1.4rem;
            color: #f3f3f3;
        }

        .founder-card p {
            margin: 5px 0;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        /* Message Button - Neon Glow Effect */
        .message-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 18px;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
            background: linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
            color: white;
            text-decoration: none;
            border-radius: 50px;
            transition: 0.3s;
            box-shadow: 0 0 8px rgba(255, 75, 43, 0.8);
        }

        .message-btn:hover {
            background: linear-gradient(45deg,rgb(0, 0, 0),rgb(25, 23, 146));
            box-shadow: 0 0 16px rgb(20, 18, 18);
            transform: scale(1.05);
        }

        /* Back Button */
        .back-button {
            background: linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
            box-shadow: 0 0 8px rgba(0, 114, 255, 0.8);
            margin-bottom: 20px;
            position: absolute;
            top: 30px;
            left:30px;
        }

        .back-button:hover {
            background: linear-gradient(45deg,rgb(0, 0, 0),rgb(23, 15, 143));
            box-shadow: 0 0 16px rgb(8, 8, 8);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="developer_dashboard.php" class="back-button"><i class="bi bi-box-arrow-left"></i> Let's go</a>
        <h2>Browse Founders</h2>

        <div class="founders-list">
            <?php if (!empty($founders)): ?>
                <?php foreach ($founders as $founder): ?>
                    <div class="founder-card">
                        <h3><?php echo htmlspecialchars($founder['name']); ?></h3>
                        <p><strong>Company:</strong> <?php echo htmlspecialchars($founder['company_name']); ?></p>
                        <p><strong>Role:</strong> <?php echo htmlspecialchars($founder['role_description'] ?? 'Not specified'); ?></p>
                        <p><strong>Tech Stack:</strong> <?php echo htmlspecialchars($founder['required_tech_stack'] ?? 'Not specified'); ?></p>
                        <a href="message.php?to=<?php echo $founder['id']; ?>" class="message-btn">Message</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No founders registered yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
