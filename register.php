<?php

include 'connect.php';

// registration backend 

// Customer Signup
if (isset($_POST['signupC'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_no = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Profile picture upload for customers
    $profile_picture = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'imageCustomer/';
        $file_name = basename($_FILES['profile_pic']['name']);
        $profile_picture = $upload_dir . $file_name;

        // Move the uploaded file to the specified folder
        if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_picture)) {
            echo "Failed to upload customer profile picture.";
            exit();
        }
    }


    // Check if the email already exists
    $checkEmail = "SELECT * From customer where email='$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        // Insert customer data into the database
        $query = "INSERT INTO customer (name, email, phone_no, address, password, profile_pic) 
                  VALUES ('$full_name', '$email', '$phone_no', '$address', '$hashed_password', '$profile_picture')";

        if ($conn->query($query) == TRUE) {
            header("location: login.php");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Worker Signup
if (isset($_POST['signupW'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_no = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['location']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $base_price = mysqli_real_escape_string($conn, $_POST['base_price']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $service_specialty = mysqli_real_escape_string($conn, $_POST['service']);



    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Profile picture upload for workers
    $profile_picture = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'imageWorker/';
        $file_name = basename($_FILES['profile_pic']['name']);
        $profile_picture = $upload_dir . $file_name;


        // Move the uploaded file to the specified folder
        if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_picture)) {
            echo "Failed to upload worker profile picture.";
            exit();
        }
    }

    // Insert worker data into the database
    $query = "INSERT INTO worker (name, email, phone_no, location, experience,base_price, bio, service_specialty, password, profile_pic) 
              VALUES ('$full_name', '$email', '$phone_no', '$address','$experience',  $base_price, '$bio', '$service_specialty',  '$hashed_password', '$profile_picture')";

    if (mysqli_query($conn, $query)) {
        header("location: login.php");
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
