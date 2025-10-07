<?php
session_start();
include '../connect.php'; // Include your database connection

// Ensure the worker is logged in
if (!isset($_SESSION['worker_id'])) {
    echo "Unauthorized access";
    exit();
}

$worker_id = $_POST['worker_id'];

// Fetch the current availability status of the worker
$sql = "SELECT availability_status FROM worker WHERE worker_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $worker_id);
$stmt->execute();
$result = $stmt->get_result();
$worker = $result->fetch_assoc();

// Toggle the current status
$new_status = ($worker['availability_status'] == 'available') ? 'busy' : 'available';

// Update the availability status in the database
$update_sql = "UPDATE worker SET availability_status = ? WHERE worker_id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("si", $new_status, $worker_id);

if ($update_stmt->execute()) {
    // Redirect back to the worker profile or dashboard page
    header("Location: WorkerProfile.php");
    exit();
} else {
    echo "Error updating status.";
}

$stmt->close();
$conn->close();
