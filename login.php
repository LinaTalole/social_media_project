<?php
session_start();
require 'includes/config.php';
require 'includes/functions.php';

// If user is already logged in, redirect to index.php
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

    // Validate user credentials
    if (loginUser($email, $password)) {
        // Fetch the username for the logged-in user
        $query = "SELECT username FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($username);
        $stmt->fetch();
        $stmt->close();

        // Set the session variables
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        // Redirect to index.php (home page) after successful login
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styling/login.css">
    <title>Login</title>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="register.php">Register</a></p>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        </form>
    </div>
</body>
</html>
