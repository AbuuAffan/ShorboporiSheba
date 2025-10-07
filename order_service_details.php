<?php
session_start();

// Check if the customer is logged in
if (!isset($_SESSION['customer_id'])) {
    // Redirect to login if the customer is not logged in
    header("Location: signin.php");
    exit();
}

// Include your database connection
include 'connect.php';

// Check if the order_id is set in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Query to fetch details for this order, including the worker's profile picture and name using a subquery
    $query = "
   SELECT so.order_id, s.service_name, so.order_date, so.payable_amount,so.worker_id, 
   (SELECT w.profile_pic FROM worker w WHERE w.worker_id = so.worker_id) AS profile_pic,
   (SELECT w.name FROM worker w WHERE w.worker_id = so.worker_id) AS worker_name
   FROM service_order so
   JOIN service s ON so.service_id = s.service_id
   WHERE so.order_id = ? AND so.customer_id = ?
";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $order_id, $_SESSION['customer_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $order_details = $result->fetch_assoc();
    } else {
        echo "Error: Could not prepare the query.";
    }
} else {
    echo "No order ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="website icon" type="png" href="Images/color monogram-8.png" />
    <link rel="stylesheet" href="service_details.css">
    <title>Service Details</title>

    <style>
        .hidden {
            display: none;
        }

        .worker_profile {
            width: 300px;
            height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            /* Align text to the bottom */
            align-items: center;
            background-image: url('Images/order.png');
            /* Use the background image */
            background-size: cover;
            background-position: center;
            border-radius: 15px;
            /* For rounded corners */
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Add shadow for depth */
            position: relative;
            color: white;
            /* Text overlay color */
            text-align: center;
            overflow: hidden;
        }

        .worker_profile .overlay-text {
            background-color: rgba(0, 0, 0, 0.5);
            /* Add a dark overlay behind the text */
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            text-align: left;
            color: white;
        }

        .worker_profile .overlay-text p {
            margin: 5px 0;
        }

        .worker_profile .amount {
            font-size: 28px;
            font-weight: bold;

        }

        .worker_profile .worker-info {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }

        .worker_profile .service-details p {
            font-size: 14px;
        }

        .worker_profile .payment-button-container {
            margin-top: 15px;
        }

        .worker_profile .btn {
            background-color: #E15197;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .worker_profile .btn:hover {
            background-color: #9e1238;
        }

        .wp {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>


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

                <button type="submit" class="btn" name="profileButton">
                    Profile
                </button>
                <a href="accepted_services.php" class="btn">Accepted Service</a>
            </form>
        </div>
    </div>




    <?php if ($order_details) { ?>

        <div class="wp">
            <div class="worker_profile">
                <div class="overlay-text">
                    <p class="amount"><?php echo htmlspecialchars($order_details['payable_amount']); ?>tk</p>
                    <div class="worker-info">
                        <p><?php echo htmlspecialchars($order_details['worker_name']); ?></p>
                    </div>
                    <p>Service: <?php echo htmlspecialchars($order_details['service_name']); ?></p>
                    <p>Order Date: <?php echo htmlspecialchars($order_details['order_date']); ?></p>
                </div>


            </div>
        </div>


        <!-- here add three button payment rating report  -->
        <!-- Payment button -->

        <div class="main_container" style="display: flex;justify-content: center;align-items: center; margin-top:50px">
            <div id="buttons" class="center">
                <button id="payment_button" class="btn" onclick="showPaymentForm()">
                    Payment
                </button>
            </div>

            <!-- Payment form -->
            <div id="payment_form" class="hidden center">
                <form id="paymentForm" method="post" action="process_payment.php">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
                    <input type="hidden" name="worker_id" value="<?php echo $order_details['worker_id']; ?>" />

                    <label for="pay_amount">Enter Payment Amount:</label>
                    <input type="number" name="pay_amount" style="display: block; margin-bottom:20px;" required />

                    <!-- Input field for Account Number -->
                    <label for="account">Enter Account Number:</label>
                    <input type="text" name="account" id="account" style="display: block;margin-bottom:20px;" required />

                    <!-- Input field for PIN -->
                    <label for="pin">Enter PIN:</label>
                    <input type="password" name="pin" id="pin" style="display: block;margin-bottom:20px;" required />

                    <button type="submit" class="btn">Submit Payment</button>
                </form>
            </div>


            <!-- Rating form -->
            <div id="rating_form" class="hidden center">
                <form id="ratingForm" action="rating.php" method="post">
                    <!-- Hidden inputs -->
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
                    <input type="hidden" name="customer_id" value="<?php echo $_SESSION['customer_id']; ?>" />
                    <input type="hidden" name="worker_id" value="<?php echo $order_details['worker_id']; ?>" />

                    <label for="rating_score">Enter Rating (0 to 5):</label>
                    <input type="number" name="rating_score" min="0" max="5" step="0.1" required />
                    <label for="review">Enter Review:</label>
                    <input type="text" name="review" required />
                    <button type="submit" class="btn">Submit Rating</button>
                </form>
            </div>

            <!-- Report option -->
            <div id="report_option" class="hidden center">
                <p>Do you want to report about this worker?</p>
                <button class="btn" onclick="showReportForm()">Yes</button>
                <button class="btn" onclick="noReport()">No</button>
            </div>

            <!-- Report form -->
            <div id="report_form" class="hidden center">
                <form id="reportForm" action="report.php" method="post">
                    <!-- Hidden inputs -->
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
                    <input type="hidden" name="reported_customer_id" value="<?php echo $_SESSION['customer_id']; ?>" />
                    <input type="hidden" name="reported_worker_id" value="<?php echo $order_details['worker_id']; ?>" />

                    <label for="report_description">Enter Report Details:</label>
                    <input type="text" name="report_description" required />
                    <button type="submit" class="btn">Submit Report</button>
                </form>
            </div>

            <!-- Message after choosing not to report -->
            <div id="no_report_message" class="hidden center">
                <h1>No</h1>
                <a href="Cdashboard.php">click to go dashboard</a>
            </div>



        </div>


    <?php } else { ?>
        <p>No details found for this order.</p>
    <?php } ?>

    <script>
        // Your JavaScript code here
        // Show the payment form and hide the payment button
        function showPaymentForm() {
            document.getElementById("payment_button").classList.add("hidden");
            document.getElementById("payment_form").classList.remove("hidden");
        }

        // Handle payment form submission
        document
            .getElementById("paymentForm")
            .addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Collect form data
                var formData = new FormData(this);

                // Send form data via AJAX
                fetch("process_payment.php", {
                        method: "POST",
                        body: formData,
                    })
                    .then(function(response) {
                        return response.text(); // Assuming the server returns a text response
                    })
                    .then(function(data) {
                        alert(data); // Alert the response for feedback

                        // Handle showing the rating form on successful payment
                        if (data.includes('Payment successful')) {
                            document.getElementById("payment_form").classList.add("hidden");
                            document.getElementById("rating_form").classList.remove("hidden");
                        } else {
                            // Reload the page if payment fails
                            window.location.reload();
                        }
                    })
                    .catch(function(error) {
                        console.error("Error:", error);
                    });
            });

        // Handle rating form submission
        document
            .getElementById("ratingForm")
            .addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Collect form data
                var formData = new FormData(this);

                // Send form data via AJAX
                fetch("rating.php", {
                        method: "POST",
                        body: formData,
                    })
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(data) {
                        alert(data);

                        // Hide rating form and show report option
                        document.getElementById("rating_form").classList.add("hidden");
                        document.getElementById("report_option").classList.remove("hidden");
                    })
                    .catch(function(error) {
                        console.error("Error:", error);
                    });
            });

        // Show the report form if the user wants to report
        function showReportForm() {
            document.getElementById("report_option").classList.add("hidden");
            document.getElementById("report_form").classList.remove("hidden");
        }

        // Display a message if the user does not want to report
        function noReport() {
            document.getElementById("report_option").classList.add("hidden");
            document.getElementById("no_report_message").classList.remove("hidden");
        }

        // Handle report form submission
        document.getElementById("reportForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Collect form data
            var formData = new FormData(this);

            // Send form data via AJAX
            fetch("report.php", {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(data) {
                    alert(data);

                    // Hide report form and thank the user
                    document.getElementById("report_form").classList.add("hidden");
                    alert("Report submitted. Thank you!");
                })
                .catch(function(error) {
                    console.error("Error:", error);
                });
        });
    </script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>