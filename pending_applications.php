<?php
session_start();
require 'db_connect.php';

// Check if the user is a founder
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'founder') {
    die("Access denied.");
}
// Get logged-in founder's ID
$founder_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT da.*, d.name AS developer_name, si.title AS idea_title 
                        FROM developer_applications da
                        JOIN developers d ON da.developer_id = d.id
                        JOIN startup_ideas si ON da.idea_id = si.id
                        WHERE da.status = 'pending' AND si.founder_id = ?
                        ORDER BY da.applied_at DESC");

$stmt->execute([$founder_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Applications</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: start;
            min-height: 100vh;
        }

        .container {
            margin-top:100px;
            width: 100%;
            max-width: 1000px;
            text-align: center;
            



        }

        /* Page Title */
        h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 40px;
            color: #000;
        }

        /* Grid Layout */
        .application-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
            justify-content: center;
        }

        /* Application Card */
        .application-card {
            background: rgba(255, 255, 255, 0.7);
            padding: 20px;
            border: 1px solid rgb(0, 0, 0);
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            color: #333;
            text-align: left;
            position: relative;
        }

        .application-card:hover {
            transform: scale(1.02);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
        }

        /* Developer & Idea Details */
        .application-card h2 {
            font-size: 18px;
            color: #222;
            margin-bottom: 5px;
        }

        .developer {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        /* Contact & Social Links */
        .application-card p {
            font-size: 14px;
            margin: 5px 0;
        }

        .application-card a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .application-card a:hover {
            text-decoration: underline;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            display: inline-block;
            font-weight: bold;
        }

        .btn.accept {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
            color: white;
        }

        .btn.accept:hover {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(23, 184, 71));
        }

        .btn.reject {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(31, 51, 163));
            color: white;
        }

        .btn.reject:hover {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(192, 20, 20));
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                width: 95%;
            }

            .btn {
                width: auto;
                font-size: 12px;
                padding: 6px 10px;
            }
        }


        .backk-button{
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
            color: white;
            padding: 5px 8px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
            position:absolute;
            top: 30px;
            left: 30px;
        }
        .backk-button:hover {
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(13, 10, 173));
        }  
    </style>
</head>
<body>
<a href="founder_dashboard.php" class="backk-button"><i class="bi bi-box-arrow-left"></i> Let's go</a>


<div class="container">
    <h1>🚀 Pending Applications</h1>

    <?php if (empty($applications)): ?>
        <p>No pending applications.</p>
    <?php else: ?>
        <div class="application-grid">
            <?php foreach ($applications as $app): ?>
                <div class="application-card">
                    <h2><?= htmlspecialchars($app['idea_title']) ?></h2>
                    <p class="developer">👤 <?= htmlspecialchars($app['developer_name']) ?></p>
                    <p><strong>📄 Cover Letter:</strong> <?= nl2br(htmlspecialchars($app['cover_letter'])) ?></p>
                    <p><strong>📄 Resume:</strong> <a href="<?= htmlspecialchars($app['resume_link']) ?>" target="_blank">View Resume</a></p>
                    <p><strong>📞 Contact:</strong> <?= htmlspecialchars($app['contact_number']) ?></p>
                    <p><strong>📷 Instagram:</strong> <a href="<?= htmlspecialchars($app['instagram'] ?? '#') ?>" target="_blank">View</a></p>
                    <p><strong>🐦 Twitter:</strong> <a href="<?= htmlspecialchars($app['twitter'] ?? '#') ?>" target="_blank">View</a></p>
                    
                    <div class="action-buttons">
                        <form action="approve_application.php" method="POST">
                            <input type="hidden" name="application_id" value="<?= $app['id'] ?>">
                            <button type="submit" name="action" value="accept" class="btn accept">✔ Accept</button>
                            <button type="submit" name="action" value="reject" class="btn reject">✖ Reject</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
