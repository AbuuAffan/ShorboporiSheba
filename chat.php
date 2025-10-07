<?php
// Assuming you already have a database connection in place
include 'connect.php'; // Make sure this file contains your DB connection

// Fetch the order_id from the URL
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

if ($order_id) {
    // Fetch worker_id and customer_id from the service_order table
    $query = "SELECT worker_id, customer_id FROM service_order WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order_details = $result->fetch_assoc();

    $worker_id = $order_details['worker_id'];
    $customer_id = $order_details['customer_id'];

    // Handle form submission (message sending)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $message_content = $_POST['message_content'];
        $sender_id = $customer_id; // Customer is sending the message
        $receiver_id = $worker_id; // Worker is the receiver
        $status = 'sent';
        $sender_type = 'customer'; // Sender type is customer
        $message_time = date('Y-m-d H:i:s'); // Current timestamp

        // Insert the message into the chat table
        $insert_query = "INSERT INTO chat (order_id, sender_id, status, message_content, message_time, receiver_id, sender_type)
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iisssis", $order_id, $sender_id, $status, $message_content, $message_time, $receiver_id, $sender_type);
        $stmt->execute();
    }

    // Fetch chat messages for this order, sorted by message_time in ascending order
    $fetch_query = "SELECT * FROM chat WHERE order_id = ? ORDER BY message_time ASC";
    $stmt = $conn->prepare($fetch_query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $chat_result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/color monogram-8.png" />
    <title>Chat</title>

    <!-- External CSS and Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Inline CSS -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .header_container {
            width: 100vw;
            height: 65px;
            background-color: #041e41;
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
            background-color: #041e41;
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
        }


        .message-area {
            height: 100vh;
            padding: 30px 0;
            background: #f5f5f5;
        }

        .chat-area {
            width: 100%;
            background-color: #fff;
            border-radius: 0.3rem;
            height: 90vh;
            overflow: hidden;
        }

        .chatbox {
            width: 100%;
            height: calc(100% - 80px);
            overflow-y: auto;
            padding: 15px;
        }

        .msg-head {
            padding: 15px;
            border-bottom: 1px solid #ccc;
        }

        .msg-body ul {
            padding: 15px;
            list-style: none;
        }

        .msg-body ul li {
            margin: 15px 0;
        }

        .msg-body ul li.sender p {
            padding: 15px;
            background: #f5f5f5;
            border-radius: 10px;
            max-width: 60%;
            float: left;
            clear: both;
        }

        .msg-body ul li.repaly p {
            padding: 15px;
            background: #4b7bec;
            color: black;
            border-radius: 10px;
            max-width: 60%;
            float: right;
            clear: both;
        }

        .time {
            font-size: 12px;
            color: #000;
            display: block;
            margin-top: 5px;
        }

        .send-box {
            padding: 15px;
            border-top: 1px solid #ccc;
        }

        .send-box form {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .send-box .form-control {
            width: 85%;
        }

        .send-box button {
            width: 14%;
            border: none;
            background: #3867d6;
            color: #fff;
            border-radius: 0.25rem;
        }
    </style>
</head>

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

                <a href="support.php" class="btn" style="color: #f5f5f5;">Support</a>
                <button type="submit" class="btn" name="profileButton" style="color: #f5f5f5;">Profile</button>

            </form>

        </div>
    </div>

    <!-- Chat System -->
    <section class="message-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="chat-area"><?php
                                            // Assuming you already have a database connection in place
                                            include 'connect.php'; // Make sure this file contains your DB connection

                                            // Fetch the order_id from the URL
                                            $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

                                            if ($order_id) {
                                                // Fetch worker_id and customer_id from the service_order table
                                                $query = "SELECT worker_id, customer_id FROM service_order WHERE order_id = ?";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $order_id);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $order_details = $result->fetch_assoc();

                                                $worker_id = $order_details['worker_id'];
                                                $customer_id = $order_details['customer_id'];

                                                // Handle form submission (message sending)
                                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                    $message_content = $_POST['message_content'];
                                                    $sender_id = $customer_id; // Customer is sending the message
                                                    $receiver_id = $worker_id; // Worker is the receiver
                                                    $status = 'sent';
                                                    $sender_type = 'customer'; // Sender type is customer
                                                    $message_time = date('Y-m-d H:i:s'); // Current timestamp

                                                    // Insert the message into the chat table
                                                    $insert_query = "INSERT INTO chat (order_id, sender_id, status, message_content, message_time, receiver_id, sender_type)
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                    $stmt = $conn->prepare($insert_query);
                                                    $stmt->bind_param("iisssis", $order_id, $sender_id, $status, $message_content, $message_time, $receiver_id, $sender_type);
                                                    $stmt->execute();
                                                }

                                                // Fetch chat messages for this order, sorted by message_time in ascending order
                                                $fetch_query = "SELECT * FROM chat WHERE order_id = ? ORDER BY message_time ASC";
                                                $stmt = $conn->prepare($fetch_query);
                                                $stmt->bind_param("i", $order_id);
                                                $stmt->execute();
                                                $chat_result = $stmt->get_result();
                                            }
                                            ?>

                        <!DOCTYPE html>
                        <html lang="en">

                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <link rel="icon" type="image/png" href="images/color monogram-8.png" />
                            <title>Chat</title>

                            <!-- External CSS and Fonts -->
                            <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

                            <!-- Inline CSS -->
                            <style>
                                * {
                                    margin: 0;
                                    padding: 0;
                                    box-sizing: border-box;
                                    font-family: Arial, sans-serif;
                                }

                                .message-area {
                                    height: 100vh;
                                    padding: 30px 0;
                                    background: #f5f5f5;
                                }

                                .chat-area {
                                    width: 100%;
                                    background-color: #fff;
                                    border-radius: 0.3rem;
                                    height: 90vh;
                                    overflow: hidden;
                                }

                                .chatbox {
                                    width: 100%;
                                    height: calc(100% - 80px);
                                    overflow-y: auto;
                                    padding: 15px;
                                }

                                .msg-head {
                                    padding: 15px;
                                    border-bottom: 1px solid #ccc;
                                }

                                .msg-body ul {
                                    padding: 15px;
                                    list-style: none;
                                }

                                .msg-body ul li {
                                    margin: 15px 0;
                                }

                                .msg-body ul li.repaly p {
                                    padding: 15px;
                                    background: #f5f5f5;
                                    border-radius: 10px;
                                    max-width: 60%;
                                    float: left;
                                    clear: both;
                                }

                                .msg-body ul li.sender p {
                                    padding: 15px;
                                    background: #4b7bec;
                                    color: #fff;
                                    border-radius: 10px;
                                    max-width: 60%;
                                    float: right;
                                    clear: both;
                                }

                                .time {
                                    font-size: 12px;
                                    color: #000;
                                    display: block;
                                    margin-top: 5px;
                                }

                                .send-box {
                                    padding: 15px;
                                    border-top: 1px solid #ccc;
                                }

                                .send-box form {
                                    display: flex;
                                    align-items: center;
                                    justify-content: space-between;
                                }

                                .send-box .form-control {
                                    width: 85%;
                                }

                                .send-box button {
                                    width: 14%;
                                    border: none;
                                    background: #3867d6;
                                    color: #fff;
                                    border-radius: 0.25rem;
                                }
                            </style>
                        </head>

                        <body>

                            <!-- Chat System -->
                            <section class="message-area">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="chat-area">
                                                <!-- Chatbox -->
                                                <div class="chatbox">
                                                    <div class="modal-dialog-scrollable">
                                                        <div class="modal-content">
                                                            <div class="msg-head">
                                                                <div class="row">
                                                                    <div class="col-8">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="flex-grow-1 ms-3">
                                                                                <h3>Chat with Worker</h3>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="msg-body">
                                                                    <ul>
                                                                        <?php while ($chat_row = $chat_result->fetch_assoc()): ?>
                                                                            <?php if ($chat_row['sender_type'] == 'customer'): ?>
                                                                                <!-- Customer is the sender, show on right -->
                                                                                <li class="sender">
                                                                                    <p><?php echo htmlspecialchars($chat_row['message_content']); ?></p>
                                                                                </li>
                                                                            <?php elseif ($chat_row['sender_type'] == 'worker'): ?>
                                                                                <!-- Worker is the sender, show on left -->
                                                                                <li class="repaly">
                                                                                    <p><?php echo htmlspecialchars($chat_row['message_content']); ?></p>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php endwhile; ?>
                                                                    </ul>
                                                                </div>
                                                            </div>

                                                            <div class="send-box">
                                                                <form action="" method="POST">
                                                                    <input type="text" class="form-control" name="message_content" aria-label="message…" placeholder="Write message…" required>
                                                                    <button type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Chatbox -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- External JS -->
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
                        </body>

                        </html>
                        <!-- Chatbox -->
                        <div class="chatbox">
                            <div class="modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="msg-head">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1 ms-3">
                                                        <h3>Chat with Worker</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-body">
                                        <div class="msg-body">
                                            <ul>
                                                <?php while ($chat_row = $chat_result->fetch_assoc()): ?>
                                                    <?php if ($chat_row['sender_type'] == 'customer'): ?>
                                                        <!-- Customer is the sender, show on right -->
                                                        <li class="sender">
                                                            <p><?php echo htmlspecialchars($chat_row['message_content']); ?></p>
                                                        </li>
                                                    <?php elseif ($chat_row['sender_type'] == 'worker'): ?>
                                                        <!-- Worker is the sender, show on left -->
                                                        <li class="repaly">
                                                            <p><?php echo htmlspecialchars($chat_row['message_content']); ?></p>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="send-box">
                                        <form action="" method="POST">
                                            <input type="text" class="form-control" name="message_content" aria-label="message…" placeholder="Write message…" required>
                                            <button type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Chatbox -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- External JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>