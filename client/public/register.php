<?php
    /// Include the MongoDB library
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

        // Find the user in MongoDB (for avoid duplicates)
        $user = $collection->findOne(['username' => $username]);
        // var_dump($user);
        // echo($user == null);

            // Check to see if the user does not already exists
        if (!$user) {
            // Hash the password
            $password_hash = password_hash($password, PASSWORD_BCRYPT);            
            
            // Insert the user into the database
            $collection->insertOne([
                'username' => $username,
                'password_hash' => $password_hash
            ]);
            echo '<script>alert("User added!")</script>';
            header('refresh:1;url=login.php');
            exit;
        } else {
            // User already in collection, display an error message
            echo '<script>alert("Username alreadly exists!")</script>';
            header('refresh:1;url=login.php');
            exit;
        }
        
}
