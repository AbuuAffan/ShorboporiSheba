<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Head content -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="website icon" type="png" href="Images/color monogram-8.png" />
    <title>Shorbopori Sheba</title>
    <style>
        /* CSS styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Ensure full viewport height */
        html,
        body {
            min-height: 100vh;
        }

        /* Add the background image to the body */
        body {
            background-image: url(imageCustomer/homepage.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: top center;
            /* Aligns image to the top */
            background-attachment: fixed;
            /* Fixes image position */
        }

        .header_container {
            position: absolute;
            /* Position header over background */
            top: 0;
            left: 0;
            width: 100%;
            height: 65px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-left: 100px;
            padding-right: 100px;
            background-color: transparent;
            /* Make header background transparent */
        }



        .btn {
            text-decoration: none;

            color: #E15197;
            ;
            padding: 10px 20px;
            margin-left: 10px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            color: #9e1238;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <!-- Use a background image for this page. Image location: Images/homepage.jpg -->
    <div class="header_container">
        <div class="left_part">
            <!-- You can add content here, such as a logo -->

        </div>
        <div class="right_part">
            <form action="profile.php" method="get">
                <a href="post.php" class="btn">Create Post</a>
                <a href="service_request.html" class="btn">Direct Request</a>
                <a href="support.php" class="btn">Support</a>
                <a href="wallet.php" class="btn">Add Wallet</a>
                <button type="submit" class="btn" name="profileButton">Profile</button>
                <button type="submit" class="btn" formaction="logout.php">Log Out</button>
            </form>
        </div>
    </div>
</body>

</html>