<?php 

session_start();
require 'db_connect.php';
if (isset($_SESSION['success'])) {
    echo "<div class='success-message'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']); 
}
if (isset($_SESSION['error'])) {
    echo "<div class='error-message'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}


// Fetch startup ideas from the database
$stmt = $conn->prepare("SELECT * FROM startup_ideas ORDER BY created_at DESC");
$stmt->execute();
$ideas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startup Ideas</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
   
<script>
    function openModal(ideaId) {
        var modal = document.getElementById("applyModal");
        modal.style.display = "flex";
        modal.style.opacity = "1";
        document.getElementById("idea_id").value = ideaId;
    }

    function closeModal() {
        var modal = document.getElementById("applyModal");
        modal.style.opacity = "0";
        setTimeout(() => {
            modal.style.display = "none";
        }, 300);
    }

    // Close modal when clicking outside the modal content
    window.onclick = function(event) {
        var modal = document.getElementById("applyModal");
        if (event.target === modal) {
            closeModal();
        }
    };
</script>



    <style>
          body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .idea-card {
            background: #fff;
            border: 1px solid #000;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .idea-card h2 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            color: #000;
        }
        .company-name {
            font-size: 16px;
            color: #777;
            margin-bottom: 10px;
        }
        .meta-info {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .tag {
            display: inline-block;
            background: #ddd;
            color: #444;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            margin: 5px 0;
        }
        .section-title {
            font-weight: bold;
            margin-top: 15px;
            font-size: 16px;
            color: #000;
        }
        

        .posted-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #ddd;
}

.posted-section img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 8px;
}

.posted-section span {
    color: #666;
    font-size: 14px;
}

.apply-btn {
    background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
    color: white;
    padding: 8px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    display: inline-block;
}

.apply-btn:hover {
    background: linear-gradient(45deg, rgb(0, 0, 0), rgb(17, 19, 139));
}

.back-button {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
            color: white;
            padding: 5px 8px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .back-button:hover {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(17, 19, 139));
        }
 /* Modal Styling */
 .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        align-items: center;
        justify-content: center;
        transition: opacity 0.3s ease-in-out;
    }
    
    .modal-content {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        width: 40%;
        padding: 25px;
        border-radius: 12px;
        position: relative;
        animation: slideIn 0.3s ease-in-out;
    }

    /* Smooth Slide-In Animation */
    @keyframes slideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Close Button */
    .close {
        position: absolute;
        top: 12px;
        right: 15px;
        font-size: 22px;
        color: white;
        cursor: pointer;
        transition: 0.3s;
    }

    .close:hover {
        color: red;
    }

    /* Form Inputs */
    input, textarea {
        width: 95%;
        padding: 12px;
        margin: 10px 0;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.2);
        color: white;
        outline: none;
        transition: 0.3s;
    }

    input:focus, textarea:focus {
        border-color: white;
        background: rgba(255, 255, 255, 0.3);
    }

    /* Submit Button */
    button {
        width: 40%;
        padding: 13px;
        background: linear-gradient(45deg, black, rgb(163, 31, 31));
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background: linear-gradient(45deg, rgb(0, 0, 0), rgb(17, 19, 139));
    }

    /* Make modal responsive */
    @media (max-width: 768px) {
        .modal-content {
            width: 90%;
            padding: 15px;
        }
    }
    </style>
</head>
<body>
    <div class="container">
        <a href="developer_dashboard.php" class="back-button"><i class="bi bi-box-arrow-left"></i>  Let's go</a>

        <h1>Startup Ideas</h1>
        <?php foreach ($ideas as $idea): ?>
            <div class="idea-card">
                <h2><?php echo htmlspecialchars($idea['role']); ?></h2>
                <p class="company-name"><?php echo htmlspecialchars($idea['company_name']); ?></p>
                <p class="meta-info"><?php echo htmlspecialchars($idea['experience']); ?> years exp • <?php echo htmlspecialchars($idea['funding_stage']); ?> • $<?php echo htmlspecialchars($idea['salary_range']); ?> • <?php echo htmlspecialchars($idea['equity_range']); ?>% equity</p>
                <span class="tag"><?php echo htmlspecialchars($idea['tech_requirements']); ?></span>

                <p class="section-title">Idea Description</p>
                <p><?php echo htmlspecialchars($idea['detailed_idea']); ?></p>

                <p class="section-title">Role Description</p>
                <p><?php echo htmlspecialchars($idea['role_description']); ?></p>

                <div class="posted-section">
                    <span>Posted by: <?php echo htmlspecialchars($idea['email']); ?></span>
                    <button class="apply-btn" onclick="openModal(<?php echo $idea['id']; ?>)">Apply Now</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    
<!-- Modal for Application -->
<div id="applyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 style="text-align: center; color: white;">Apply for Startup</h2>
        <form action="submit_application.php" method="POST">
            <input type="hidden" id="idea_id" name="idea_id">

            <label style="color: white;">Cover Letter:</label>
            <textarea name="cover_letter" required></textarea>

            <label style="color: white;">Resume Link:</label>
            <input type="url" name="resume_link" required>

            <label style="color: white;">Contact Number:</label>
            <input type="text" name="contact_number" required>

            <label style="color: white;">Instagram:</label>
            <input type="url" name="instagram">

            <label style="color: white;">Twitter:</label>
            <input type="url" name="twitter">

            <button type="submit">Submit Application</button>
        </form>
    </div>
</div>
</body>
</html>
