<?php
session_start();

// Include database login information
include "secret.php";

// Connect to database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", $db_username, $db_pw, $db_username);

// Check connection
if(!$mysqli || $mysqli->connect_errno)
{
  echo "There was a problem connecting to the server.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="stylesheet.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="register.js"></script>
    <script type="text/javascript" src="reg2.js"></script>
    <title>TeaMe! - Sign Up</title>
</head>
<body>
  <div id="page-container">
    <div id="nav">
      <ul>
        <li><strong><a href="index.php" id="homelink">TeaMe!</a></strong></li>
        <?php
          if(empty($_SESSION['username']))
          {
            echo "<li><a href='login.php'>Log In</a></li>";
            echo "<li><a href='register.php'>Sign Up</a></li>";
          }
          else
          {
            echo "<li><a href='logout.php'>Log Out</a></li>";
          }
        ?>
        <li><a href="collection.php">My Collection</a></li>
        <li><a href="browse.php">Browse</a></li>
        <li><a href="find.php">Explore</a></li>
      </ul>
    </div>
    <div id="content-upper">
      <br><br>
      <?php
        if(!empty($_POST['username']) && !empty($_POST['password']))
        {
          $username = mysqli_real_escape_string($mysqli, $_POST['username']);
          $password = sha1(mysqli_real_escape_string($mysqli, $_POST['password']));
          $email = mysqli_real_escape_string($mysqli, $_POST['email']);

          $checkusername = mysqli_query($mysqli, "SELECT * FROM user WHERE username='".$username."'");
          $checkemail = mysqli_query($mysqli, "SELECT * FROM user WHERE email='".$email."'");
          if(mysqli_num_rows($checkusername) == 1)
          {
            echo "<p>That username is already in use. Please <a href='register.php'>try again</a></p>";
          }
          elseif(mysqli_num_rows($checkemail) == 1)
          {
            echo "<p>That email address is already in use. Please <a href='register.php'>try again</a></p>";
          }
          else
          {
            $regquery = mysqli_query($mysqli, "INSERT INTO user (username, password, email) VALUES('".$username."', '".$password."', '".$email."')");
            if($regquery)
            {
              echo "<p>Account created. <a href='login.php'> Click here to log in.</a></p>";
            }
            else
            {
              echo "<p>There was an error registering your account.</p>";
            }
          }
        }

        else
        { ?>
          <h2>Create an account</h2><br>
            <div id='form'>
              <form id='regForm' action='register.php' method='POST'>
                <div class='fieldName'><p>Username</p></div><br>
                <input type='text' name='username' id='username' placeholder='Username' onkeyup='showError(this.value, "username")'><br>
                <div id='usernameError'></div>
                <div class='fieldName'><p>Email Address</p></div><br>
                <input type='text' name='email' id='email' placeholder='Email Address' onkeyup='showError(this.value, "email")'><br>
                <div id='emailError'></div>
                <div class='fieldName'><p>Password</p></div><br>
                <input type='password' name='password' id='password' placeholder='Password' onkeyup='showError(this.value, "password")'><br>
                <div id='passwordError'></div>
                <div id='error'></div>
                <input type='submit' value='Sign Up' id='submitButton' onclick="return checkInput(); return false">
              </form>
            </div>
          <?php
        }
      ?>
      <br><br>
    </div>
    <div id="content-lower">
      <br><br>
    </div>
    <div id="footer">
       <p>CS 290 Final Project by <a href="https://github.com/lblackwell">Lucia Blackwell</a></p>
    </div>
  </div>
</body>
</html>