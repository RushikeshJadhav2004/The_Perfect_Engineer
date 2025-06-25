<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db_connect.php';
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email, role, bio, skills, portfolio, 
    company_name, company_website, company_size, funding_stage, equity_range, salary_range, 
    role_description, required_tech_stack, experience_required 
    FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $bio = trim($_POST['bio']);
    // $skills = trim($_POST['skills']);
    // $portfolio = trim($_POST['portfolio']);
    $company_name = trim($_POST['company_name']);
    $company_website = trim($_POST['company_website']);
    $company_size = trim($_POST['company_size']);
    // $funding_stage = trim($_POST['funding_stage']);
    $equity_range = trim($_POST['equity_range']);
    $salary_range = trim($_POST['salary_range']);
    $role_description = trim($_POST['role_description']);
    $required_tech_stack = trim($_POST['required_tech_stack']);
    $experience_required = trim($_POST['experience_required']);

    $updateStmt = $conn->prepare("UPDATE users SET 
    company_name = :company_name, company_website = :company_website, 
    company_size = :company_size, 
    equity_range = :equity_range, salary_range = :salary_range, 
    role_description = :role_description, required_tech_stack = :required_tech_stack, 
    experience_required = :experience_required 
    WHERE id = :id");

$success = $updateStmt->execute([
    'company_name' => $company_name,
    'company_website' => $company_website,
    'company_size' => $company_size,
    'equity_range' => $equity_range,
    'salary_range' => $salary_range,
    'role_description' => $role_description,
    'required_tech_stack' => $required_tech_stack,
    'experience_required' => $experience_required,
    'id' => $user_id
]);


    if ($success) {
        echo "<p style='color: green; text-align:center;'>Profile updated successfully!</p>";
    } else {
        echo "<p style='color: red; text-align:center;'>Error updating profile. Please try again.</p>";
    }

    header("Refresh:2; url=founder_profile.php"); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Founder Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            max-width: 700px;
            margin: 40px auto;
            padding: 25px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(22, 21, 21, 0.1);
        }
        h2 {
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            color:black;
        }
        .profile-info p {
    font-size: 16px;
    font-weight: 500;
    color: #000; /* Pure black */
}




        .profile-info strong {
            color: #000;
        }
        .form-group {
            margin-bottom: 15px;
        }
        /* input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            background: #fafafa;
        } */
        .dual-input {
            display: flex;
            gap: 10px;
        }
        .dual-input input {
            width: 50%;
        }
        button {
            width: 100%;
            padding: 12px;
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(163, 31, 31));
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: 0.3s;
        }
        button:hover {
            background:linear-gradient(45deg,rgb(0, 0, 0),rgb(47, 22, 156));
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

input:focus, textarea:focus, select:focus {
    border-color: #2575fc;
    outline: none;
    box-shadow: 0 0 8px rgba(37, 117, 252, 0.3);
}

select {
    appearance: none; /* Removes default dropdown styling */
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
        label{
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
            font-size: 14px;
        }

        

    </style>
</head>
<body>
    
    <div class="profile-container">
    <a href="founder_dashboard.php" class="back-button"><i class="bi bi-box-arrow-left"></i> Let's go</a>
        <h2>Company Profile</h2>
        <div class="profile-info mb-3">
            <p ><strong >Name:</strong> <?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?></p>
            <p ><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></p>
            <p ><strong>Role:</strong> <?php echo ucfirst(htmlspecialchars($user['role'] ?? 'N/A')); ?></p>
        
        </div>

        <form  method="POST">
            <div class="form-group">
                <label  for="company_name">Company Name:</label>
                <input type="text" name="company_name" value="<?php echo htmlspecialchars($user['company_name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="company_website">Company Website:</label>
                <input type="url" name="company_website" value="<?php echo htmlspecialchars($user['company_website'] ?? ''); ?>">
            </div>
            <div class="form-group">
    <label for="company_size">Company Size:</label>
    <select name="company_size">
        <option value="" disabled selected>Select Company Size</option>
        <option value="1-10" <?php echo ($user['company_size'] == '1-10') ? 'selected' : ''; ?>>1-10 Employees</option>
        <option value="11-50" <?php echo ($user['company_size'] == '11-50') ? 'selected' : ''; ?>>11-50 Employees</option>
        <option value="51-200" <?php echo ($user['company_size'] == '51-200') ? 'selected' : ''; ?>>51-200 Employees</option>
        <option value="201-500" <?php echo ($user['company_size'] == '201-500') ? 'selected' : ''; ?>>201-500 Employees</option>
        <option value="501-1000" <?php echo ($user['company_size'] == '501-1000') ? 'selected' : ''; ?>>501-1000 Employees</option>
        <option value="1001+" <?php echo ($user['company_size'] == '1001+') ? 'selected' : ''; ?>>1001+ Employees</option>
    </select>
</div>

            <div class="form-group">
                <label for="required_tech_stack">Required Tech Stack:</label>
                <input type="text" name="required_tech_stack" value="<?php echo htmlspecialchars($user['required_tech_stack'] ?? ''); ?>">
            </div>
            <div class="form-group">
    <label for="experience_required">Experience Required (Years):</label>
    <select name="experience_required">
        <option value="" disabled selected>Select Experience</option>
        <option value="0-1" <?php echo ($user['experience_required'] == '0-1') ? 'selected' : ''; ?>>0-1 Years</option>
        <option value="1-3" <?php echo ($user['experience_required'] == '1-3') ? 'selected' : ''; ?>>1-3 Years</option>
        <option value="3-5" <?php echo ($user['experience_required'] == '3-5') ? 'selected' : ''; ?>>3-5 Years</option>
        <option value="5-10" <?php echo ($user['experience_required'] == '5-10') ? 'selected' : ''; ?>>5-10 Years</option>
        <option value="10+" <?php echo ($user['experience_required'] == '10+') ? 'selected' : ''; ?>>10+ Years</option>
    </select>
</div>

            <div class="dual-input">
                <input type="text" name="equity_range" placeholder="Equity Range (%)" value="<?php echo htmlspecialchars($user['equity_range'] ?? ''); ?>">
                <input type="text" name="salary_range" placeholder="Salary Range ($)" value="<?php echo htmlspecialchars($user['salary_range'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="role_description">Role Description:</label>
                <textarea name="role_description"><?php echo htmlspecialchars($user['role_description'] ?? ''); ?></textarea>
            </div>
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
