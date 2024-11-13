<?php
// index.php

// Start the session (if not already started)
session_start();

// Include functions.php for authentication checks (adjust path if necessary)
if (file_exists(__DIR__ . '/includes/functions.php')) {
    include __DIR__ . '/includes/functions.php';
} else {
    die('Error: functions.php file is missing.');
}

// Check if the user is already logged in
if (!isLoggedIn()) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Project</title>
    <link rel="stylesheet" href="styling/index.css"> <!-- Link to the stylesheet -->
</head>
<body>
    <h1>Welcome to the Social Media Project!</h1>
    <p>This is the home page of your social media platform.</p>
	<a href="dashboard.php">Dashboard</a>
    <a href="profile.php">Go to your profile</a>
    <a href="logout.php">Logout</a>
</body>
</html>
