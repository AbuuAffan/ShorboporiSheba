<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="../images/color monogram-8.png" />
  <link rel="stylesheet" href="See_work_request.css" />
  <title>Work Requests</title>
</head>

<body>
  <div class="header_container">
    <div class="left_part">
      <div class="image_container">
        <a href="../Wdashboard.html"><img
            src="../Images/lImage.png"
            alt="logo of Shorboporisheba"
            class="logo" /></a>
      </div>
    </div>
    <div class="right_part">
      <a href="See_work_post.php" class="btn">See Work Post</a>
      <a href="See_work_request.php" class="btn">Click To Refresh</a>
      <a href="../support.php" class="btn">Support</a>

      <a href="WorkerProfile.php" class="btn">Profile</a>
    </div>
  </div>

  <?php
  session_start();

  // Include the database connection file
  include '../connect.php'; // Ensure this file contains your database connection

  // Check if the worker is logged in
  if (!isset($_SESSION['worker_id'])) {
    echo "You need to be logged in to view requests.";
    exit();
  }

  // Fetch worker_id from the session
  $worker_id = $_SESSION['worker_id'];

  // Fetch the requests for this worker where status is 'pending'
  $query_requests = "
    SELECT r.*, c.name AS customer_name, s.service_name, w.base_price 
    FROM request r
    JOIN customer c ON r.customer_id = c.customer_id
    JOIN service s ON r.service_id = s.service_id
    JOIN worker w ON r.worker_id = w.worker_id
    WHERE r.worker_id = ? AND r.status = 'pending'
";

  if ($stmt_requests = $conn->prepare($query_requests)) {
    $stmt_requests->bind_param("i", $worker_id);
    $stmt_requests->execute();
    $result_requests = $stmt_requests->get_result();

    if ($result_requests->num_rows > 0) {
  ?>
      <div class="hire-requests">
        <h2>Hire Requests:</h2>
        <?php while ($request = $result_requests->fetch_assoc()) {
          // Calculate the total price based on quantity and worker's base price
          $total_price = $request['Quantity'] * $request['base_price'];
        ?>
          <div class="request-card">
            <p><strong><?php echo htmlspecialchars($request['customer_name']); ?></strong>
              sent you a hire request for
              <strong><?php echo htmlspecialchars($request['service_name']); ?></strong>
              (Quantity: <?php echo htmlspecialchars($request['Quantity']); ?>).
              The total amount is <?php echo number_format($total_price, 2); ?></strong>.
            </p>
            <form method="POST" action="accept_request.php">
              <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
              <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
              <button type="submit" name="accept" class="btn-accept">Accept</button>
            </form>
          </div>
        <?php } ?>
      </div>
  <?php
    } else {
      echo "<p>No hire requests available.</p>";
    }
  } else {
    echo "Error: Could not prepare the requests query.";
  }

  // Close the database connection
  $conn->close();
  ?>




</body>

</html>