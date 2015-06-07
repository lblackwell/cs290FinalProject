<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <link href="stylesheet.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="login.js"></script>
    <title>TeaMe! - Log In</title>
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
      <div id="form">
        <br><br>
        <h3>Log in</h3><br>
        <form action="collection.php" method="POST" id="loginForm">
          <input type="text" name="username" id="username" placeholder="Username"><br>
          <input type="password" name="password" id="password" placeholder="Password"><br>
          <div id="error"></div>
          <input type="submit" id="submitButton" onclick="return checkCredentials(); return false" value="Log In">
          <div id="orRegister">
            <p> or <a href="register.php">create an account</a></p>
          </div>
        </form>
        <br><br>
      </div>
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