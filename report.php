<?php
session_start();
include 'connect.php';

// Check if the customer is logged in
if (!isset($_SESSION['customer_id'])) {
    echo "You need to be logged in to submit a report.";
    exit();
}

// Check if required POST data is provided
if (isset($_POST['order_id'], $_POST['reported_customer_id'], $_POST['reported_worker_id'], $_POST['report_description'])) {
    $order_id = $_POST['order_id'];
    $reported_customer_id = $_POST['reported_customer_id'];
    $reported_worker_id = $_POST['reported_worker_id'];
    $report_description = $_POST['report_description'];
    $report_date = date('Y-m-d H:i:s'); // Current date and time

    // Insert the report into the database
    $insert_report = "INSERT INTO report (reported_customer_id, reported_worker_id, report_date, report_description, order_id) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($insert_report)) {
        $stmt->bind_param("iissi", $reported_customer_id, $reported_worker_id, $report_date, $report_description, $order_id);
        if ($stmt->execute()) {
            echo "Report submitted successfully.";
        } else {
            echo "Error submitting report: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Incomplete form submission.";
}

$conn->close();
