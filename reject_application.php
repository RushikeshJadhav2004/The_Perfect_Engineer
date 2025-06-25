<?php
session_start();
require 'db_connect.php';

// Ensure only founders can reject applications
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'founder') {
    die("Access denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["application_id"])) {
    $application_id = $_POST["application_id"];
    $founder_id = $_SESSION['user_id']; // Get the logged-in founder's ID

    // Check if the application belongs to the logged-in founder's startup
    $stmt = $conn->prepare("SELECT da.id 
                            FROM developer_applications da
                            JOIN startup_ideas si ON da.idea_id = si.id
                            WHERE da.id = ? AND si.founder_id = ?");
    $stmt->execute([$application_id, $founder_id]);
    $application = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($application) {
        // Update status to 'rejected' only if the application belongs to this founder
        $updateStmt = $conn->prepare("UPDATE developer_applications SET status = 'rejected' WHERE id = ?");
        if ($updateStmt->execute([$application_id])) {
            echo "Application successfully rejected.";
        } else {
            echo "Error rejecting application.";
        }
    } else {
        echo "Application not found or does not belong to your startup.";
    }
}
?>
