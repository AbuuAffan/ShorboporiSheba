<?php
session_start();
// Include the database connection file
include 'connect.php';



// Check if the form was submitted
if (isset($_POST['posting'])) {
    // Get form data
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $service = mysqli_real_escape_string($conn, $_POST['service']);
    $postTitle = mysqli_real_escape_string($conn, $_POST['postTitle']);
    $postPrice = mysqli_real_escape_string($conn, $_POST['postPrice']);
    $serviceDate = mysqli_real_escape_string($conn, $_POST['serviceDate']);
    $postContent = mysqli_real_escape_string($conn, $_POST['postContent']);

    // Assume customer_id is stored in session, extract it like this:


    $customer_id = $_SESSION['customer_id']; // Make sure the session is started and this variable is set during login

    // Step 1: Fetch the service_id from the service table based on the service name
    $sql = "SELECT service_id FROM service WHERE service_name = ?";
    $stmt = $conn->prepare($sql);

    // Check if the statement was prepared correctly 
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind the service name parameter
    $stmt->bind_param("s", $service);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a matching service was found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $service_id = $row['service_id']; // Extract the service_id from the result

    } else {
        die('Service not found.');
    }

    // Close the statement
    $stmt->close();

    // Prepare SQL statement to insert into the `post` table using placeholders to prevent SQL injection
    $sql = "INSERT INTO post (customer_id, service_id, post_date, location, price, service_schedule, description, Post_title)
            VALUES (?, ?, NOW(), ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Check if the statement was prepared correctly
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind the parameters (i: integer, d: double, s: string)
    // Bind: customer_id (int), service_id (int), location (string), price (decimal), service_schedule (string), description (string), Post_title (string)
    $stmt->bind_param("iisdsss", $customer_id, $service_id, $location, $postPrice, $serviceDate, $postContent, $postTitle);
    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>
                    alert('Your response has been submitted successfully!');
                    window.location.href = 'Cdashboard.php'; // Redirect back to the post page
                  </script>";
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "If you are seeing this massage it's means your system is not working well";
}
