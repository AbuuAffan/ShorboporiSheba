<?php
session_start();
include 'connect.php';

// Check if the customer is logged in
if (!isset($_SESSION['customer_id'])) {
    echo "You need to be logged in to submit a rating.";
    exit();
}

// Check if required POST data is provided
if (isset($_POST['order_id'], $_POST['customer_id'], $_POST['worker_id'], $_POST['rating_score'], $_POST['review'])) {
    $order_id = $_POST['order_id'];
    $customer_id = $_POST['customer_id'];
    $worker_id = $_POST['worker_id'];
    $rating_score = $_POST['rating_score'];
    $review = $_POST['review'];
    $rating_date = date('Y-m-d'); // Current date and time

    // Insert the rating into the database
    $insert_rating = "INSERT INTO rating (customer_id, worker_id, rating_score, review, rating_date, order_id) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($insert_rating)) {
        $stmt->bind_param("iidssi", $customer_id, $worker_id, $rating_score, $review, $rating_date, $order_id);
        if ($stmt->execute()) {
            echo "Rating submitted successfully.";
        } else {
            echo "Error submitting rating: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Incomplete form submission.";
}

$conn->close();
