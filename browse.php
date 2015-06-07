<?php
session_start();

include "secret.php";

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", $db_username, $db_pw, $db_username);

if(!$mysqli || $mysqli->connect_errno)
{
  echo "There was a problem connecting to the database.";
}

// Add tea if coming from add form
if(!empty($_POST))
{
  if(empty($_POST['name']))
  {
    echo "The tea needs a name. Please try again.";
  }
  elseif(strlen($_POST['name']) > 100)
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
    $teaname = $_POST['name'];
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
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="stylesheet.css" rel="stylesheet" type="text/css">
    <title>TeaMe! - Browse Teas</title>
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
      <h2>Browse all teas</h2>
      <h3>Teas submitted by all TeaMe! users.</h3><br>
      <?php
        // Add tea to collection if coming from button
        if(!empty($_POST['addTeaID']))
        {
          // Make sure user is logged in
          if(empty($_SESSION['username']))
          {
            echo "<p><a href='login.php'>Log in</a> to add teas to your collection.</p><br>";
          }

          else
          {
            // Check for duplicate
            $checkaddquery = "SELECT user_id, tea_id FROM userTea WHERE user_id = ".$_SESSION['userID']." AND tea_id = ".$_POST['addTeaID'];

            $checkResult = mysqli_query($mysqli, $checkaddquery);

            if(mysqli_num_rows($checkResult) > 0)
            {
              echo "<p>This tea is already in your collection.</p><br>";
            }

            else
            {
              // Add tea
              if(!$addstmt = $mysqli->prepare("INSERT INTO userTea(user_id, tea_id) VALUES (?, ?)"))
              {
                echo "There was an error adding this tea to your collection.";
              }

              if(!$addstmt->bind_param("ii", $_SESSION['userID'], $_POST['addTeaID']))
              {
                echo "There was an error adding this tea to your collection.";
              }

              if(!$addstmt->execute())
              {
                echo "There was an error adding this tea to your collection.";
              }
              else
              {
                echo "<p><strong>".$_POST['addTeaMaker']." ".$_POST['addTeaName']."</strong> added to your collection.</p><br>";
              }
            }
          }
        }
      ?>
      <?php

        $query = "SELECT * FROM tea";
        $result = mysqli_query($mysqli, $query);

        if(mysqli_num_rows($result) == 0)
        {
          echo "<p>There are no teas in the database!</p>";
        }

        else
        {
          echo "<table>";
          echo "<thead>
                  <tr>
                    <th>Name</th>
                    <th>Maker</th>
                    <th>Type</th>
                    <th>Caffeine</th>
                    <th>+ My Collection</th>
                  </tr>
                </thead>";
          echo "<tbody>";

          while($row = mysqli_fetch_array($result))
          {
            if($row['caffeine'])
            {
              $caf = "Yes";
            }
            else
            {
              $caf = "No";
            }

            echo "<tr>
                    <td>".$row['name']."</td>
                    <td>".$row['maker']."</td>
                    <td>".$row['type']."</td>
                    <td>".$caf."</td>
                    <td>
                      <form class='addButForm' method='POST' action='browse.php'>
                        <input type='hidden' name='addTeaID' value='".$row['tea_id']."'>
                        <input type='hidden' name='addTeaName' value='".$row['name']."'>
                        <input type='hidden' name='addTeaMaker' value='".$row['maker']."'>
                        <input type='submit' value='+' class='addButton'>
                      </form>
                    </td>
                  </tr>";
          }

          echo "</tbody></table>";
        }
      ?>
      <br><br>
    </div>
    <div id="content-lower">
      <br><br>
      <?php
        if(!empty($_SESSION['username']))
        {
          echo "<h3>Add a tea</h3>
            <br>
            <div id='form'>
              <form id='addTeaForm' method='POST' action='browse.php'>
                <input type='text' name='name' placeholder='Name of tea'><br>
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
            </div>";
        }
        else
        {
          echo "<p><a href='login.php'>Log in</a> to add teas.</p>";
        }
      ?>
      <br><br>
    </div>
    <div id="footer">
      <p>CS 290 Final Project by <a href="https://github.com/lblackwell">Lucia Blackwell</a></p>
    </div>
  </div>
</body>
</html>