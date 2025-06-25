<?php
require 'db_connect.php';
session_start();

// Check if the user is a founder
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'founder') {
    header("Location: login.php");
    exit();
}

// Fetch all developers with skills and education
try {
    $stmt = $conn->prepare("SELECT id, name, email, experience, portfolio, profile_picture, skills, education FROM developers ORDER BY id DESC");
    $stmt->execute();
    $developers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Developers</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .developer-card {
            display: flex;
            align-items: center;
            background: #fafafa;
            margin: 10px 0;
            padding: 15px;
            border: 1px solid #000;
            border-radius: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .developer-card img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }
        .developer-info {
            flex: 1;
        }
        .developer-info h3 {
            margin: 0;
            color: #333;
        }
        .developer-info p {
            margin: 5px 0;
            color: #666;
        }
        .btn {
            padding: 8px 12px;
            text-decoration: none;
            color: white;
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
            border-radius: 5px;
            transition: 0.3s;
        }
        /* .btn:hover {
            background: #0056b3;
        } */

        .back-button {
    background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
            color: white;
            padding: 5px 8px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .back-button:hover {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(17, 19, 139));
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
<a href="founder_dashboard.php" class="back-button"><i class="bi bi-box-arrow-left"></i> Let's go</a>
    <h2>Browse Developers</h2>
    <?php if (!empty($developers)): ?>
        <?php foreach ($developers as $developer): ?>
            <div class="developer-card">
                <img src="<?= $developer['profile_picture'] ?: 'default_profile.png' ?>" alt="Profile">
                <div class="developer-info">
                    <h3><?= htmlspecialchars($developer['name']) ?></h3>
                    <p><strong>Experience:</strong> <?= htmlspecialchars($developer['experience']) ?> years</p>
                    <p><strong>Skills:</strong> <?= htmlspecialchars($developer['skills'] ?: 'Not specified') ?></p>
                    <p><strong>Education:</strong> <?= htmlspecialchars($developer['education'] ?: 'Not specified') ?></p>
                    <p><strong>Portfolio:</strong> <a href="<?= htmlspecialchars($developer['portfolio']) ?>" target="_blank">View</a></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($developer['email']) ?></p>
                </div>
                <a href="mailto:<?= htmlspecialchars($developer['email']) ?>" class="btn">Contact</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No developers found.</p>
    <?php endif; ?>
</div>

</body>
</html>
