<?php
session_start();
require 'db_connect.php';

// Ensure only founders can remove applications
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'founder') {
    die("Access denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["application_id"])) {
    $application_id = $_POST["application_id"];
    $founder_id = $_SESSION['user_id'];

    // Verify that the application belongs to this founder
    $stmt = $conn->prepare("SELECT da.id 
                            FROM developer_applications da
                            JOIN startup_ideas si ON da.idea_id = si.id
                            WHERE da.id = ? AND si.founder_id = ?");
    $stmt->execute([$application_id, $founder_id]);
    $application = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($application) {
        // Delete the application
        $deleteStmt = $conn->prepare("DELETE FROM developer_applications WHERE id = ?");
        if ($deleteStmt->execute([$application_id])) {
            echo "success";
        } else {
            echo "Error deleting application.";
        }
    } else {
        echo "Application not found or unauthorized.";
    }
}
?>
