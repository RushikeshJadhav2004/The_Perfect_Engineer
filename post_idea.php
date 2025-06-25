<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $founder_id = $_SESSION['user_id'];

    // Validate required fields
    $title = isset($_POST['title']) ? trim($_POST['title']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;

    if (empty($title) || empty($description)) {
        die("Error: Title and Description are required fields.");
    }

    // Retrieve other optional fields safely
    $company_name = $_POST['company_name'] ?? null;
    $company_website = $_POST['company_website'] ?? null;
    $company_size = $_POST['company_size'] ?? null;
    $equity_range = $_POST['equity_range'] ?? null;
    $salary_range = $_POST['salary_range'] ?? null;
    $role = $_POST['role'] ?? null;
    $detailed_idea = $_POST['detailed_idea'] ?? null;
    $role_description = $_POST['key_responsibilities'] ?? null; // FIXED: Ensure it's stored
    $ideal_candidate_profile = $_POST['ideal_candidate_profile'] ?? null;
    $tech_requirements = $_POST['tech_requirements'] ?? null;
    $experience = $_POST['experience'] ?? null;
    $industry = $_POST['industry'] ?? null;
    $funding_stage = $_POST['funding_stage'] ?? null;

    // Fetch founder email from users table
    $stmt = $conn->prepare("SELECT email FROM users WHERE id = :founder_id");
    $stmt->execute(['founder_id' => $founder_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $founder_email = $user['email'] ?? null; // FIXED: Store founder's email

    try {
        $stmt = $conn->prepare("INSERT INTO startup_ideas 
            (founder_id, title, description, company_name, company_website, company_size, equity_range, salary_range, role, detailed_idea, role_description, ideal_candidate_profile, tech_requirements, experience, industry, funding_stage, email)
            VALUES (:founder_id, :title, :description, :company_name, :company_website, :company_size, :equity_range, :salary_range, :role, :detailed_idea, :role_description, :ideal_candidate_profile, :tech_requirements, :experience, :industry, :funding_stage, :email)");
        
        $stmt->execute([
            'founder_id' => $founder_id,
            'title' => $title,
            'description' => $description,
            'company_name' => $company_name,
            'company_website' => $company_website,
            'company_size' => $company_size,
            'equity_range' => $equity_range,
            'salary_range' => $salary_range,
            'role' => $role,
            'detailed_idea' => $detailed_idea,
            'role_description' => $role_description, // FIXED: Corrected field name
            'ideal_candidate_profile' => $ideal_candidate_profile,
            'tech_requirements' => $tech_requirements,
            'experience' => $experience,
            'industry' => $industry,
            'funding_stage' => $funding_stage,
            'email' => $founder_email // FIXED: Store founder's email
        ]);

        header("Location: founder_dashboard.php");
        exit();
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage()); // DEBUGGING HELP
    }
}
?>
