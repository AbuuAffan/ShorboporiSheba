<?php
session_start(); // Start session

// Check if workers data is available in the session
if (!isset($_SESSION['service'])) {
    echo "No workers available.";
    exit();
}

// Get the selected service from session
$selectedService = $_SESSION['service'];

// Connect to the database
include 'connect.php';

// Initialize default values for filtering
$rating_filter = isset($_POST['rating_filter']) ? $_POST['rating_filter'] : null;
$price_filter = isset($_POST['price_filter']) ? $_POST['price_filter'] : null;
$experience_filter = isset($_POST['experience_filter']) ? $_POST['experience_filter'] : null;

// Construct the base query to fetch workers for the selected service with rating calculated from the rating table
$query = "
    SELECT w.worker_id, w.name, w.experience, w.bio, w.base_price, 
           IFNULL(AVG(r.rating_score), 0) AS rating
    FROM worker w
    JOIN service s ON w.service_specialty = s.service_name
    LEFT JOIN rating r ON w.worker_id = r.worker_id
    WHERE s.service_name = ? AND w.availability_status = 'available'
";

// Add filtering conditions based on user selection
$filter_conditions = [];
if ($price_filter || $experience_filter) {
    if ($price_filter) {
        if ($price_filter == 'low_high') {
            $order_by_clause = " ORDER BY w.base_price ASC";
        } elseif ($price_filter == 'high_low') {
            $order_by_clause = " ORDER BY w.base_price DESC";
        }
    }

    if ($experience_filter) {
        if ($experience_filter == 'low_high') {
            $order_by_clause = " ORDER BY w.experience ASC";
        } elseif ($experience_filter == 'high_low') {
            $order_by_clause = " ORDER BY w.experience DESC";
        }
    }
} else {
    $order_by_clause = " ORDER BY AVG(r.rating_score) DESC"; // Default sorting by rating
}

$query .= " GROUP BY w.worker_id"; // Grouping by worker_id for correct aggregation

// Use HAVING for filtering on the aggregate function
$having_clause = '';
if ($rating_filter) {
    $having_clause = " HAVING rating >= " . intval($rating_filter);
}

$query .= $having_clause;
$query .= $order_by_clause;

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $selectedService);
$stmt->execute();
$result = $stmt->get_result();
$workers = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="website icon" type="png" href="Images/color monogram-8.png" />
    <link rel="stylesheet" href="worker_list.css">
    <title>List Of Workers</title>
    <style>
        /* CSS for filter form */
        .filter {
            margin-left: 1200px;
            margin-top: 20px;
            margin-bottom: -20px;
            display: flex;
            justify-content: flex-end;
        }

        .filter form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .filter label {
            margin-right: 10px;
            font-size: 16px;
        }

        .filter select {
            padding: 5px;
            font-size: 14px;
        }

        .filter button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .filter button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="header_container">
        <div class="left_part">
            <div class="image_container">
                <a href="Cdashboard.php"><img src="Images/lImage.png" alt="logo of Shorboporisheba" class="logo" /></a>
            </div>
        </div>
        <div class="right_part">
            <form action="profile.php" method="get">

                <button type="submit" class="btn" name="profileButton">Profile</button>
                <button type="submit" class="btn" formaction="logout.php">Log Out</button>
            </form>
        </div>
    </div>

    <!-- Filtering system -->
    <div class="filter" style="padding-right: 165px;">
        <form method="POST" action="worker_list.php">
            <label for="rating_filter">Rating:</label>
            <select name="rating_filter" id="rating_filter">
                <option value="">Select Rating</option>
                <option value="1">Above 1</option>
                <option value="2">Above 2</option>
                <option value="3">Above 3</option>
                <option value="4">Above 4</option>
            </select>

            <label for="price_filter">Base Price:</label>
            <select name="price_filter" id="price_filter">
                <option value="">Select Price Order</option>
                <option value="low_high">Low to High</option>
                <option value="high_low">High to Low</option>
            </select>

            <label for="experience_filter">Experience:</label>
            <select name="experience_filter" id="experience_filter">
                <option value="">Select Experience</option>
                <option value="low_high">Low to High</option>
                <option value="high_low">High to Low</option>
            </select>

            <button type="submit">Apply Filter</button>
        </form>
    </div>

    <div class="main_container" style="display: flex; justify-content: center; align-items: center;">
        <div class="forcenter">
            <h1 style="margin-top: 30px;margin-bottom: 20px;margin-left: 50px;">Available Workers for <?php echo htmlspecialchars($selectedService); ?></h1>
            <div id="workerList" style="max-width: 600px;">
                <?php if (count($workers) > 0): ?>
                    <?php foreach ($workers as $worker): ?>
                        <div class="worker" style="background-color: #f9f9f9; border: 1px solid #ddd;border-radius: 8px;padding: 20px;box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">
                            <h2 style="font-size: 30px;color: #333;"><?php echo htmlspecialchars($worker['name']); ?></h2>
                            <p style="font-size: 16px; color: #555;"><strong>Rating:</strong> <?php echo number_format((float)$worker['rating'], 1, '.', ''); ?></p>
                            <p style="font-size: 16px; color: #555;"><strong>Base Price:</strong> <?php echo htmlspecialchars($worker['base_price']); ?> tk</p>
                            <p style="font-size: 16px; color: #555;"><strong>Experience:</strong> <?php echo htmlspecialchars($worker['experience']); ?> years</p>
                            <p style="font-size: 16px; color: #555;"><strong>Bio:</strong> <?php echo htmlspecialchars($worker['bio']); ?></p>
                            <form action="worker_profile.php" method="get">
                                <input type="hidden" name="worker_id" value="<?php echo htmlspecialchars($worker['worker_id']); ?>">
                                <button type="submit" class="btn" style="margin-top: 10px;">View Profile</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No workers available for the selected service and location.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>

</html>