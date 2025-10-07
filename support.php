<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="website icon" type="png" href="Images/color monogram-8.png" />
    <title>Support</title>
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
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-left: 100px;
            padding-right: 100px;
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

                <a href="support.php" class="btn">Support</a>
                <a href="customer_support.php" class="btn">Admin Response</a>
                <button type="submit" class="btn" name="profileButton">Profile</button>
                <a href="Wdashboard.html">Click For worker dashboard</a>
                <a href="logout.php" class="btn">Log Out</a>
            </form>

        </div>
    </div>


    <div class="main">

        <div>
            <form action="submit_ticket.php" method="POST" style="margin-top: 50px;">
                <label for="subject"> <strong>Subject:</strong></label>
                <input type="text" id="subject" name="subject" required><br><br>

                <label for="message"> <strong>Describe your issue:</strong></label><br>
                <textarea id="message" name="message" rows="4" cols="50" required></textarea><br><br>

                <input type="submit" class="btn" value="Submit Support Request">
            </form>
        </div>

    </div>





</body>

</html>