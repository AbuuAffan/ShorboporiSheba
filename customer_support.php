<?php
session_start();
include 'connect.php'; // Include your database connection file

// Check if the user is logged in as either customer or worker
if (!isset($_SESSION['customer_id']) && !isset($_SESSION['worker_id'])) {
    echo "Unauthorized access. Please log in.";
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

// Fetch support tickets submitted by this user
$query = "SELECT * FROM support_tickets WHERE user_id = ? AND user_type = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $user_type);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="website icon" type="png" href="Images/color monogram-8.png" />
    <title>Support Tickets</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .ticket {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .ticket h3 {
            margin-top: 0;
            color: #333;
        }

        .ticket p {
            margin: 5px 0;
            color: #555;
        }

        .header_container {
            margin-bottom: 20px;
        }

        .header_container a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #E15197;
            color: white;
            border-radius: 5px;
        }

        .header_container a:hover {
            background-color: #9e1238;
        }
    </style>
</head>

<body>

    <div class="header_container">
        <?php if ($user_type == 'customer'): ?>
            <a href="Cdashboard.php">Back to Customer Dashboard</a>
        <?php elseif ($user_type == 'worker'): ?>
            <a href="Wdashboard.html">Back to Worker Dashboard</a>
        <?php endif; ?>
    </div>

    <h1>Your Support Tickets</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="ticket">
                <h3>Subject: <?php echo htmlspecialchars($row['subject']); ?></h3>
                <p><strong>Message:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
                <p><strong>Admin Response:</strong> <?php echo $row['admin_response'] ? htmlspecialchars($row['admin_response']) : "No response yet."; ?></p>
                <p><strong>Created At:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No support tickets found.</p>
    <?php endif; ?>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>