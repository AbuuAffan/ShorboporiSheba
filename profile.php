<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
  // If not logged in, redirect to the login page
  header("Location: signin.php");
  exit();
}

include 'connect.php'; // Include your database connection file

// Get the email from the session
$email = $_SESSION['email'];
$customer_id = $_SESSION['customer_id'];

// Prepare an SQL statement to prevent SQL injection
$sql = "SELECT * FROM customer WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
  // Fetch the user's data
  $row = $result->fetch_assoc();

  // Store the user's data in variables
  $name = htmlspecialchars($row['name']);
  $email = htmlspecialchars($row['email']);
  $address = htmlspecialchars($row['address']);
  $profile_image = htmlspecialchars($row['profile_pic']);
  $balance = htmlspecialchars($row['amount']);
} else {
  echo "User not found.";
  exit();
}

// Close the statement and connection

// Get the number of posts created by the customer
$post_count_sql = "SELECT COUNT(*) AS post_count FROM post WHERE customer_id = ?";
$stmt_post_count = $conn->prepare($post_count_sql);
$stmt_post_count->bind_param("i", $customer_id);
$stmt_post_count->execute();
$post_count_result = $stmt_post_count->get_result();
$post_count = $post_count_result->fetch_assoc()['post_count'];

// Get the number of requests made by the customer
$request_count_sql = "SELECT COUNT(*) AS request_count FROM request WHERE customer_id = ?";
$stmt_request_count = $conn->prepare($request_count_sql);
$stmt_request_count->bind_param("i", $customer_id);
$stmt_request_count->execute();
$request_count_result = $stmt_request_count->get_result();
$request_count = $request_count_result->fetch_assoc()['request_count'];

$stmt->close();
// Close statements for post and request counts
$stmt_post_count->close();
$stmt_request_count->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="website icon" type="png" href="Images/color monogram-8.png" />
  <link rel="stylesheet" href="profile.css" />
  <title>Profile</title>
  <style>
    .main_container {
      max-width: 600px;
      /* Adjust the size to match the compact design */
      margin: 50px auto;
      padding: 20px;
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
      text-align: center;
      position: relative;
      z-index: 1;
      background-color: #ffffff;
      overflow: hidden;
      /* For smooth corners */
      border: 2px solid #E15197;
      /* Add border as seen in the image */
    }

    .main_container img {
      width: 100%;
      /* Make image take full width for styling */
      height: 200px;
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
      object-fit: cover;
    }

    .profile-info {
      padding: 15px 20px;
      text-align: left;
      /* Align text to the left */
    }

    .profile-info h1 {
      font-size: 18px;
      margin-bottom: 5px;
      color: #333;
      font-weight: bold;
    }

    .profile-info p {
      font-size: 14px;
      margin-bottom: 5px;
      color: #555;
      line-height: 1.4;
    }

    .profile-info .balance {
      margin-top: 10px;
      font-size: 20px;
      font-weight: bold;
      color: #00b300;
      text-align: left;
      /* Keep balance aligned to the left */
    }

    .post-info {
      font-size: 14px;
      color: #555;
      margin-top: 5px;
      text-align: left;
    }

    .order_btns {
      margin-top: 20px;
      display: flex;
      justify-content: space-around;
      /* Distribute buttons evenly */
    }

    .order_btns a {
      width: 45%;
      padding: 10px;
      border-radius: 20px;
      text-align: center;
      font-size: 14px;
      background-color: #E15197;
      color: white;
      font-weight: bold;
      text-decoration: none;
      transition: background-color 0.3s;
    }

    .order_btns a:hover {
      background-color: #9e1238;
    }
  </style>
</head>
<!-- Customer profile frontend -->

<body>
  <div class="header_container">
    <div class="left_part">
      <div class="image_container">
        <a href="Cdashboard.php">
          <img src="Images/lImage.png" alt="logo of Shorboporisheba" class="logo" />
        </a>
      </div>
    </div>
    <div class="right_part">
      <form action="profile.php" method="get">
        <a href="post.php" class="btn">Create Post</a>
        <a href="service_request.html" class="btn">Direct Request</a>
        <button type="submit" class="btn" name="profileButton">Profile</button>
      </form>
    </div>
  </div>

  <div class="main_container">
    <!-- Profile Picture at the top -->
    <img src="<?php echo $profile_image; ?>" alt="Profile Picture">

    <!-- Information Section -->
    <div class="profile-info">
      <h1><?php echo $name; ?></h1>
      <p><?php echo $email; ?></p>
      <p><?php echo $address; ?></p>

      <p class="balance">Balance: <?php echo "$balance tk"; ?></p>

      <div class="post-info">
        <p>Total Posts Created: <?php echo $post_count; ?></p>
        <p>Total Requests Made: <?php echo $request_count; ?></p>
      </div>
    </div>

    <!-- Buttons Section -->
    <div class="order_btns">
      <a href="order_history.php">Order History</a>
      <a href="accepted_services.php">Accepted Order</a>
    </div>
  </div>


  <div style="max-width: 600px; margin: 50px auto; border: 2px solid #E15197; padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5); text-align: left;">

    <?php
    // Prepare an SQL statement to get all posts created by this customer
    $sql_posts = "SELECT * FROM post WHERE customer_id = (SELECT customer_id FROM customer WHERE email = ?)";
    $stmt_posts = $conn->prepare($sql_posts);
    $stmt_posts->bind_param("s", $email);
    $stmt_posts->execute();
    $result_posts = $stmt_posts->get_result();

    // Check if the customer has posts
    if ($result_posts->num_rows > 0) {
      while ($post = $result_posts->fetch_assoc()) {
        $post_id = $post['post_id'];
        $post_title = htmlspecialchars($post['Post_title']);
        $post_description = htmlspecialchars($post['description']);
        $post_date = htmlspecialchars($post['post_date']);
        $location = htmlspecialchars($post['location']);
        $price = htmlspecialchars($post['price']);
        $service_schedule = htmlspecialchars($post['service_schedule']);

        // Display post details
        echo '<div class="post" style="border-bottom: 1px solid #ddd; padding: 15px 0;">';

        // Add profile picture, user name, and post date
        echo '<div style="display: flex; align-items: center;">';
        echo '<img src="' . $profile_image . '" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; margin-right: 10px;" alt="Profile Picture">';
        echo '<div>';
        echo '<p style="font-weight: bold; margin: 0;">' . $name . '</p>';
        echo '<p style="color: #888; font-size: 12px; margin: 0;">' . $post_date . '</p>';
        echo '</div>';
        echo '<form action="edit_post.php" method="post" style="display:inline-block; margin-left:20px;">';
        echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
        echo '<button type="submit" class="btn" style="padding: 5px 5px; font-size: 13px;">Edit Post</button>';
        echo '</form>';
        echo '<form action="delete_post.php" method="post" style="display:inline-block; margin-left: 10px;">';
        echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
        echo '<button type="submit" class="btn" style="padding: 5px 5px; font-size: 13px;">Delete Post</button>';
        echo '</form>';
        echo '</div>';

        // Post content
        echo '<div style="margin-top: 10px; padding-bottom: 10px;">';
        echo '<p style="font-size: 16px; margin: 0;">' . $post_description . '</p>';
        echo '<p style="color: #888; font-size: 12px; margin: 0;">Location: ' . $location . ' | Price: ' . $price . ' BDT</p>';
        echo '<p style="color: #888; font-size: 12px; margin: 0;">Service Schedule: ' . $service_schedule . '</p>';
        echo '</div>';

        // Add edit and delete buttons for the post

        // Separator line between post and comments
        echo '<hr style="border: 0; border-top: 1px solid #ddd;">';

        // Fetch comments related to this post
        $sql_comments = "SELECT comment.*, worker.name AS worker_name, worker.profile_pic AS worker_profile_pic, 
                         (SELECT AVG(r.rating_score) FROM rating r WHERE r.worker_id = worker.worker_id) AS worker_rating 
                         FROM comment 
                         JOIN worker ON comment.worker_id = worker.worker_id 
                         WHERE post_id = ?";
        $stmt_comments = $conn->prepare($sql_comments);
        $stmt_comments->bind_param("i", $post_id);
        $stmt_comments->execute();
        $result_comments = $stmt_comments->get_result();

        // Check if there are any comments for the post
        if ($result_comments->num_rows > 0) {
          echo '<div class="comments" style="margin-top: 10px;">';
          while ($comment = $result_comments->fetch_assoc()) {
            $comment_description = htmlspecialchars($comment['description']);
            $comment_time = htmlspecialchars($comment['comment_time']);
            $worker_name = htmlspecialchars($comment['worker_name']);
            $worker_profile_pic = htmlspecialchars($comment['worker_profile_pic']);
            $worker_rating = htmlspecialchars($comment['worker_rating']); // Fetch the calculated rating

            // Display each comment with worker information
            echo '<div class="comment" style="display: flex; align-items: flex-start; margin-top: 10px;">';
            echo '<img src="' . $worker_profile_pic . '" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;" alt="Worker Profile">';
            echo '<div>';

            // Display worker name as a clickable link to view the worker's profile
            echo '
            <form id="worker_form_' . $comment['worker_id'] . '" action="worker_profile.php" method="get" style="display: inline;">
                <input type="hidden" name="worker_id" value="' . htmlspecialchars($comment['worker_id']) . '">
                <a href="javascript:void(0);" onclick="document.getElementById(\'worker_form_' . $comment['worker_id'] . '\').submit();" style="font-weight: bold; text-decoration: none; color: #007bff;">
                 ' . htmlspecialchars($worker_name) . '
                </a>
            </form>
            <span style="font-size: 12px; color: #888;">(Rating: ' . htmlspecialchars(number_format((float)$worker_rating, 1, '.', '')) . ')</span>';

            echo '<p style="font-size: 14px; margin: 0;">' . $comment_description . '</p>';
            echo '<p style="color: #888; font-size: 12px; margin: 0;">Commented on: ' . $comment_time . '</p>';
            echo '</div>';
            echo '</div>';
          }
          echo '</div>';
        } else {
          echo '<p>No comments yet.</p>';
        }

        echo '</div>'; // Close post div
      }
    } else {
      echo '<p>No posts found.</p>';
    }

    // Close statements and connection
    $stmt_posts->close();
    $conn->close();
    ?>
  </div>
</body>

</html>