<?php
// Start session to use session variables
session_start();

// Include the database connection file
include('connect.php');

// PHP Code to handle money addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addMoney'])) {
  // Get the customer ID from the session
  $customerId = $_SESSION['customer_id']; // Assuming 'customer_id' is the key in the session array

  // Get the amount from the input field
  $addedAmount = floatval($_POST['amount']);

  // Get account and pin from input fields
  $account = $_POST['account'];
  $pin = $_POST['pin'];

  // Sanitize the inputs
  $account = trim($account);
  $pin = trim($pin);

  // Fetch the current amount from the database for the customer
  $stmt = $conn->prepare("SELECT amount FROM customer WHERE customer_id = ?");
  $stmt->bind_param("i", $customerId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentAmount = $row['amount'];

    // If there is no value in the 'amount' column, set it to 0
    if ($currentAmount === null) {
      $currentAmount = 0;
    }

    // Add the new amount to the existing amount
    $newAmount = $currentAmount + $addedAmount;

    // Update the customer's amount, account, and pin in the database
    $updateStmt = $conn->prepare("UPDATE customer SET amount = ?, account = ?, pin = ? WHERE customer_id = ?");
    $updateStmt->bind_param("dssi", $newAmount, $account, $pin, $customerId);

    if ($updateStmt->execute()) {
      // Use JavaScript alert and redirect to Cdashboard.php after clicking OK
      echo "<script>
                    alert('Amount successfully updated to $newAmount');
                    window.location.href = 'Cdashboard.php';
                  </script>";
    } else {
      echo "Error updating record: " . $updateStmt->error;
    }
  } else {
    echo "No customer found with ID $customerId";
  }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="website icon" type="png" href="Images/color monogram-8.png" />
  <link rel="stylesheet" href="dashboard.css" />
  <title>Shorbopori Sheba</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    .header_container {
      width: 100vw;
      height: 65px;
      background-color: #041E41;


      display: flex;
      justify-content: space-between;
      justify-items: center;
      align-items: center;
      padding-left: 100px;
      padding-right: 100px;
    }

    .image_container img {
      width: 150px;
      height: 50px;
      object-fit: cover;
    }

    .btn {
      text-decoration: none;
      /* Removes underline from the link */
      background-color: #E15197;
      color: white;
      padding: 10px 20px;
      margin-left: 10px;
      border-radius: 5px;
      font-size: 16px;
      transition: background-color 0.3s;
    }

    .logo:hover {
      cursor: pointer;
    }

    .btn:hover {
      background-color: #9e1238;
      cursor: pointer;
    }
  </style>
</head>

<!-- Customer homepage frontend -->

<body>
  <div class="header_container">
    <div class="left_part">
      <div class="image_container">
        <a href="Cdashboard.php"><img
            src="Images/lImage.png"
            alt="logo of Shorboporisheba"
            class="logo" /></a>
      </div>
    </div>
    <div class="right_part">
      <form action="profile.php" method="get">

        <a href="support.html" class="btn">Support</a>

        <button type="submit" class="btn" name="profileButton">
          Profile
        </button>
      </form>
    </div>
  </div>

  <div class="text_overlay">
    <form method="post" class="title_" style="text-align: center; margin-top: 30px">
      <label for="amount" style="font-size: 22px; color: #041e41">Add Money</label><br />
      <input
        type="number"
        name="amount"
        id="amount"
        placeholder="Enter amount"
        step="50"
        style="
            background-color: #f0f8ff;
            width: 250px;
            padding: 10px;
            font-size: 18px;
            margin-top: 10px;
        "
        required /><br /><br />
      <input
        type="text"
        name="account"
        id="account"
        placeholder="Enter account number"
        style="
            background-color: #f0f8ff;
            width: 250px;
            padding: 10px;
            font-size: 18px;
            margin-top: 10px;
        "
        required /><br /><br />
      <input
        type="password"
        name="pin"
        id="pin"
        placeholder="Enter PIN"
        style="
            background-color: #f0f8ff;
            width: 250px;
            padding: 10px;
            font-size: 18px;
            margin-top: 10px;
        "
        required /><br /><br />
      <input type="submit" name="addMoney" value="Add Money" class="btn" />
    </form>

  </div>
</body>

</html>