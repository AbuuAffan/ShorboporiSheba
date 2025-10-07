<?php
session_start();

// Include the database connection file
include '../connect.php'; // Ensure this file contains your database connection

// Check if the worker is logged in
if (!isset($_SESSION['worker_id'])) {
    echo "You need to be logged in to accept requests.";
    exit();
}

// Fetch worker_id from the session
$worker_id = $_SESSION['worker_id'];

// Check if the form was submitted
if (isset($_POST['accept']) && isset($_POST['request_id'], $_POST['total_price'])) {
    $request_id = $_POST['request_id'];
    $total_price = $_POST['total_price'];

    // Start a transaction to ensure data consistency
    $conn->begin_transaction();

    try {
        // 1. Update the request status to 'accepted'
        $update_request = "UPDATE request SET status = 'accepted' WHERE request_id = ? AND worker_id = ?";
        $stmt_update = $conn->prepare($update_request);
        $stmt_update->bind_param("ii", $request_id, $worker_id);
        $stmt_update->execute();

        // 2. Update the worker's availability_status to 'busy'
        $update_worker = "UPDATE worker SET availability_status = 'busy' WHERE worker_id = ?";
        $stmt_worker = $conn->prepare($update_worker);
        $stmt_worker->bind_param("i", $worker_id);
        $stmt_worker->execute();

        // 3. Insert the service order into the service_order table
        $order_date = date('Y-m-d');
        $order_status = 'on_going';

        // Fetch the request details to get the customer_id and service_id
        $query_request_details = "SELECT customer_id, service_id FROM request WHERE request_id = ?";
        $stmt_request_details = $conn->prepare($query_request_details);
        $stmt_request_details->bind_param("i", $request_id);
        $stmt_request_details->execute();
        $result_request_details = $stmt_request_details->get_result();
        $request_details = $result_request_details->fetch_assoc();

        $customer_id = $request_details['customer_id'];
        $service_id = $request_details['service_id'];

        // Insert into service_order table
        $insert_order = "
            INSERT INTO service_order (customer_id, worker_id, order_date, order_status, service_id, payable_amount)
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $stmt_insert_order = $conn->prepare($insert_order);
        $stmt_insert_order->bind_param("iisssi", $customer_id, $worker_id, $order_date, $order_status, $service_id, $total_price);
        $stmt_insert_order->execute();

        // Commit the transaction
        $conn->commit();

        // Success, redirect to the worker's dashboard
        echo "<script>
                alert('Request accepted successfully and service order created.');
                window.location.href = '../Wdashboard.html';
              </script>";
        exit();
    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid submission.";
}

// Close the database connection
$conn->close();
