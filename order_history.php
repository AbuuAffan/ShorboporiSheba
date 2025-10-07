<?php
session_start();
include 'connect.php'; // Include the database connection

// Ensure the customer is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: signin.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Fetch completed orders for this customer
$order_sql = "
    SELECT so.order_id, s.service_name, so.order_date, so.payable_amount, so.order_status, w.name AS worker_name
    FROM service_order so
    JOIN service s ON so.service_id = s.service_id
    JOIN worker w ON so.worker_id = w.worker_id
    WHERE so.customer_id = ? AND so.order_status = 'finished'
";
$stmt_order = $conn->prepare($order_sql);
$stmt_order->bind_param("i", $customer_id);
$stmt_order->execute();
$order_result = $stmt_order->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="Images/color monogram-8.png" />
    <title>Order History</title>

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

        .btn:hover {
            background-color: #9e1238;
        }

        .main_container {
            max-width: 900px;
            margin: 50px auto;
            padding: 40px;
            background-color: #041E41;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white;
        }

        .order_card {
            background-color: white;
            color: #333;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .order_card p {
            margin: 0;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="header_container">
        <div class="left_part">
            <div class="image_container">
                <a href="Cdashboard.php"><img src="Images/lImage.png" alt="logo of Shorboporisheba" class="logo" /></a>
            </div>
        </div>
        <div class="right_part">

            <a href="profile.php" class="btn">Profile</a>
        </div>
    </div>

    <div class="main_container">
        <h1 style="text-align: center; padding-top: 30px; padding-bottom: 30px;">Your Order History</h1>

        <?php if ($order_result->num_rows > 0) : ?>
            <?php while ($order = $order_result->fetch_assoc()) : ?>
                <div class="order_card">
                    <p>Order ID: <strong><?php echo htmlspecialchars($order['order_id']); ?></strong></p>
                    <p>Service: <strong><?php echo htmlspecialchars($order['service_name']); ?></strong></p>
                    <p>Worker: <strong><?php echo htmlspecialchars($order['worker_name']); ?></strong></p>
                    <p>Order Date: <strong><?php echo htmlspecialchars($order['order_date']); ?></strong></p>
                    <p>Amount Paid: <strong><?php echo htmlspecialchars($order['payable_amount']); ?> tk</strong></p>
                    <p>Order Status: <strong style=" color:green; "><?php echo htmlspecialchars($order['order_status']); ?></strong></p>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p style="text-align: center;">No completed orders found.</p>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
// Close the statement and connection
$stmt_order->close();
$conn->close();
?>