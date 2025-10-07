<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="website icon" type="png" href="Images/color monogram-8.png" />
    <title>Create Post</title>
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

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 80px;
            gap: 30px;
        }

        .left img {
            width: 500px;
            height: 500px;
            object-fit: cover;
        }

        .right {
            background-color: #E15197;
            width: 500px;
            height: 500px;
            border-radius: 35px;
            border: 2px solid #041E41;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .right h2 {
            font-size: 18px;
            margin-bottom: 10px;
            color: white;
        }

        .right select,
        .right input[type="text"],
        .right input[type="number"],
        .right input[type="date"],
        .right textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: none;
            outline: none;
            font-size: 14px;
        }

        .right textarea {
            height: 70px;
            resize: none;
        }

        .right button {
            background-color: white;
            color: #E15197;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .right button:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>
    <div class="header_container">
        <div class="left_part">
            <div class="image_container">
                <a href="Cdashboard.php">
                    <img src="Images/lImage.png" alt="logo of Shorboporisheba" class="logo" />
                </a>
            </div>
        </div>
        <div class="right_part">
            <form action="profile.php" method="get">
                <button type="submit" class="btn" name="profileButton">Profile</button>
                <a href="accepted_services.php" class="btn">Accepted Service</a>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="left">
            <img src="Images/post.png" alt="">
        </div>

        <div class="right">
            <form action="Submit_post.php" method="post" id="post-form" class="hidden-form">
                <div class="location">
                    <h2 style="margin-top: 20px;">Select Your Location</h2>
                    <select id="locationSelect" name="location" required>
                        <option value="" disabled selected>Select a location</option>
                        <option value="Mohammodpur">Mohammodpur</option>
                        <option value="Mirpur">Mirpur</option>
                        <option value="Dhanmondi">Dhanmondi</option>
                        <option value="Jatrabari">Jatrabari</option>
                        <option value="Uttara">Uttara</option>
                        <option value="Sadarghat">Sadarghat</option>
                        <option value="Gulshan">Gulshan</option>
                        <option value="Boshundhora">Boshundhora</option>
                        <option value="Banasree">Banasree</option>
                        <option value="Khilgaon">Khilgaon</option>
                        <option value="Banani">Banani</option>
                        <option value="Baridhara">Baridhara</option>
                        <option value="Nikunja">Nikunja</option>
                        <option value="Savar">Savar</option>
                        <option value="Agargaon">Agargaon</option>
                        <option value="Farmgate">Farmgate</option>
                        <option value="Mohakhali">Mohakhali</option>
                        <option value="Niketon">Niketon</option>
                        <option value="Tejgaon">Tejgaon</option>
                    </select>
                </div>
                <div class="location" style="margin-top: 20px;">
                    <h2>Select Your Required Service</h2>
                    <select id="service" name="service" required>
                        <option value="" disabled selected>Select a service specialty</option>
                        <option value="TV Servicing">TV Repair</option>
                        <option value="Fridge">Fridge Repair</option>
                        <option value="Induction">Induction Repair</option>
                        <option value="AC Servicing">AC Repair</option>
                        <option value="Plumbing Service">Plumbing Service</option>
                        <option value="Pest Control">Pest Control</option>
                        <option value="Gas Service">Gas Service</option>
                        <option value="Babysitter">Babysitter</option>
                        <option value="Interior Design">Interior Design</option>
                        <option value="Home Cleaning">Home Cleaning</option>
                        <option value="Laundry">Laundry</option>
                        <option value="Home Shifting">Home Shifting</option>
                    </select>
                </div>

                <div class="creat_post">
                    <input type="text" name="postTitle" placeholder="Enter the post title" required>
                    <input type="number" name="postPrice" placeholder="Enter the price for your requirement" step="100" required>
                    <input type="date" name="serviceDate" required>
                    <textarea name="postContent" placeholder="Write your requirement..." required></textarea>
                </div>

                <button type="submit" class="btn" name="posting" style="margin-left: 190px;margin-top: -10px; width:100px; border-radius: 20px; ">Post</button>
            </form>
        </div>
    </div>
</body>

</html>