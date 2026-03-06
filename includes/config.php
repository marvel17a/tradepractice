<?php
// Start session for user login tracking
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// UPDATE THESE WITH YOUR INFINITYFREE CREDENTIALS
$host = "sql100.infinityfree.com"; // e.g., sql123.epizy.com or sql123.infinityfree.com
$user = "if0_41116335";    // Your InfinityFree vPanel Username
$pass = "edj7gFttqW";    // Your InfinityFree vPanel Password
$db = "if0_41116335_tradedb"; // Your Database Name

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
