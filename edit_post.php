<?php
session_start();
include 'connect.php'; // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: signin.php");
    exit();
}

// Check if the post_id is set in the POST request
if (isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    // Fetch the existing post data
    $fetch_sql = "SELECT * FROM post WHERE post_id = ?";
    if ($stmt = $conn->prepare($fetch_sql)) {
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $post = $result->fetch_assoc();
        } else {
            echo "Post not found.";
            exit();
        }
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }

    // If the form is submitted for updating the post
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_post'])) {
        $post_title = $_POST['post_title'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $price = $_POST['price'];
        $service_schedule = $_POST['service_schedule'];

        // Update the post data in the database
        $update_sql = "UPDATE post SET Post_title = ?, description = ?, location = ?, price = ?, service_schedule = ? WHERE post_id = ?";
        if ($stmt = $conn->prepare($update_sql)) {
            $stmt->bind_param("sssssi", $post_title, $description, $location, $price, $service_schedule, $post_id);
            if ($stmt->execute()) {
                echo "<script>
                        alert('Post has been updated successfully.');
                        window.location.href = 'profile.php'; // Redirect back to profile page
                      </script>";
            } else {
                echo "Error updating post: " . $conn->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing the update statement: " . $conn->error;
        }
    }
} else {
    echo "Invalid request. No post ID provided.";
}

$conn->close();
?>

<!-- HTML Form for editing the post -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="website icon" type="png" href="Images/color monogram-8.png" />
    <link rel="stylesheet" href="edit_post.css">
    <title>Edit Post</title>
</head>

<body>
    <div class="header_container">
        <div class="left_part">
            <div class="image_container">
                <a href="Cdashboard.php">
                    <img
                        src="Images/lImage.png"
                        alt="logo of Shorboporisheba"
                        class="logo" />
                </a>
            </div>
        </div>
        <div class="right_part">
            <form action="profile.php" method="get">
                <a href="allService.php" class="btn">All Service</a>
                <button type="submit" class="btn" name="profileButton">
                    Profile
                </button>

            </form>
        </div>
    </div>


    <div class="main_container" style="margin-left:500px; max-width: 500px;background-color: #041E41; border-radius: 10px; ">
        <h1 style="margin-top:30px;margin-bottom:30px;font-size:36px;color: aliceblue; padding-top:20px;">Edit Post</h1>
        <div class="form_container">
            <form method="POST" action="edit_post.php" style="display: flex; flex-direction:column">
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

                <p style="color: aliceblue;">Post Title:</p>
                <input type="text" name="post_title" id="post_title" value="<?php echo htmlspecialchars($post['Post_title']); ?>" required>

                <p style="color: aliceblue;">Description:</p>
                <textarea name="description" id="description" rows="5" required><?php echo htmlspecialchars($post['description']); ?></textarea>

                <p style="color: aliceblue;">Price:</p>
                <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($post['price']); ?>" required>


                <p style="color: aliceblue;">Service Schedule:</p>
                <input type="date" name="service_schedule" id="service_schedule" value="<?php echo htmlspecialchars($post['service_schedule']); ?>" required>

                <button type="submit" name="update_post" style="margin-bottom: 40px;">Update Post</button>
            </form>
        </div>
    </div>

</body>

</html>