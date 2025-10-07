<?php
// Start session
session_start();

// Include the database connection file
include '../connect.php'; // Ensure this file contains your database connection

// Check if the worker is logged in
if (!isset($_SESSION['worker_id'])) {
    echo "You need to be logged in to submit a response.";
    exit();
}

// Fetch worker_id from the session
$worker_id = $_SESSION['worker_id'];

// Check if the form was submitted with the required fields
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'], $_POST['comment'])) {
    // Get the post_id and comment from the form
    $post_id = $_POST['post_id'];
    $comment_description = $_POST['comment'];

    // Check if the worker has already commented on this post
    $check_query = "SELECT * FROM comment WHERE post_id = ? AND worker_id = ?";
    if ($check_stmt = $conn->prepare($check_query)) {
        $check_stmt->bind_param("ii", $post_id, $worker_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        // If the worker has already commented on this post
        if ($check_result->num_rows > 0) {
            // Worker has already commented on this post
            echo "<script>
                    alert('You have already commented on this post. You cannot comment again.');
                    window.location.href = 'see_work_post.php'; // Redirect back to the post page
                  </script>";
            exit();
        }

        // If the worker has not commented yet, allow them to comment
        $check_stmt->close(); // Close the previous prepared statement

        // Get the current timestamp for comment_time
        $comment_time = date('Y-m-d H:i:s');

        // Insert the comment into the comment table
        $query = "INSERT INTO comment (post_id, worker_id, comment_time, description) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($query)) {
            // Bind parameters (post_id, worker_id, comment_time, description)
            $stmt->bind_param("iiss", $post_id, $worker_id, $comment_time, $comment_description);

            // Execute the statement
            if ($stmt->execute()) {
                // Success, redirect to see_work_post.php or another page
                echo "<script>
                        alert('Your response has been submitted successfully!');
                        window.location.href = 'see_work_post.php'; // Redirect back to the post page
                      </script>";
                exit();
            } else {
                // Error during execution
                echo "Failed to submit response: " . $conn->error;
            }
        } else {
            // Error preparing the SQL statement
            echo "Database query failed.";
        }
    } else {
        // Error preparing the check query
        echo "Database query failed.";
    }
} else {
    // Form was not submitted correctly
    echo "Invalid submission.";
}

// Close the database connection
$conn->close();
