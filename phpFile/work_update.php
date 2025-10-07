<?php
session_start();
include '../connect.php'; // Include the database connection

// Check if the order_id is set in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order details from the service_order table
    $query = "SELECT customer_id, worker_id FROM service_order WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order_details = $result->fetch_assoc();

    // Check if order details are available
    if ($order_details) {
        $customer_id = $order_details['customer_id'];
        $worker_id = $order_details['worker_id'];
    } else {
        echo "Order details not found.";
        exit();
    }
} else {
    echo "No order ID provided.";
    exit();
}

// Check if the form is submitted (for the "YES" button)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Begin a transaction to update the order and insert into the payment table
    $conn->begin_transaction();

    try {
        // Update the service_order table to mark the order as finished
        $update_order_query = "UPDATE service_order SET order_status = 'finished', worker_receive = 'yes' WHERE order_id = ?";
        $update_stmt = $conn->prepare($update_order_query);
        $update_stmt->bind_param("i", $order_id);
        $update_stmt->execute();

        // Insert the order details into the payment table
        $insert_payment_query = "INSERT INTO payment (order_id, customer_id, worker_id) VALUES (?, ?, ?)";
        $insert_payment_stmt = $conn->prepare($insert_payment_query);
        $insert_payment_stmt->bind_param("iii", $order_id, $customer_id, $worker_id);
        $insert_payment_stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Show a success message and redirect to another page if needed
        echo "<script>alert('Order marked as finished and payment recorded.'); window.location.href = 'WorkerProfile.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction if there's an error
        $conn->rollback();
        echo "Failed to update order and payment. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Update</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .header_container {
            width: 98.8vw;
            height: 65px;
            background-color: #041E41;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-left: 100px;
            padding-right: 100px;
        }

        .image_container img {
            width: 150px;
            height: 50px;
            object-fit: cover;
        }

        .btn {
            text-decoration: none;
            background-color: #E15197;
            color: white;
            padding: 10px 20px;
            margin-left: 10px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .logo:hover {
            cursor: pointer;
        }

        .btn:hover {
            background-color: #9e1238;
        }

        .main {
            max-width: 900px;
            margin: 50px auto;
            padding-left: 100px;
            padding-right: 100px;
            background-color: #041E41;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white;
        }
    </style>
</head>

<body>
    <div class="header_container">
        <div class="left_part">
            <div class="image_container">
                <a href="../Wdashboard.html"><img src="../Images/lImage.png" alt="logo of Shorboporisheba" class="logo" /></a>
            </div>
        </div>
        <div class="right_part">
            <a href="See_work_post.php" class="btn">See Work Post</a>
            <a href="See_work_request.php" class="btn">See Work Request</a>
            <a href="../support.php" class="btn">Support</a>

            <a href="WorkerProfile.php" class="btn">Profile</a>
            <a href="../logout.php" class="btn">Log Out</a>
        </div>
    </div>

    <div class="main">
        <div>
            <h1 style="padding-bottom: 20px;">Check your balance from your profile</h1>
            <a href="WorkerProfile.php" style="padding-bottom: 20px;font-size: 20px;color:aliceblue;">Profile Link</a>

            <h2 style="padding-bottom: 50px;margin-top:20px">Did you receive your payment? Click the button below if you did.</h2>

            <!-- Form for the "YES" button -->
            <form method="POST">
                <button type="submit" class="btn" style="margin-left: 300px;margin-bottom:50px">YES</button>
            </form>
        </div>
    </div>

</body>

</html>