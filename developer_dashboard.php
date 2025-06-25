<?php
require 'db_connect.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'developer') {
    header("Location: developer_login.php");
    exit();
}

// Fetch developer data from database
try {
    $developer_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM developers WHERE id = :id");
$stmt->execute([':id' => $developer_id]); $developer =
$stmt->fetch(PDO::FETCH_ASSOC); if (!$developer) { $developer = []; 
   } } catch (PDOException
$e) { die("Database error: " . $e->getMessage()); } ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Developer Dashboard</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
  </head>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .dashboard-container {
      max-width: 1200px;
      margin: 100px auto;
      padding: 20px;
      background:rgb(32, 32, 32);
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
      background:rgb(32, 32, 32);
      
    }

    .navbarr .logo {
      font-size: 1.5rem;
      font-weight: 700;
      color: #fff;
    }

    .login-btn {
      background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
      color: white;
      padding: 10px 18px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.9rem;
      margin-right: 30px;
    }

    .post-idea-btn {
      /* position: absolute;
            bottom: 180px;
         left: 90px; */
      background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
      color: white;
      border: none;
      padding: 8px 10px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 5px;
    }

    /* .post-idea-btn:hover {
        background: #0056b3;
               } */

    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      width: 60%;
      max-height: 80%;
      overflow-y: auto;
      border-radius: 8px;
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
      background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
      color: white;
      padding: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 10px;
      border-radius: 8px 8px 0 0;
    }

    .modal-body {
      padding: 20px;
      max-height: 500px;
      overflow-y: auto;
    }

    .section {
      margin-bottom: 20px;
      padding: 10px;
      border-radius: 8px;
      background: #f8f8f8;
    }

    .section h3 {
      margin-bottom: 10px;
      font-size: 18px;
    }

    .form-group {
      display: flex;
      gap: 10px;
    }

    .form-group input,
    .form-group select {
      flex: 1;
    }

    input,
    textarea,
    select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    .modal-footer {
      display: flex;
      justify-content: space-between;
      padding: 15px;
      background: #f1f1f1;
      border-radius: 0 0 8px 8px;
    }

    .cancel-btn,
    .post-btn {
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      border-radius: 5px;
      font-size: 16px;
    }

    .content{
      color:white;
    }

    .cancel-btn {
      background: #ccc;
    }

    .postt {
      background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
      color: white;
    }

    .post2,
    .post3,
    .post4 {
      background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
      color: white;
    }

    .post4:hover {
      color: white;
    }

    .post2:hover {
      color: white;
    }

    .post3:hover {
      color: white;
    }

    .postt:hover {
      color: white;
    }

    .close-btn {
      /* background: #000; */
      color: white;
      padding-right: 4px;
      cursor: pointer;
      font-size: 15px;
      /* border: 1px solid #000;
        border-radius: 50%; */
    }
  </style>

  <body>
    <header class="navbarr">
      <div class="logo head-font">परफेक्ट इंजीनियर</div>
      <a href="logout.php" class="login-btn"> Logout</a>
    </header>
    <div class="dashboard-container">
      <div class="navbar">
        <span>
          Welcome,
          <?php echo isset($developer['name']) ? htmlspecialchars($developer['name']) : 'Developer'; ?>
        </span>
        <div>
          <a href="browse.php">Browse Foundersers</a>
          <a href="startup_ideas.php">Startup Ideas</a>
          <a href="message.php">Messages</a>
          <a href="developer_inbox.php">Inbox</a>
        </div>
      </div>
      <div class="content">
        <h2>Developer Dashboard</h2>
        <p>You are logged in as a Developer.</p>

        <a class="btn post2 mx-1" href="#">Saved</a>
        <a class="btn post3 mx-1" href="#">Applied</a>
        <a class="btn post4 mx-1" href="developer_profile.php">Profile</a>
      </div>

      <div class="sidebar">
    <h3>
        Developer Profile &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="developer_profile.php"><i class="bi bi-pencil-square"></i></a>
    </h3>

    <p><strong>Name:</strong> <?php echo htmlspecialchars($developer['name'] ?? 'N/A'); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($developer['email'] ?? 'N/A'); ?></p>
    <p><strong>Experience:</strong> <?php echo htmlspecialchars($developer['experience'] ?? 'N/A'); ?> years</p>
    <p><strong>Skills:</strong> <?php echo htmlspecialchars($developer['skills'] ?? 'N/A'); ?></p>
    
    <p>
        <strong>GitHub:</strong>
        <a href="<?php echo htmlspecialchars($developer['portfolio'] ?? '#'); ?>" target="_blank">
            <?php echo htmlspecialchars($developer[''] ?? 'Visit'); ?>
        </a>
    </p>

    <!-- New Education Section -->
    <p><strong>Education:</strong>&nbsp;&nbsp;<?php echo htmlspecialchars($developer['education'] ?? 'N/A'); ?></p>
   
</div>

    </div>

    <!-- Bootstrap JS (for modal functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
