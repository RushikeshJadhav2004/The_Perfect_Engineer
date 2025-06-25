<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db_connect.php';
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email, role, bio, skills, portfolio FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bio = trim($_POST['bio']);
    $skills = trim($_POST['skills']);
    $portfolio = trim($_POST['portfolio']);

    $updateStmt = $conn->prepare("UPDATE users SET bio = :bio, skills = :skills, portfolio = :portfolio WHERE id = :id");
    $success = $updateStmt->execute([
        'bio' => $bio,
        'skills' => $skills,
        'portfolio' => $portfolio,
        'id' => $user_id
    ]);

    if ($success) {
        echo "<p style='color: green; text-align:center;'>Profile updated successfully!</p>";
    } else {
        echo "<p style='color: red; text-align:center;'>Error updating profile. Please try again.</p>";
    }

    header("Refresh:2; url=profile.php"); // Redirect after 2 seconds
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .profile-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background:rgb(0, 0, 0);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: 0.3s;
        }

        button:hover {
            background: #2575fc;
        }
        .back-button {
            background: #000;
            color: white;
            padding: 5px 8px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .back-button:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <div class="profile-container">
    <a href="dashboard.php" class="back-button"><i class="bi bi-back"></i> Let's go</a>
        <h2>Your Profile</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea name="bio" id="bio" rows="3"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="skills">Skills:</label>
                <input type="text" name="skills" id="skills" value="<?php echo htmlspecialchars($user['skills'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="portfolio">Portfolio (GitHub, LinkedIn, etc.):</label>
                <input type="url" name="portfolio" id="portfolio" value="<?php echo htmlspecialchars($user['portfolio'] ?? ''); ?>">
            </div>
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>

.navbarr {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 50px;
            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
            border-bottom: 1px solid rgb(83, 83, 83);
            margin-bottom: 20px;
            background: #fff;
        }

        .navbarr .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #000;
        }

        .login-btn {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            margin-right: 90px;

        }



    


<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'founder') {
    header("Location: login.php");
    exit();
}

require 'db_connect.php';
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email, role, company_name, company_website, company_size, funding_stage, equity_range, salary_range, role_description, required_tech_stack, experience_required FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Founder Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
            padding: 15px;
            border-radius: 10px 10px 0 0;
            color: white;
            width: 100%;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .sidebar {
            width: 300px;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .sidebar h3 {
            color: rgb(0, 0, 0);
            font-size: 20px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .sidebar p {
            font-size: 14px;
            margin: 8px 0;
        }


       
        .navbarr .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #000;
        }

        .login-btn {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            margin-right: 90px;

        }
    </style>
</head>
<body>
<header class="navbarr">
        <div class="logo head-font">Rushikesh</div>
        <a href="logout.php" class="login-btn"> Logout</a>
    </header>
    <div class="dashboard-container">
        <div class="navbar">
            <span>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</span>
            <div>
                 <a href="browse.php">Browse Developers</a>
                <a href="messages.php">Messages</a>
                <a href="inbox.php">Inbox</a>
            </div>
        </div>
        <div class="content">
            <h2>Founder Dashboard</h2>
            <p>You are logged in as a Founder.</p>
        </div>
        <div class="sidebar">
        <h3>Company Profile &nbsp;&nbsp;&nbsp;&nbsp; <a href="founder_profile.php"><i
        class="bi bi-pencil-square"></i></a></h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Company:</strong> <?php echo htmlspecialchars($user['company_name']); ?></p>
            <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($user['company_website']); ?>" target="_blank">Visit</a></p>
            <p><strong>Size:</strong> <?php echo htmlspecialchars($user['company_size']); ?></p>
            <p><strong>Funding:</strong> <?php echo htmlspecialchars($user['funding_stage']); ?></p>
            <p><strong>Equity Range:</strong> <?php echo htmlspecialchars($user['equity_range']); ?></p>
            <p><strong>Salary Range:</strong> <?php echo htmlspecialchars($user['salary_range']); ?></p>
            <p><strong>Tech Stack:</strong> <?php echo htmlspecialchars($user['required_tech_stack']); ?></p>
            <p><strong>Experience Required:</strong> <?php echo htmlspecialchars($user['experience_required']); ?> years</p>
            <p><strong>Role Description:</strong> <?php echo htmlspecialchars($user['role_description']); ?></p>
        </div>
    </div>
</body>
</html>



        