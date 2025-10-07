<?php
session_start();

include 'connect.php';

// service request backend


// Check if the form was submitted
if (isset($_POST['service'])) {


    // Get the value of the clicked button
    $selectedService = $_POST['service'];
    $location = $_POST['location'];




    // Now you know which service button was clicked and can handle accordingly
    switch ($selectedService) {
        case 'AC Servicing':
            // Fetch workers who provide AC servicing in the selected location
            $query = "SELECT * FROM worker WHERE service_specialty = 'AC Servicing' AND availability_status = 'available' AND location = ?";

            break;

        case 'TV Servicing':
            // Fetch workers who provide TV servicing in the selected location
            $query = "SELECT * FROM worker WHERE service_specialty = 'TV Servicing' AND availability_status = 'available' AND location = ?";

            break;

        case 'Fridge':
            // Fetch workers who provide Fridge servicing in the selected location
            $query = "SELECT * FROM worker WHERE service_specialty = 'Fridge' AND availability_status = 'available' AND location = ?";
            break;

        case 'Induction':
            // Fetch workers who provide Induction servicing in the selected location
            $query = "SELECT * FROM worker WHERE service_specialty = 'Induction' AND availability_status = 'available' AND location = ?";
            break;

        case 'Plumbing Service':
            // Fetch workers who provide Plumbing services in the selected location
            $query = "SELECT * FROM worker WHERE service_specialty = 'Plumbing Service' AND availability_status = 'available' AND location = ?";
            break;

            // Add more cases as needed for different services
    }
    // Prepare the query and execute it safely using prepared statements
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $location); // Bind the location parameter to the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch all the matching workers into an array
        $workers = $result->fetch_all(MYSQLI_ASSOC);

        // Store the workers array in the session
        $_SESSION['worker'] = $workers;
        $_SESSION['service'] = $selectedService;



        // Redirect to the worker_list.php page to display the workers
        header("Location: worker_list.php");
        exit();
    } else {
        echo "Error in query execution!";
    }

    // Run the query (assuming you have a database connection ready)
    // Example: mysqli_query($connection, $query);

    // Fetch and display the workers (you would need to customize this part)
    // Example:
    // $result = mysqli_query($connection, $query);
    // while($row = mysqli_fetch_assoc($result)) {
    //     echo "Worker Name: " . $row['name'] . "<br>";
    //     echo "Experience: " . $row['experience'] . "<br>";
    // }
}


mysqli_close($conn);
