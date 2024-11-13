<?php
require 'includes/config.php';
require 'includes/functions.php';
require 'includes/auth_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    header("Location: profile.php?username=$username");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styling/search.css">
    <title>Search Users</title>
</head>
<body>
    <div class="search-container">
        <h2>🔍 Search Users</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter username" required>
            <button type="submit">Search</button>
        </form>
    </div>
</body>
</html>
