<?php
session_start();

// Check if the customer is logged in
if (!isset($_SESSION['customer_id'])) {
    // Redirect to login if the customer is not logged in
    header("Location: signin.php");
    exit();
}

// Include your database connection
include 'connect.php';

// Fetch the logged-in customer's ID from the session
$customer_id = $_SESSION['customer_id'];

// Query to fetch accepted service requests for this customer, including worker name
$query = "
    SELECT so.order_id, s.service_name, so.order_date,so.order_status, so.payable_amount, w.name AS worker_name 
    FROM service_order so
    JOIN service s ON so.service_id = s.service_id
    JOIN worker w ON so.worker_id = w.worker_id
    WHERE so.customer_id = ? AND so.order_status = 'on_going'
";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Error: Could not prepare the query.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="website icon" type="png" href="Images/color monogram-8.png" />
    <link rel="stylesheet" href="as.css">
    <title>Accepted Services</title>

</head>

<body>
    <div class="header_container">
        <div class="left_part">
            <div class="image_container">
                <a href="Cdashboard.php"><img
                        src="Images/lImage.png"
                        alt="logo of Shorboporisheba"
                        class="logo" /></a>
            </div>
        </div>
        <div class="right_part">

            <form action="profile.php" method="get">

                <a href="support.php" class="btn">Support</a>
                <a href="wallet.php" class="btn">Add Wallet</a>
                <button type="submit" class="btn" name="profileButton">Profile</button>
                <a href="logout.php" class="btn">Log Out</a>
            </form>

        </div>
    </div>

    <div class="container" style=" border: 2px solid #E15197;box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);">
        <h1>Accepted Service Requests</h1>

        <?php if ($result->num_rows > 0) { ?>
            <ul class="service-list">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <li class="service-item">
                        <p><strong><?php echo htmlspecialchars($row['worker_name']); ?></strong> accepted your <strong><?php echo htmlspecialchars($row['service_name']); ?></strong> request.</p>
                        <p><strong>Payable Amount:</strong> <?php echo htmlspecialchars($row['payable_amount']); ?> tk</p>
                        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($row['order_date']); ?></p>
                        <a href="order_service_details.php?order_id=<?php echo $row['order_id']; ?>" class="btn-view-details">View Details</a>
                        <a href="chat.php?order_id=<?php echo $row['order_id']; ?>" class="btn-view-details">Chat</a>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No accepted service requests yet.</p>
        <?php } ?>
    </div>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>