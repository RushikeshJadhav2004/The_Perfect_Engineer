<?php
session_start();
require 'db_connect.php';

// Ensure only founders can view rejected applications
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'founder') {
    die("Access denied.");
}

// Fetch rejected applications only for the logged-in founder
$stmt = $conn->prepare("SELECT da.*, d.name AS developer_name, si.title AS idea_title 
                        FROM developer_applications da
                        JOIN developers d ON da.developer_id = d.id
                        JOIN startup_ideas si ON da.idea_id = si.id
                        WHERE da.status = 'rejected' AND si.founder_id = :founder_id
                        ORDER BY da.applied_at DESC");

$stmt->bindParam(':founder_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$rejectedApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejected Applications</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
        function removeApplication(applicationId) {
            if (confirm("Are you sure you want to remove this application?")) {
                fetch('remove_application.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'application_id=' + applicationId
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        document.getElementById("app-" + applicationId).remove();
                    } else {
                        alert("Error removing application.");
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #d9534f;
}

.application-card {
    background: #fff;
    padding: 15px;
    margin: 15px 0;
    border-left: 5px solid #d9534f;
    border-radius: 5px;
    border-top:1px solid rgb(0, 0, 0);
    border-right:1px solid rgb(0, 0, 0);
    border-bottom:1px solid rgb(0, 0, 0);
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.developer {
    color: #888;
    font-size: 14px;
    margin-bottom: 5px;
}

.remove-btn {
    background:linear-gradient(45deg,rgb(0, 0, 0),rgb(10, 34, 170));
    color: white;
    border: none;
    padding: 8px 12px;
    margin-top: 10px;
    cursor: pointer;
    border-radius: 4px;
    transition: 0.3s;
}

.remove-btn:hover {
    background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
}

.backk-button {
           background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
            color: white;
            padding: 5px 8px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 10px;
            
        }
        .backk-button:hover {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(17, 19, 139));
            text-decoration: none;
        } 

</style>
<body>
    <div class="container">
    <a href="founder_dashboard.php" class="backk-button"><i class="bi bi-box-arrow-left"></i> Let's go</a>

        <h1>❌ Rejected Applications</h1>
        
        <?php if (empty($rejectedApplications)): ?>
            <p>No rejected applications.</p>
        <?php else: ?>
            <?php foreach ($rejectedApplications as $app): ?>
                <div class="application-card" id="app-<?= $app['id'] ?>">
                    <h2><?= htmlspecialchars($app['idea_title']) ?></h2>
                    <p class="developer">@<?= htmlspecialchars($app['developer_name']) ?></p>
                    <p><strong>Cover Letter:</strong> <?= nl2br(htmlspecialchars($app['cover_letter'])) ?></p>
                    <p><strong>Resume:</strong> <a href="<?= htmlspecialchars($app['resume_link']) ?>" target="_blank">View</a></p>
                    <p><strong>Contact:</strong> <?= htmlspecialchars($app['contact_number']) ?></p>
                    <button class="remove-btn" onclick="removeApplication(<?= $app['id'] ?>)">Remove</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
