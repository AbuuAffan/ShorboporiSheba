<?php
session_start();
include '../connect.php'; // Include your database connection file

// Ensure the worker is logged in
if (!isset($_SESSION['worker_id'])) {
    header("Location: signin.php");
    exit();
}

$worker_id = $_SESSION['worker_id'];

// Fetch worker details
$worker_sql = "SELECT * FROM worker WHERE worker_id = ?";
$stmt_worker = $conn->prepare($worker_sql);
$stmt_worker->bind_param("i", $worker_id);
$stmt_worker->execute();
$worker_result = $stmt_worker->get_result();

// Check if worker exists
if ($worker_result->num_rows > 0) {
    $worker = $worker_result->fetch_assoc();
    $name = htmlspecialchars($worker['name']);
    $email = htmlspecialchars($worker['email']);
    $address = htmlspecialchars($worker['location']);
    $profile_image = htmlspecialchars($worker['profile_pic']);
    $balance = htmlspecialchars($worker['balance']);
    $experience = htmlspecialchars($worker['experience']);
    $bio = htmlspecialchars($worker['bio']);
} else {
    echo "Worker not found.";
    exit();
}
$stmt_worker->close();

// Fetch posts where this worker commented
$comments_sql = "
    SELECT p.post_id, p.Post_title, p.description, p.post_date, p.location, p.price, p.service_schedule 
    FROM comment c 
    JOIN post p ON c.post_id = p.post_id 
    WHERE c.worker_id = ?
";
$stmt_comments = $conn->prepare($comments_sql);
$stmt_comments->bind_param("i", $worker_id);
$stmt_comments->execute();
$comments_result = $stmt_comments->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="../images/color monogram-8.png" />
    <link rel="stylesheet" href="WorkerProfile.css" />
    <title>Worker Dashboard</title>
    <style>
        .main_container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            border: 2px solid #E15197;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            text-align: center;
            color: #333;
        }

        .worker_profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;

        }

        .worker_profile img {
            width: 500px;
            height: 250px;
            border-radius: 15px;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .worker_details p {
            text-align: left;
            margin: 8px 0;
            font-size: 18px;
        }

        .balance {
            font-weight: bold;
            font-size: 24px;
            color: green;
            margin-top: 15px;
        }

        .action_buttons {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .action_buttons button {
            background-color: #f06292;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            margin: 0 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .action_buttons button:hover {
            background-color: #e91e63;
        }

        .posts_container {
            margin-top: 30px;
            text-align: left;
            margin-bottom: 20px;
        }

        .post {
            background-color: white;
            color: #333;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .post p {
            margin: 0;
        }

        .comment_button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: default;
        }

        .comment_button:hover {
            background-color: #0056b3;
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

            <form action="toggle_status.php" method="post">
                <a href="See_work_post.php" class="btn">See Work Post</a>
                <a href="See_work_request.php" class="btn">See Work Request</a>
                <a href="../support.php" class="btn">Support</a>

                <a href="WorkerProfile.php" class="btn">Profile</a>
                <input type="hidden" name="worker_id" value="<?php echo $worker_id; ?>">
                <button type="submit" style="height:40px; margin-top:10px" class="btn">
                    <?php echo ($worker['availability_status'] == 'available') ? 'Set Busy' : 'Set Available'; ?>
                </button>
                <a href="../logout.php" class="btn">Log Out</a>
            </form>


        </div>
    </div>

    <div class="main_container">
        <!-- Worker Profile Section -->
        <div class="worker_profile">
            <img src="<?php echo "../$profile_image" ?>" alt="Worker Profile Picture">
            <div class="worker_details">
                <p><strong>Name:</strong> <?php echo $name; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>Location:</strong> <?php echo $address; ?></p>
                <p><strong>Experience:</strong> <?php echo $experience; ?> years</p>
                <p><strong>Bio:</strong> <?php echo $bio; ?></p>
                <p class="balance">Balance: <?php echo $balance; ?> tk</p>
            </div>
        </div>
        <div class="action_buttons">
            <button onclick="window.location.href='orders_complete.php';">Order History</button>
            <button onclick="window.location.href='worker_orders.php';">Accepted Order</button>
        </div>

        <!-- Display the posts this worker commented on -->
        <div class="posts_container">
            <h2>Posts You've Commented On</h2>

            <?php if ($comments_result->num_rows > 0) : ?>
                <?php while ($post = $comments_result->fetch_assoc()) : ?>
                    <div class="post">
                        <h3><?php echo htmlspecialchars($post['Post_title']); ?></h3>
                        <p><?php echo htmlspecialchars($post['description']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($post['location']); ?></p>
                        <p><strong>Price:</strong> <?php echo htmlspecialchars($post['price']); ?> BDT</p>
                        <p><strong>Service Schedule:</strong> <?php echo htmlspecialchars($post['service_schedule']); ?></p>
                        <button class="comment_button">Commented</button>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <p>No comments found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>