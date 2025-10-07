<?php
// Start session
session_start();

// Include the database connection file
include '../connect.php'; // Make sure this file contains your database connection

// Check if the worker is logged in
if (!isset($_SESSION['worker_id'])) {
    echo "You need to be logged in to see the posts.";
    exit();
}

// Fetch worker_id from the session
$worker_id = $_SESSION['worker_id'];

// Fetch worker details from the worker table
$query_worker = "SELECT location, service_specialty FROM worker WHERE worker_id = ?";
if ($stmt_worker = $conn->prepare($query_worker)) {
    $stmt_worker->bind_param("i", $worker_id);
    $stmt_worker->execute();
    $result_worker = $stmt_worker->get_result();
    $worker_data = $result_worker->fetch_assoc();

    if ($worker_data) {
        // Store worker's location and service specialty
        $worker_location = $worker_data['location'];
        $worker_service = $worker_data['service_specialty'];

        // Fetch service_id based on worker's service from the service table
        $query_service = "SELECT service_id FROM service WHERE service_name = ?";
        if ($stmt_service = $conn->prepare($query_service)) {
            $stmt_service->bind_param("s", $worker_service);
            $stmt_service->execute();
            $result_service = $stmt_service->get_result();
            $service_data = $result_service->fetch_assoc();

            if ($service_data) {
                $service_id = $service_data['service_id'];

                // Fetch posts matching worker's location and service_id from the post table
                $query_posts = "SELECT * FROM post WHERE location = ? AND service_id = ?";

                if ($stmt_posts = $conn->prepare($query_posts)) {
                    $stmt_posts->bind_param("si", $worker_location, $service_id);
                    $stmt_posts->execute();
                    $result_posts = $stmt_posts->get_result();

                    // Check if any posts match the criteria
                    if ($result_posts->num_rows > 0) {
?>
                        <!DOCTYPE html>
                        <html lang="en">

                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <link rel="icon" type="image/png" href="../images/color monogram-8.png" />
                            <link rel="stylesheet" href="see_work_post.css">
                            <title>See Work Posts</title>
                        </head>

                        <body>



                            <div class="header_container">
                                <div class="left_part">
                                    <div class="image_container">
                                        <a href="../Wdashboard.html"><img
                                                src="../Images/lImage.png"
                                                alt="logo of Shorboporisheba"
                                                class="logo" /></a>
                                    </div>
                                </div>
                                <div class="right_part">
                                    <a href="See_work_post.php" class="btn">Click To Refresh</a>

                                    <a href="See_work_request.php" class="btn">See Work Request</a>
                                    <a href="../support.php" class="btn">Support</a>

                                    <a href="WorkerProfile.php" class="btn">Profile</a>
                                </div>

                            </div>
                            <div class="main_container" style="margin-top: 70px;">
                                <?php while ($post = $result_posts->fetch_assoc()) { ?>
                                    <div class="post_card">
                                        <h2><?php echo htmlspecialchars($post['Post_title']); ?></h2>
                                        <p><strong>Location:</strong> <?php echo htmlspecialchars($post['location']); ?></p>
                                        <p><strong>Offer Price:</strong> <?php echo htmlspecialchars($post['price']); ?></p>
                                        <p><strong>Schedule (Work date):</strong>
                                            <?php echo htmlspecialchars(date('Y-m-d', strtotime($post['service_schedule']))); ?>
                                        </p>
                                        <p><strong>Description:</strong> <?php echo htmlspecialchars($post['description']); ?></p>

                                        <p style="font-size: 15px;"><strong>Date Posted:</strong> <?php echo htmlspecialchars($post['post_date']); ?></p>

                                        <!-- Add comment section for worker response -->
                                        <form method="POST" action="submit_comment.php">
                                            <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                                            <textarea name="comment" class="comment_input" placeholder="Write your response..." required></textarea>
                                            <button type="submit" class="btn_submit">Submit Response</button>
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>
                        </body>

                        </html>
<?php
                    } else {
                        // No posts found
                        echo "No posts available for your location and service.";
                    }
                } else {
                    echo "Failed to fetch posts.";
                }
            } else {
                echo "Service not found for the worker's specialty.";
            }
        } else {
            echo "Failed to fetch service data.";
        }
    } else {
        echo "Worker data not found.";
    }
} else {
    echo "Failed to fetch worker data.";
}

// Close the database connection
$conn->close();
?>