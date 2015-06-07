<?php
session_start();

include "secret.php";

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", $db_username, $db_pw, $db_username);

if(!$mysqli || $mysqli->connect_errno)
{
  echo "There was a problem connecting to the database.";
}

// Check whether user is logged in
if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['username']))
{
  // Allow access
  echo "";
}
elseif(!empty($_POST['username']) && !empty($_POST['password']))
{
  // Let user log in
  $username = mysqli_real_escape_string($mysqli, $_POST['username']);
  $password = sha1(mysqli_real_escape_string($mysqli, $_POST['password']));
  $checklogin = mysqli_query($mysqli, "SELECT * FROM user WHERE username='".$username."' AND password='".$password."'");
  if(mysqli_num_rows($checklogin) == 1)
  {
    $row = mysqli_fetch_array($checklogin);

    $userID = $row['user_id'];

    $_SESSION['username'] = $username;
    $_SESSION['userID'] = $userID;
    $_SESSION['LoggedIn'] = 1;
  }
  else
  {
    echo "Username and password did not match. <a href='login.php'>Try again</a>";
  }
}
else
{
  // Go back to login page
  header("location: login.php");
  exit();
}

?>

<?php
  // Remove tea if coming from form
  if(!empty($_POST['remTeaID']))
  {
    if(!($rmstmt = $mysqli->prepare("DELETE FROM userTea WHERE user_id = ? AND tea_id = ?")))
    {
      echo "There was an error removing this tea from your collection.";
    }

    if(!$rmstmt->bind_param("ii", $_SESSION['userID'], $_POST['remTeaID']))
    {
      echo "There was an error removing this tea from your collection.";
    }

    if(!$rmstmt->execute())
    {
      echo "There was an error removing this tea from your collection.";
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
    <link href="stylesheet.css" rel="stylesheet" type="text/css">
    <title>TeaMe! - My Collection</title>
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
        // Add tea if coming from add form
        $teaname;
        $teamaker;
        $teatype;
        $teacaf;

        if(!empty($_POST) && isset($_POST['newtea']))
        {
          if(empty($_POST['teaname']))
          {
            echo "The tea needs a name. Please try again.";
          }
          elseif(strlen($_POST['teaname']) > 100)
          {
            echo "Tea name is too long. Please try again. Limit is 100 characters.";
          }
          elseif(empty($_POST['maker']))
          {
            echo "The tea needs a brand or maker. Please try again.";
          }
          elseif(strlen($_POST['maker']) > 100)
          {
            echo "Tea brand or maker name is too long. Please try again. Limit is 100 characters.";
          }
          else
          {
            $teaname = $_POST['teaname'];
            $teamaker = $_POST['maker'];

            if($_POST['type'] == 'black')
            {
              $teatype = "Black";
            }
            elseif($_POST['type'] == 'green')
            {
              $teatype = "Green";
            }
            elseif($_POST['type'] == 'white')
            {
              $teatype = "White";
            }
            elseif($_POST['type'] == 'oolong')
            {
              $teatype = "Oolong";
            }
            elseif($_POST['type'] == 'herbal')
            {
              $teatype = "Herbal";
            }

            if($_POST['caffeine'] == 'caf')
            {
              $teacaf = 1;
            }
            else
            {
              $teacaf = 0;
            }

            // Check for duplicates in sitewide table
            $checkaddquery = "SELECT * FROM tea WHERE name = '".$teaname."' AND maker = '".$teamaker."' AND type = '".$teatype."'";

            $checkResult = mysqli_query($mysqli, $checkaddquery);

            if(mysqli_num_rows($checkResult) == 0)
            {
              // Add tea to sitewide table if no duplicate found
              if(!($stmt = $mysqli->prepare("INSERT INTO tea(name, maker, type, caffeine) VALUES (?, ?, ?, ?)")))
              {
                echo "There was an error adding this tea to the database.";
              }

              if(!$stmt->bind_param("sssi", $teaname, $teamaker, $teatype, $teacaf))
              {
                echo "There was an error adding this tea to the database.";
              }

              if(!$stmt->execute())
              {
                echo "There was an error adding this tea to the database.";
              }
            }

            // Check for duplicates in user's collection
            $newTeaQuery = "SELECT * FROM tea WHERE name='".mysqli_real_escape_string($mysqli, $teaname)."' AND type='".mysqli_real_escape_string($mysqli, $teatype)."' AND maker='".mysqli_real_escape_string($mysqli, $teamaker)."'";

            $newTeaResult = mysqli_query($mysqli, $newTeaQuery);
            if(mysqli_num_rows($newTeaResult) < 1)
            {
              echo "There was a problem retrieving the new tea from the database.";
            }
            else
            {
              while($row = mysqli_fetch_array($newTeaResult))
              {
                $teaID = $row['tea_id'];
              }

              $checkcoladdquery = "SELECT user_id, tea_id FROM userTea WHERE user_id = ".$_SESSION['userID']." AND tea_id = ".$teaID;

              $checkcolResult = mysqli_query($mysqli, $checkcoladdquery);

              if(mysqli_num_rows($checkcolResult) > 0)
              {
                echo "<p>This tea is already in your collection.</p><br>";
              }

              else
              {
                $userID = $_SESSION['userID'];

                if(!($newStmt = $mysqli->prepare("INSERT INTO userTea(user_id, tea_id) VALUES (?, ?)")))
                  {
                    echo "There was an error adding this tea to your collection. 1";
                  }

                if(!$newStmt->bind_param("ii", $userID, $teaID))
                {
                  echo "There was an error adding this tea to your collection. 2";
                }

                if(!$newStmt->execute())
                {
                  echo "There was an error adding this tea to your collection. 3";
                }
              }
            }
          }
        }
      ?>

      <?php
        echo "<h2>".$_SESSION['username']."'s collection</h2><br>";
      
        $query = "SELECT * FROM userTea WHERE user_id=".$_SESSION['userID'];
        $result = mysqli_query($mysqli, $query);

        if(mysqli_num_rows($result) == 0)
        {
          echo "<h3>Your collection is empty.</h3>";
          echo "<p>Add teas below, or select teas from the <a href='browse.php'>TeaMe! database</a>.</p>";
        }

        else
        {
          echo "<table>
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Maker</th>
                      <th>Type</th>
                      <th>Caffeine</th>
                      <th>Remove</th>
                    </tr>
                  </thead>
                  <tbody>";
          while($row = mysqli_fetch_array($result))
          {
            $teaTableQuery = "SELECT * FROM tea WHERE tea_id=".$row['tea_id'];
            $teaResult = mysqli_query($mysqli, $teaTableQuery);

            while($teaRow = mysqli_fetch_array($teaResult))
            {
              if($teaRow['caffeine'])
              {
                $caf = 'Yes';
              }
              else
              {
                $caf = 'No';
              }

              echo "<tr>
                      <td>".$teaRow['name']."</td>
                      <td>".$teaRow['maker']."</td>
                      <td>".$teaRow['type']."</td>
                      <td>".$caf."</td>
                      <td><form class='remButForm' method='POST' action='collection.php'><input type='hidden' name='remTeaID' value='".$teaRow['tea_id']."'><input type='submit' value='x' class='remButton'></form></td>
                    </tr>";
            }
          }
          echo "</tbody>
              </table>";
        }
      ?>
      <br><br>
    </div>
    <div id="content-lower">
      <br><br>
      <h3>Add a tea</h3>
            <br>
            <div id='form'>
              <form id='addTeaForm' method='POST' action='collection.php'>
                <input type="hidden" name="newtea" value="1">
                <input type='text' name='teaname' placeholder='Name of tea'><br>
                <input type='text' name='maker' placeholder='Brand or maker'><br>
                <select name='type'>
                  <option value='black'>Black</option>
                  <option value='green'>Green</option>
                  <option value='white'>White</option>
                  <option value='oolong'>Oolong</option>
                  <option value='herbal'>Herbal</option>
                </select><br>
                <select name='caffeine'>
                  <option value='caf'>Caffeinated</option>
                  <option value='nocaf'>Decaf/Caffeine Free</option>
                </select><br>
                <input type='submit' value='Add Tea'>
              </form>
            </div>
          <br><br>
    </div>
    <div id="footer">
      <p>CS 290 Final Project by <a href="https://github.com/lblackwell">Lucia Blackwell</a></p>
    </div>
  </div>
</body>
</html>