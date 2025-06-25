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
        $stmt = $conn->prepare("UPDATE developer_applications SET status = 'accepted' WHERE id = ?");
    } elseif ($action === "remove") {
        $stmt = $conn->prepare("DELETE FROM developer_applications WHERE id = ?");
    } else {
        die("Invalid action.");
    }

    $stmt->execute([$application_id]);
    header("Location: rejected_applications.php");
    exit();
}
?>
