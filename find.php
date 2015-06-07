<?php
include "secret.php";
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <link href="stylesheet.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="find.js"></script>
    <title>TeaMe! - Find Tea Sellers Near You</title>
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
      <h2>Explore tea sellers near you</h2><br>
      <form method="GET" action="find.php">
        <input type="text" name="zipcode" id="zipfield" placeholder="Zip code" onkeyup="showError(this.value)">
        <input type="submit" id="goBut" value="Go!">
        <div id="error"></div>
      </form>
      <br><br>
    </div>
    <div id="content-lower">
      <br><br>
      <div id="mapFrame">
        <?php
          // If zip code was entered, display map
          if(!empty($_GET['zipcode']))
          {
            echo "<iframe 
                    width='500' 
                    height='500' 
                    frameborder='0' 
                    style='border:0'
                    src='https://www.google.com/maps/embed/v1/search?key=".$maps_key."&q=tea+shops+near+".$_GET['zipcode']."'>
                  </iframe>";
          }
        ?>
      </div>
      <br><br>
    </div>
    <div id="footer">
      <p>CS 290 Final Project by <a href="https://github.com/lblackwell">Lucia Blackwell</a></p>
    </div>
  </div>
</body>
</html>