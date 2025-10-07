


<?php
session_start();
include 'connect.php';

// Check if the user is logged in as either a customer or worker
if (!isset($_SESSION['customer_id']) && !isset($_SESSION['worker_id'])) {
    echo "You need to log in to submit a support request.";
    exit();
}

// Determine if the user is a customer or worker based on session data
if (isset($_SESSION['customer_id'])) {
    $user_id = $_SESSION['customer_id']; // Fetch customer ID
    $user_type = 'customer'; // Set user type as 'customer'
} elseif (isset($_SESSION['worker_id'])) {
    $user_id = $_SESSION['worker_id']; // Fetch worker ID
    $user_type = 'worker'; // Set user type as 'worker'
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Insert support ticket into the database
    $stmt = $conn->prepare("INSERT INTO support_tickets (user_id, user_type, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $user_type, $subject, $message);

    if ($stmt->execute()) {
        echo "Your support request has been submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>