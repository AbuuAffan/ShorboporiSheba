<?php
session_start();
include '../connect.php'; // Include the database connection

// Ensure the worker is logged in
if (!isset($_SESSION['worker_id'])) {
    header("Location: signin.php");
    exit();
}

$worker_id = $_SESSION['worker_id'];

// Fetch the orders for this worker
$order_sql = "
    SELECT so.order_id, c.name AS customer_name, s.service_name, so.payable_amount, so.order_date, so.order_status 
    FROM service_order so 
    JOIN customer c ON so.customer_id = c.customer_id 
    JOIN service s ON so.service_id = s.service_id 
    WHERE so.worker_id = ? AND so.order_status = 'on_going'
";
$stmt_order = $conn->prepare($order_sql);
$stmt_order->bind_param("i", $worker_id);
$stmt_order->execute();
$order_result = $stmt_order->get_result();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/color monogram-8.png" />
    <title>Worker Orders</title>
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
            justify-items: center;
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
            /* Removes underline from the link */
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



        .main_container {
            max-width: 900px;
            margin: 50px auto;
            padding-left: 100px;
            padding-right: 100px;
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



    <div class="main_container">
        <h1 style="margin-left: 250px;padding-top: 30px;padding-bottom: 30px;">Your Orders</h1>

        <?php if ($order_result->num_rows > 0) : ?>
            <?php while ($order = $order_result->fetch_assoc()) : ?>
                <div class="order_card" style="margin-bottom: 30px;">
                    <p>You have an order from <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong> for <strong><?php echo htmlspecialchars($order['service_name']); ?></strong> service.</p>
                    <p>After finishing, you'll get <strong><?php echo htmlspecialchars($order['payable_amount']); ?> tk</strong>.</p>
                    <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                    <button class="btn" onclick="window.location.href='work_update.php?order_id=<?php echo $order['order_id']; ?>';">See Work Update</button>
                    <button class="btn" onclick="window.location.href='Wchat.php?order_id=<?php echo $order['order_id']; ?>';">Chat</button>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>
</body>

</html>