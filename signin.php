<?php

include 'connect.php';
// login backend

if (isset($_POST['login'])) {

    $temp = $_POST['email_phone'];

    // Check if the input contains the word "admin"
    if (strpos(strtolower($temp), 'admin') !== false) {
        // If it contains "admin", it's identified as admin
        echo "Admin found";
    } elseif (strpos($temp, '@') !== false) {
        $email = $_POST['email_phone'];
        $password = $_POST['password'];

        // Fetch the stored hashed password from the database
        $sql = "SELECT * FROM customer WHERE email= ?";



        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the email parameter
        $stmt->bind_param("s", $email);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stored_hashed_password = $row['password'];

            // Use password_verify() to check the entered password against the stored hashed password
            if (password_verify($password, $stored_hashed_password)) {
                session_start();
                $_SESSION['email'] = $row['email'];
                $_SESSION['customer_id'] = $row['customer_id'];
                header("Location: Cdashboard.php");
                exit();
            } else {
                echo "Incorrect Email or Password.";
            }
        } else {
            echo "User not found.";
        }
    } elseif (preg_match('/^[0-9]+$/', $temp)) {
        $phone_no = $_POST['email_phone'];
        $password = $_POST['password'];

        // Fetch the stored hashed password from the database
        $sql = "SELECT * FROM worker WHERE phone_no= ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the phone number parameter
        $stmt->bind_param("s", $phone_no);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stored_hashed_password = $row['password'];

            // Use password_verify() to check the entered password against the stored hashed password
            if (password_verify($password, $stored_hashed_password)) {
                session_start();
                $_SESSION['email'] = $row['email']; // Assuming workers also have an email field
                $_SESSION['worker_id'] = $row['worker_id']; // Store worker ID in session
                header("Location: Wdashboard.html"); // Redirect to the worker dashboard
                exit();
            } else {
                echo "Incorrect Phone Number or Password.";
            }
        } else {
            echo "User not found.";
        }
    } else {
        // If neither, it's an invalid input or unknown format
        echo "Invalid input";
    }
}
