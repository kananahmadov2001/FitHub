<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitHub Login</title>
    <link rel="stylesheet" href="../../common/assets/stylesheets/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
</head>
<body>
    <div class="background">
        <div class="overlay">
            <header>
                <img src="../../common/assets/pics/logo.png" alt="FitHub Logo" class="logo">
                <a href="info.html">
                    <img src="../../common/assets/pics/login-about-avatar.png" alt="Info Icon" class="icon">
                </a>
            </header>
            <div class="welcome-message">WELCOME to FITHUB</div>
            <div class="login-box">
                <div class="login-header">
                  <h2>Sign In</h2>
                </div>
                <div class="login-avatar">
                  <img src="../../common/assets/pics/rounded.webp" alt="User Avatar">
                </div>
                <form action="../server/login.php" method="POST">
                  <div class="input-group">
                    <label for="username"></label>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                    <label for="password"></label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                  </div>
                  <button type="submit" formaction="login.php">Log In</button>
                  <button type="submit" formaction="register.php">Register</button>
                </form>
                <form action="main.php">
                  <button type="submit" formaction="advertisement.php">Continue as Guest</button>
                </form>
              </div>
        </div>
    </div>
</body>
</html>

<?php
  // Include the MongoDB library
  require 'database.php';
  $client = getMongoClient();

  // Select the database and collection
  $database = $client->selectDatabase('m7');
  $collection = $database->selectCollection('users');

  // Check if the form is submitted
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Find the user in MongoDB
    $user = $collection->findOne(['username' => $username]);

    if ($user) { //  Check if the user exists
      if (password_verify($password, $user['password_hash'])) { //  Check if the password matches
        session_start();
        $_SESSION['username'] = $username;
        
        header('Location: advertisement.php');
        exit;
      } else {
        echo '<script>alert("Invalid password!")</script>';
      }
    } else {
      echo '<script>alert("Invalid username!")</script>';
    }
    
  }

  

   
?>