<?php
session_start();
require 'db_connect.php';

// Check if the user is a developer
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'developer') {
    $_SESSION['error'] = "You must be logged in as a developer to apply.";
    header("Location: developer_dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $developer_id = $_SESSION['user_id']; // Get developer ID from session
    $idea_id = $_POST['idea_id'];
    $cover_letter = trim($_POST['cover_letter']);
    $resume_link = trim($_POST['resume_link']);
    $contact_number = trim($_POST['contact_number']);
    $instagram = !empty($_POST['instagram']) ? trim($_POST['instagram']) : null;
    $twitter = !empty($_POST['twitter']) ? trim($_POST['twitter']) : null;

    if (empty($cover_letter) || empty($resume_link) || empty($contact_number)) {
        $_SESSION['error'] = "All required fields must be filled.";
        header("Location: startup_ideas.php");
        exit();
    }

    try {
        $stmt = $conn->prepare("INSERT INTO developer_applications 
            (developer_id, idea_id, cover_letter, resume_link, contact_number, instagram, twitter, applied_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        
        $stmt->execute([$developer_id, $idea_id, $cover_letter, $resume_link, $contact_number, $instagram, $twitter]);

        // Show success message and redirect
        $_SESSION['success'] = "Application submitted successfully!";
        header("Location: startup_ideas.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database Error: " . $e->getMessage();
        header("Location: startup_ideas.php");
        exit();
    }
}
?>
