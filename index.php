<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <link href="stylesheet.css" rel="stylesheet" type="text/css">
    <title>TeaMe!</title>
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
      <h1>Which tea is for me?</h1><br>
      <h3>Can't decide what to sip? Pick any criteria you want and we'll do the rest.</h3><br>
      <form id="randomTea" method="GET" action="index2.php">
        <p><strong>Type: </strong>
          &emsp;<input type="radio" name="teatype" value="Black"> Black 
          &emsp;<input type="radio" name="teatype" value="Green"> Green 
          &emsp;<input type="radio" name="teatype" value="White"> White 
          &emsp;<input type="radio" name="teatype" value="Oolong"> Oolong
          &emsp;<input type="radio" name="teatype" value="Herbal"> Herbal
        </p>
        <p><strong>Caffeine: </strong>
          &emsp;<input type="radio" name="caffeine" value="yes"> Caffeinated
          &emsp;<input type="radio" name="caffeine" value="no"> Decaf/Caffeine Free
          &emsp;<input type="radio" name="caffeine" value="either" checked="checked"> Either
        </p>
        <p><strong>Use: </strong>
          &emsp;<input type="radio" name="collection" value="user"> My collection
          &emsp;<input type="radio" name="collection" value="site" checked="checked"> TeaMe's tea database
        </p><br>
        <p><input type="submit" value="Tea me! &raquo;"></p>
      </form>
        <br><br>
    </div>
    <div id="content-lower">
      <br><br>
      <h3>About</h3>
      <p>Sometimes, the last thing you want to do is make one more decision. Or maybe you just want to shake things up. TeaMe! will make a random tea suggestion from your collection or from our database based on your criteria. <span id="highlight">We're the shuffle mode your tea cabinet was missing.</span></p>
      <br><br>
    </div>
    <div id="footer">
      <p>CS 290 Final Project by <a href="https://github.com/lblackwell">Lucia Blackwell</a></p>
    </div>
  </div>
</body>
</html>