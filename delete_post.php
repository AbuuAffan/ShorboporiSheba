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

    // Prepare the delete query to remove the post
    $delete_sql = "DELETE FROM post WHERE post_id = ?";
    if ($stmt = $conn->prepare($delete_sql)) {
        $stmt->bind_param("i", $post_id);
        if ($stmt->execute()) {
            // Successfully deleted the post
            echo "<script>
                    alert('Post has been deleted successfully.');
                    window.location.href = 'profile.php'; // Redirect back to profile page
                  </script>";
        } else {
            echo "Error deleting post: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Failed to prepare the delete statement: " . $conn->error;
    }
} else {
    echo "Invalid request. No post ID provided.";
}

$conn->close();
