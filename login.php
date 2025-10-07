<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="website icon" type="png" href="Images/color monogram-8.png" />
  <link rel="stylesheet" href="login.css" />
  <title>Log In</title>
</head>

<!-- login frontend -->

<body class="body">
  <div class="container">
    <h2>Welcome to Shorbopori Sheba</h2>
    <h4 style="padding-bottom: 15px">
      Customer use email and Worker use phone number
    </h4>
    <form action="signin.php" class="login-form" method="post">
      <!-- email_phone input -->
      <input
        type="text"
        class="ifield"
        id="email_phone"
        name="email_phone"
        placeholder="Enter your email/phone"
        style="width: 335px"
        required />

      <!-- Password input and Show Password option -->
      <div class="password-container">
        <input
          type="password"
          class="ifield"
          id="password"
          name="password"
          placeholder="Enter your password"
          style="width: 335px"
          required />
        <div class="show-password">
          <input type="checkbox" id="show-password" />
          <label for="show-password">Show Password</label>
        </div>
      </div>

      <!-- Remember me and Forgot Password -->
      <div class="options">
        <div class="remember-me">
          <input type="checkbox" id="remember-me" />
          <label for="remember-me">Remember me</label>
        </div>
        <a href="#" class="forgot-password">Forgot Password?</a>
      </div>

      <!-- Login Button -->
      <button type="submit" class="login-btn" name="login">Log In</button>

      <!-- Sign Up Link -->
      <p class="signup-link">
        Don't have an account yet? <a href="signup.php">Sign Up</a>
      </p>

      <!-- Terms and Conditions -->
      <p class="terms">
        By signing up, you agree to Shorbopori Sheba's
        <a href="#">Terms and Conditions</a> & <a href="#">Privacy Policy</a>.
      </p>
    </form>
  </div>

  <script>
    // Toggle show/hide password functionality
    document
      .getElementById("show-password")
      .addEventListener("change", function() {
        var passwordField = document.getElementById("password");
        if (this.checked) {
          passwordField.type = "text";
        } else {
          passwordField.type = "password";
        }
      });
  </script>
</body>

</html>