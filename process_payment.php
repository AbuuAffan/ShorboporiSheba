<?php
session_start();
include 'connect.php'; // Include the database connection

// Check if the customer is logged in
if (!isset($_SESSION['customer_id'])) {
    echo "You need to be logged in to proceed with payment.";
    exit();
}

$customer_id = $_SESSION['customer_id'];
$order_id = $_POST['order_id'];
$worker_id = $_POST['worker_id'];
$pay_amount = $_POST['pay_amount'];
$account = $_POST['account'];
$pin = $_POST['pin'];

// Fetch customer details and payable amount from the service_order table
$query = "SELECT c.amount as balance, c.account as account_number, c.pin, so.payable_amount 
          FROM customer c 
          JOIN service_order so ON so.customer_id = c.customer_id 
          WHERE c.customer_id = ? AND so.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $customer_id, $order_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

// Check if the pay_amount is enough to cover the payable_amount
if ($pay_amount < $customer['payable_amount']) {
    echo "The payment amount must be greater than or equal to the payable amount of " . $customer['payable_amount'] . " tk.";
    exit();
}

// Check if balance is enough
if ($customer['balance'] < $pay_amount) {
    echo "Insufficient balance! Add money to make payment.";

    exit();
}



// Check if account number and pin match
if ($customer['account_number'] !== $account || $customer['pin'] !== $pin) {
    echo "Account number or PIN is incorrect. Please try again.";
    exit();
}

// Process the payment: Update customer balance and service order
$new_balance = $customer['balance'] - $pay_amount;

// Begin a transaction
$conn->begin_transaction();

try {
    // Update customer balance
    $update_balance_query = "UPDATE customer SET amount = ? WHERE customer_id = ?";
    $update_stmt = $conn->prepare($update_balance_query);
    $update_stmt->bind_param("di", $new_balance, $customer_id);
    $update_stmt->execute();

    // Update worker balance (assuming worker has a `balance` attribute)
    $update_worker_balance_query = "UPDATE worker SET balance = balance + ? WHERE worker_id = ?";
    $update_worker_stmt = $conn->prepare($update_worker_balance_query);
    $update_worker_stmt->bind_param("di", $pay_amount, $worker_id);
    $update_worker_stmt->execute();

    // Update the service_order table, marking payment as done
    $update_order_query = "UPDATE service_order SET customer_paid = 'yes' WHERE order_id = ?";
    $update_order_stmt = $conn->prepare($update_order_query);
    $update_order_stmt->bind_param("i", $order_id);
    $update_order_stmt->execute();

    // Commit transaction
    $conn->commit();

    echo "Payment successful!";
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo "Payment failed. Please try again.";
}

// Close connection
$conn->close();
