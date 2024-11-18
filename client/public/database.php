<?php
    require '../../vendor/autoload.php';
    

    // Load environment variables from .env file
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();

    function getMongoClient() {
        // Initialize the database connection
        $client = new MongoDB\Client(
            $_ENV['MONGODB_URI'],
            [
                'username' => $_ENV['MONGODB_USERNAME'],
                'password' => $_ENV['MONGODB_PASSWORD'],
                'retryWrites' => true,
                'w' => 'majority',
                'appName' => 'FitHUBCluster0'
            ],
        );
    
        return $client;

    }