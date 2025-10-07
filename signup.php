<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="website icon" type="png" href="Images/color monogram-8.png" />
  <link rel="stylesheet" href="signup.css" />
  <title>Sign Up</title>
</head>

<!-- Registration frontend -->

<body class="body">
  <div class="container">
    <h2>Register as</h2>

    <!-- Options to select Customer or Worker -->
    <div class="options" id="options">
      <button class="btn" id="customer-btn" style="width: 200px">
        Customer
      </button>
      <button class="btn" id="worker-btn" style="width: 200px">Worker</button>
    </div>

    <!-- Customer Registration Form (initially hidden) -->
    <form
      id="customer-form"
      class="hidden-form"
      style="display: none"
      action="register.php"
      enctype="multipart/form-data"
      method="post">
      <h3>Customer Registration</h3>
      <input
        type="text"
        name="fullname"
        placeholder="Full Name"
        class="ifield"
        required />
      <input
        type="email"
        name="email"
        placeholder="Email"
        class="ifield"
        required />
      <input
        type="tel"
        name="phone"
        placeholder="Phone No"
        class="ifield"
        required />
      <input
        type="text"
        name="address"
        placeholder="Address"
        class="ifield"
        required />
      <input
        type="password"
        name="password"
        placeholder="Password"
        class="ifield"
        required />
      <label for="profile-pic" class="file-label">Upload Profile Picture</label>
      <input
        type="file"
        name="profile_pic"
        class="ifield"
        id="ppinput"
        accept="image/*" />
      <br />
      <button type="submit" class="btn" name="signupC">Register</button>

    </form>

    <!-- Worker Registration Form (initially hidden) -->
    <form
      id="worker-form"
      class="hidden-form"
      style="display: none"
      action="register.php"
      method="post">
      <h3>Worker Registration</h3>
      <input
        type="text"
        name="fullname"
        placeholder="Full Name"
        class="ifield"
        required />
      <input
        type="email"
        name="email"
        placeholder="Email"
        class="ifield"
        required />
      <input
        type="tel"
        name="phone"
        placeholder="Phone No"
        class="ifield"
        required />


      <label for="location">Location</label>
      <select id="locationSelect" name="location" class="ifield" required>
        <option value="" disabled selected>Select a location</option>
        <option value="Mohammodpur">Mohammodpur</option>
        <option value="Mirpur">Mirpur</option>
        <option value="Dhanmondi">Dhanmondi</option>
        <option value="Jatrabari">Jatrabari</option>
        <option value="Uttara">Uttara</option>
        <option value="Sadarghat">Sadarghat</option>
        <option value="Gulshan">Gulshan</option>
        <option value="Badda">Gulshan</option>
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

      <input
        type="text"
        name="experience"
        placeholder="Experience (in years)"
        class="ifield"
        required />
      <input
        type="number"
        name="base_price"
        placeholder="Base Price"
        class="ifield"
        required />
      <textarea
        name="bio"
        placeholder="Short Bio"
        class="ifield"
        required>
      </textarea>

      <!-- Main Service Specialty dropdown -->
      <label for="service">Service Specialty</label>
      <select id="service" name="service" class="ifield" required>
        <option value="" disabled selected>Select a service specialty</option>
        <option value="AC Servicing">AC Servicing</option>
        <option value="TV Servicing">TV Servicing</option>
        <option value="Fridge">Fridge</option>
        <option value="Induction">Induction</option>
        <option value="Plumbing Service">Plumbing Service</option>
        <option value="Pest Control">Pest Control</option>
        <option value="Gas Service">Gas Service</option>
        <option value="Babysitter">Babysitter</option>
        <option value="Interior Design">Interior Design</option>
        <option value="Home Cleaning">Home Cleaning</option>
        <option value="Laundry">Laundry</option>
        <option value="Home Shifting">Home Shifting</option>
      </select>



      <input
        type="password"
        name="password"
        placeholder="Password"
        class="ifield"
        required />
      <label for="profile-pic" class="file-label">Upload Profile Picture</label>
      <input
        type="file"
        name="profile_pic"
        class="ifield"
        id="ppinput"
        accept="image/*" />
      <br />
      <button type="submit" class="btn" name="signupW">Register</button>
    </form>

    <!-- Login link -->
    <p class="login-link" style="padding-top: 15px">
      Already have an account? <a href="login.php">Log In</a>
    </p>
  </div>

  <script>
    // Customer button event listener
    document
      .getElementById("customer-btn")
      .addEventListener("click", function() {
        document.getElementById("customer-form").style.display = "block";
        document.getElementById("worker-form").style.display = "none";
        document.getElementById("options").style.display = "none"; // Hide options
      });

    // Worker button event listener
    document
      .getElementById("worker-btn")
      .addEventListener("click", function() {
        document.getElementById("worker-form").style.display = "block";
        document.getElementById("customer-form").style.display = "none";
        document.getElementById("options").style.display = "none"; // Hide options
      });
  </script>
</body>

</html>