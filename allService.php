<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="website icon" type="png" href="Images/color monogram-8.png" />
  <link rel="stylesheet" href="service.css">
  <title>All service</title>
</head>

<body>
  <div class="head_container">
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
        <a href="allService.php" class="btn">All Service</a>
        <button type="submit" class="btn" name="profileButton">Profile</button>
      </form>
    </div>
  </div>

  <div class="main_container">
    <div class="option_picker">


      <!-- Options to select Customer or Worker -->
      <div class="options" id="options">
        <button class="btn" id="post-btn" style="width: 200px">
          Post
        </button>
        <a class="btn" href="">Direct Request</a>
      </div>

      <!-- post -->

    </div>


  </div>


  <script>
    document.getElementById("post-btn").addEventListener("click", function() {
      // Show the post form
      document.getElementById("post-form").style.display = "block";

      // Hide the options (both the Post and Direct Request buttons)
      document.getElementById("options").style.display = "none";
    });
  </script>

</body>

</html>