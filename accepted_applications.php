<?php
session_start();
require 'db_connect.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'founder') {
    die("Access denied.");
}

// Fetch accepted applications
$stmt = $conn->prepare("SELECT da.*, d.name AS developer_name, si.title AS idea_title 
                        FROM developer_applications da
                        JOIN developers d ON da.developer_id = d.id
                        JOIN startup_ideas si ON da.idea_id = si.id
                        WHERE da.status = 'accepted' AND si.founder_id = :founder_id
                        ORDER BY da.applied_at DESC");

$stmt->bindParam(':founder_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$acceptedApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepted Applications</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 800px;
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: start;
            margin:50px auto;
        }

        h1 {
            color: #27ae60;
            font-size: 32px;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        .application-card {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px;
            border: 1px solid #000;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .application-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .application-card h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .developer {
            font-weight: bold;
            color: #34495e;
            margin-bottom: 10px;
        }

        p {
            margin: 10px 0;
            color: #555;
        }

        a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .buttons {
            margin-top: 20px;
        }

        .action-btn {
            padding: 10px 20px;
            font-size: 14px;
            margin: 5px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
        }


        /* .remove-btn {
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(21, 55, 165));
            color: white;
        }

        .remove-btn:hover {
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
        } */

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

        .remove-btn {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(17, 19, 139));
    color: white;
    border: none;
    padding: 8px 12px;
    margin-top: 10px;
    cursor: pointer;
    border-radius: 4px;
    transition: 0.3s;
}

.remove-btn:hover {
    background: linear-gradient(45deg, rgb(0, 0, 0), rgb(192, 7, 7));
}

    </style>
     <script>
       function removeApplication(applicationId) {
    if (confirm("Are you sure you want to remove this application?")) {
        fetch('remove_application.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'application_id=' + encodeURIComponent(applicationId)
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                // Remove application from UI
                let element = document.getElementById("app-" + applicationId);
                if (element) {
                    element.remove();
                }
            } else {
                alert("Error: " + data);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

    </script>
</head>

<body>

<div class="container">
<a href="founder_dashboard.php" class="backk-button"><i class="bi bi-box-arrow-left"></i> Let's go</a>

    <h1>✅ Accepted Applications</h1>

    <?php if (empty($acceptedApplications)): ?>
        <p>No accepted applications.</p>
    <?php else: ?>
        <?php foreach ($acceptedApplications as $app): ?>
            <div class="application-card" id="app-<?= $app['id'] ?>">

                <h2><?= htmlspecialchars($app['idea_title']) ?></h2>
                <p class="developer">@<?= htmlspecialchars($app['developer_name']) ?></p>
                <p><strong>Cover Letter:</strong> <?= nl2br(htmlspecialchars($app['cover_letter'])) ?></p>
                <p><strong>Resume:</strong> <a href="<?= htmlspecialchars($app['resume_link']) ?>" target="_blank">View</a></p>
                <p><strong>Contact:</strong> <?= htmlspecialchars($app['contact_number']) ?></p>
                <p><strong>Instagram:</strong> <a href="<?= htmlspecialchars($app['instagram'] ?? '#') ?>" target="_blank">View</a></p>
                <p><strong>Twitter:</strong> <a href="<?= htmlspecialchars($app['twitter'] ?? '#') ?>" target="_blank">View</a></p>

          
                <div class="buttons">
               

                    <!-- Remove Button -->
                    <form action="reject_application.php" method="POST" style="display: inline;">
                        <input type="hidden" name="application_id" value="<?= htmlspecialchars($app['id']) ?>">
                        <button type="button" class="remove-btn" onclick="removeApplication(<?= $app['id'] ?>)">Remove</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>

</body>
</html>
