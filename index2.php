<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

session_start();

include "secret.php";

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", $db_username, $db_pw, $db_username);

if(!$mysqli || $mysqli->connect_errno)
{
  echo "There was a problem connecting to the database.";
}

if(isset($_GET['teatype']))
{
  $teatype = $_GET['teatype'];
}
else
{
  $teatype = 0;
}

$teacaffeine = $_GET['caffeine'];

if($teacaffeine == "yes")
{
  $teacaffeine = 1;
}
elseif($teacaffeine == "no")
{
  $teacaffeine = 0;
}

if(isset($_GET['collection']) && $_GET['collection'] === 'user')
{
  $useOwnCollection = 1;
}
else
{
  $useOwnCollection = 0;
}

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
      <?php
        $query;

        // If using own collection, make sure user is logged in
        if($useOwnCollection && empty($_SESSION['userID']))
        {
          echo "<p>You must <a href='login.php'>log in</a> to access your collection.</p>";
        }

        else
        {
          // Use own collection
          if($useOwnCollection)
          {
            // Start of query
            $query = "SELECT tea.name, tea.maker 
                  FROM userTea, tea 
                  WHERE userTea.tea_id = tea.tea_id 
                  AND userTea.user_id = ".$_SESSION['userID'];

            // Tea type
            if($teatype)
            {
              $query .= " AND tea.type='".$teatype."'";
            }

            // Caffeine type
            if($teacaffeine === 1 || $teacaffeine === 0)
            {
              $query .= " AND tea.caffeine=".$teacaffeine;
            }
          }

          // Use sitewide database
          else
          {
            // Start of query
            $query = "SELECT * FROM tea";

            // Type OR caffeine
            if($teatype || $teacaffeine === 1 || $teacaffeine === 0)
            {
              $query .= " WHERE";
            }

            // Tea type
            if($teatype)
            {
              $query .= " type = '".$teatype."'";
            }

            // Type AND caffeine
            if($teatype && ($teacaffeine === 1 || $teacaffeine === 0))
            {
              $query .= " AND";
            }

            // Caffeine
            if($teacaffeine === 1 || $teacaffeine === 0)
            {
              $query .= " caffeine = ".$teacaffeine;
            }
          }

          // Randomize
          $query .= " ORDER BY RAND() LIMIT 1";

          $result = mysqli_query($mysqli, $query);

          if(mysqli_num_rows($result) < 1)
          {
            echo "<h2>No matching teas found!</h2>";
            if(!empty($_SESSION['username']))
              {
                echo  "<p>Try adding some to the <a href='browse.php'>database</a> 
                  or to <a href='collection.php'>your collection</a></p>";
              }
          }

          else
          {
            while($row = mysqli_fetch_array($result))
            {
              echo "<h2>Try some</h2>
              <h1>".$row['name']."</h1>
              <h2>by</h2>
              <h1>".$row['maker']."</h1>";
            }
          }
        }
      ?>
      <br>
      <div id="another-back">
        <input type="button" onClick="location.href='index.php'" value="&laquo; Back" id="back">
        <input type="button" onClick="history.go(0)" value="Another &#10227;" id="another">
      </div>
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