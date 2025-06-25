<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db_connect.php';
$user_id = $_SESSION['user_id'];

// Fetch developer details, including profile picture and education
$stmt = $conn->prepare("SELECT name, email, role, skills, portfolio, experience, education, profile_picture FROM developers WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

$profilePic = $user['profile_picture'] ?? 'default-profile.png';  // Default if no profile picture

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $skills = trim($_POST['skills']);
    $role = trim($_POST['role']);
    $portfolio = trim($_POST['portfolio']);
    $experience = trim($_POST['experience']);
    $education = trim($_POST['education']);

    // Profile picture upload handling
    if (!empty($_FILES['profile_picture']['name'])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        if (in_array($fileType, ['jpg', 'jpeg', 'png']) && $_FILES['profile_picture']['size'] <= 2 * 1024 * 1024) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
                $profilePic = $targetFilePath;
            } else {
                echo "<p style='color: red; text-align:center;'>Error uploading profile picture.</p>";
            }
        } else {
            echo "<p style='color: red; text-align:center;'>Invalid file. Only JPG, JPEG, PNG (Max 2MB).</p>";
        }
    }

    // Update developer profile
    $updateStmt = $conn->prepare("UPDATE developers SET skills = :skills, role = :role, portfolio = :portfolio, experience = :experience, education = :education, profile_picture = :profile_picture WHERE id = :id");
    $success = $updateStmt->execute([
        'skills' => $skills,
        'role' => $role,
        'portfolio' => $portfolio,
        'experience' => $experience,
        'education' => $education,
        'profile_picture' => $profilePic,
        'id' => $user_id
    ]);

    if ($success) {
        echo "<p style='color: green; text-align:center;'>Profile updated successfully!</p>";
    } else {
        echo "<p style='color: red; text-align:center;'>Error updating profile.</p>";
    }

    header("Refresh:2; url=developer_profile.php");
}
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
    <style>
       body {
    font-family: 'Poppins', sans-serif;
    background: #f4f4f4;
    margin: 0;
    padding: 100px 0 ;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.profile-container {
    width: 100%;
           max-width: 700px;
           margin:100px auto;
            padding: 25px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(22, 21, 21, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 5px;
}

input, textarea, select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box; /* Ensures consistent sizing */
    background: #fff;
    transition: 0.3s ease-in-out;
}

input:focus, textarea:focus {
    border-color: #2575fc;
    outline: none;
    box-shadow: 0 0 8px rgba(37, 117, 252, 0.3);
}

/* Two-column layout for first & last name */
.flex-group {
    display: flex;
    gap: 10px;
}

.flex-group .form-group {
    flex: 1;
}

/* Profile Picture */
.profile-picture {
    display: block;
    width: 150px;
    height: 150px;
  
    margin: 0 auto 15px;
    border: 2px solid #ddd;
    border-radius: 20%;
            border: 2px solid rgb(0, 0, 0);
            overflow: hidden;
            
}

/* Buttons */
.button-group {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
}

button {
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

.save-button {
    background: #2575fc;
    color: white;
}

.cancel-button {
    background: #ccc;
}



        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .back-button {
    background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
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
        .butt{
           
            align-items: center;
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
            color: white;
            width: 100%;
            padding: 12px;
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
            color: #fff;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-size: 18px;
            transition: 0.3s;
        }

        .butt:hover{
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(47, 22, 156));
        }

    </style>


<body>
    <div class="profile-container">
        <a href="developer_dashboard.php" class="back-button"><i class="bi bi-box-arrow-left"></i> Let's go</a>

        <div class="profile-header">
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture">
            </div>
            <h2><?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></p>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" name="profile_picture" accept="image/*">
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <input type="text" name="role" value="<?php echo htmlspecialchars($user['role'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="skills">Skills:</label>
                <input type="text" name="skills" value="<?php echo htmlspecialchars($user['skills'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="portfolio">Portfolio URL:</label>
                <input type="url" name="portfolio" value="<?php echo htmlspecialchars($user['portfolio'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="experience">Experience (Years):</label>
                <input type="text" name="experience" value="<?php echo htmlspecialchars($user['experience'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="education">Education:</label>
                <input type="text" name="education" value="<?php echo htmlspecialchars($user['education'] ?? ''); ?>">
            </div>
            <button class="butt" type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>