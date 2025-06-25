<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'founder') {
    die("Access denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["application_id"], $_POST["action"])) {
    $application_id = $_POST["application_id"];
    $action = $_POST["action"];

    if ($action === "accept") {
        $status = "accepted";
    } elseif ($action === "reject") {
        $status = "rejected";
    } else {
        die("Invalid action.");
    }

    $stmt = $conn->prepare("UPDATE developer_applications SET status = ? WHERE id = ?");
    $stmt->execute([$status, $application_id]);

    header("Location: pending_applications.php"); // Redirect back to pending applications
    exit();
}
?>
