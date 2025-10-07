<?php
// Start session
session_start();

// Include the database connection file
include 'connect.php'; // Ensure you have a database connection script

// Ensure the customer is logged in (assuming customer_id is stored in the session)
if (!isset($_SESSION['customer_id'])) {
    echo "You need to be logged in to hire a worker.";
    exit();
}

// Get customer ID from the session
$customer_id = $_SESSION['customer_id'];

// Check if worker_id is provided in the URL
if (isset($_GET['worker_id'])) {
    $worker_id = $_GET['worker_id'];

    // Query the database to fetch worker details based on worker_id
    $query = "SELECT * FROM worker WHERE worker_id = ?";

    if ($stmt = $conn->prepare($query)) {
        // Bind the worker_id to the prepared statement
        $stmt->bind_param("i", $worker_id);
        $stmt->execute();

        // Get the result of the query
        $result = $stmt->get_result();
        $selectedWorker = $result->fetch_assoc(); // Fetch worker data as an associative array

        // Check if a worker was found
        if ($selectedWorker) {
            // Calculate the average rating for the worker
            $rating_query = "SELECT AVG(rating_score) AS average_rating FROM rating WHERE worker_id = ?";
            if ($rating_stmt = $conn->prepare($rating_query)) {
                $rating_stmt->bind_param("i", $worker_id);
                $rating_stmt->execute();
                $rating_result = $rating_stmt->get_result();
                $rating_data = $rating_result->fetch_assoc();
                $average_rating = $rating_data['average_rating'];

                // Check if there are ratings; if not, set a default message
                if ($average_rating !== null) {
                    // Format the average rating to one decimal place
                    $average_rating = number_format($average_rating, 1);
                } else {
                    $average_rating = 'No ratings yet';
                }
            } else {
                echo "Failed to prepare rating query: " . $conn->error;
            }

            // If the Hire Request button is clicked
            if (isset($_POST['hire_request'])) {
                // Fetch service_id based on the service_specialty from the service table
                $service_name = $selectedWorker['service_specialty'];
                $service_query = "SELECT service_id FROM service WHERE service_name = ?";
                if ($service_stmt = $conn->prepare($service_query)) {
                    $service_stmt->bind_param("s", $service_name);
                    $service_stmt->execute();
                    $service_result = $service_stmt->get_result();
                    $service_data = $service_result->fetch_assoc();
                    $service_id = $service_data['service_id'];

                    // If service ID is found, proceed with storing the hire request
                    if ($service_id) {
                        // Get current date (without time)
                        $request_date = date('Y-m-d');

                        // Get the quantity from the form input
                        $quantity = intval($_POST['quantity']); // Get the quantity entered by the user

                        // Calculate the total price (base_price * quantity)
                        $base_price = $selectedWorker['base_price'];
                        $total_price = $base_price * $quantity;

                        // Set status to 'pending' since the user clicked the hire button
                        $status = 'pending';

                        // Use the worker's location for the request
                        $location = $selectedWorker['location'];

                        // Insert the request into the request table
                        $insert_query = "INSERT INTO request (customer_id, service_id, request_date, location, price, Quantity, status, worker_id) 
                                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                        if ($insert_stmt = $conn->prepare($insert_query)) {
                            $insert_stmt->bind_param("iissdisi", $customer_id, $service_id, $request_date, $location, $total_price, $quantity, $status, $worker_id);
                            if ($insert_stmt->execute()) {
                                // Show a popup notification for success and redirect to the dashboard
                                echo "<script>
                                        alert('Your hire request was successful!');
                                        window.location.href = 'Cdashboard.php'; // Redirect to the dashboard page
                                      </script>";
                                exit();
                            } else {
                                echo "Failed to send hire request: " . $conn->error;
                            }
                        } else {
                            echo "Failed to prepare request insertion: " . $conn->error;
                        }
                    } else {
                        echo "Service not found for the worker.";
                    }
                } else {
                    echo "Failed to prepare service query: " . $conn->error;
                }
            }
?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="website icon" type="png" href="Images/color monogram-8.png" />
                <link rel="stylesheet" href="wp.css">
                <title><?php echo htmlspecialchars($selectedWorker['name']); ?>'s Profile</title>
            </head>

            <body>
                <div class="header_container">
                    <div class="left_part">
                        <div class="image_container">
                            <a href="Cdashboard.php"><img src="Images/lImage.png" alt="logo of Shorboporisheba" class="logo" /></a>
                        </div>
                    </div>
                    <form action="profile.php" method="get">
                        <button type="submit" class="btn" name="profileButton">Profile</button>
                        <button type="submit" class="btn" formaction="logout.php">Log Out</button>
                    </form>
                </div>

                <div class="main_container" style="max-width: 900px; margin: 50px auto; padding: 40px; background-color: #041E41; border-radius: 10px;
                                                   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); text-align: center;">
                    <h1 style="font-size: 36px; margin-bottom: 20px; color: white;">
                        Profile of <?php echo htmlspecialchars($selectedWorker['name']); ?>
                    </h1>
                    <div>
                        <img src="<?php echo htmlspecialchars($selectedWorker['profile_pic']); ?>" alt="Profile Picture" style="width:150px;height:150px;border-radius:50%; object-fit:cover">
                    </div>
                    <p style="font-size: 22px; margin-bottom: 15px; margin-top: 15px; color: white;"><strong>Name:</strong> <?php echo htmlspecialchars($selectedWorker['name']); ?></p>
                    <p style="font-size: 22px; margin-bottom: 15px; margin-top: 15px; color: white;"><strong>Service:</strong> <?php echo htmlspecialchars($selectedWorker['service_specialty']); ?></p>
                    <p style="font-size: 22px; margin-bottom: 15px; margin-top: 15px; color: white;"><strong>Base Price:</strong> <?php echo htmlspecialchars($selectedWorker['base_price']); ?> tk</p>
                    <p style="font-size: 22px; margin-bottom: 15px; margin-top: 15px; color: white;"><strong>Experience:</strong> <?php echo htmlspecialchars($selectedWorker['experience']); ?> years</p>
                    <p style="font-size: 22px; margin-bottom: 15px; margin-top: 15px; color: white;"><strong>Rating:</strong> <?php echo htmlspecialchars($average_rating); ?></p>
                    <p style="font-size: 22px; margin-bottom: 15px; margin-top: 15px; color: white;"><strong>Bio:</strong> <?php echo htmlspecialchars($selectedWorker['bio']); ?></p>

                    <!-- Form to input quantity and submit the hire request -->
                    <form method="POST" action="">
                        <label for="quantity" style="font-size: 22px; color: white; margin-left: 300px;">Quantity of Service:</label>
                        <input type="number" name="quantity" id="quantity" min="1" required style="padding: 8px; margin-top: 10px; margin-bottom: 20px; font-size: 18px;">
                        <button type="submit" name="hire_request" class="btn" style="margin-left: 600px;">Hire Request</button>
                    </form>
                </div>

                <?php
                // Fetch all reviews with customer names for the worker
                $reviews_query = "SELECT r.rating_score, r.review, c.name AS customer_name FROM rating r
                                  JOIN customer c ON r.customer_id = c.customer_id
                                  WHERE r.worker_id = ?";
                if ($reviews_stmt = $conn->prepare($reviews_query)) {
                    $reviews_stmt->bind_param("i", $worker_id);
                    $reviews_stmt->execute();
                    $reviews_result = $reviews_stmt->get_result();
                    $reviews = $reviews_result->fetch_all(MYSQLI_ASSOC);
                } else {
                    echo "Failed to prepare reviews query: " . $conn->error;
                }
                ?>

                <div class="reviews_container" style="max-width: 900px; margin: 50px auto; padding: 40px; background-color: #f9f9f9; border-radius: 10px;
                                                     box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); text-align: left;">
                    <h2 style="font-size: 30px; margin-bottom: 20px; color: #333;">Customer Reviews</h2>
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="review" style="margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                                <p style="font-size: 18px; color: #555;"><strong>Customer:</strong> <?php echo htmlspecialchars($review['customer_name']); ?></p>
                                <p style="font-size: 18px; color: #555;"><strong>Rating:</strong> <?php echo htmlspecialchars($review['rating_score']); ?>/5</p>
                                <p style="font-size: 18px; color: #555;"><strong>Review:</strong> <?php echo htmlspecialchars($review['review']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="font-size: 18px; color: #555;">No reviews yet.</p>
                    <?php endif; ?>
                </div>

            </body>

            </html>
<?php
        } else {
            // Worker not found in the database
            echo "Worker not found.";
        }
    } else {
        // Error preparing the SQL statement
        echo "Database query failed.";
    }
} else {
    echo "No worker ID provided.";
}
?>