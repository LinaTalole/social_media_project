<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Connect to the database
function dbConnect() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "social_media_db";

    // Create a new connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Sanitize user input to prevent SQL Injection
function sanitizeInput($input) {
    $conn = dbConnect();
    return htmlspecialchars($conn->real_escape_string(trim($input)));
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get the currently logged-in user
function getCurrentUser() {
    if (isLoggedIn()) {
        $conn = dbConnect();
        $userId = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE id = '$userId'";
        $result = $conn->query($query);
        return $result->fetch_assoc();
    }
    return null;
}

// Register a new user
function registerUser($username, $email, $password) {
    $conn = dbConnect();
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    return $stmt->execute();
}

// Authenticate user login
function loginUser($email, $password) {
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    return false;
}

// Create a new post
function createPost($content, $image = null) {
    if (isLoggedIn()) {
        $conn = dbConnect();
        $userId = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $content, $image);
        return $stmt->execute();
    }
    return false;
}

// Fetch all posts (for the feed)
function getAllPosts() {
    $conn = dbConnect();
    $query = "SELECT posts.*, users.username FROM posts 
              JOIN users ON posts.user_id = users.id 
              ORDER BY posts.created_at DESC";
    return $conn->query($query);
}

// Get posts by a specific user
function getPostsByUser($username) {
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT posts.*, users.username FROM posts 
                            JOIN users ON posts.user_id = users.id 
                            WHERE users.username = ? 
                            ORDER BY posts.created_at DESC");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    return $stmt->get_result();
}

// Like or unlike a post
function toggleLike($postId) {
    if (isLoggedIn()) {
        $conn = dbConnect();
        $userId = $_SESSION['user_id'];

        // Check if the user already liked the post
        $checkQuery = "SELECT * FROM likes WHERE user_id = '$userId' AND post_id = '$postId'";
        $result = $conn->query($checkQuery);

        if ($result->num_rows > 0) {
            // Unlike the post
            $conn->query("DELETE FROM likes WHERE user_id = '$userId' AND post_id = '$postId'");
            return "unliked";
        } else {
            // Like the post
            $conn->query("INSERT INTO likes (user_id, post_id) VALUES ('$userId', '$postId')");
            return "liked";
        }
    }
    return null;
}

// Get like count for a post
function getLikeCount($postId) {
    $conn = dbConnect();
    $result = $conn->query("SELECT COUNT(*) as like_count FROM likes WHERE post_id = '$postId'");
    $row = $result->fetch_assoc();
    return $row['like_count'];
}

// Follow or unfollow a user
function toggleFollow($followUserId) {
    if (isLoggedIn()) {
        $conn = dbConnect();
        $userId = $_SESSION['user_id'];

        // Check if already following
        $checkQuery = "SELECT * FROM followers WHERE follower_id = '$userId' AND following_id = '$followUserId'";
        $result = $conn->query($checkQuery);

        if ($result->num_rows > 0) {
            // Unfollow
            $conn->query("DELETE FROM followers WHERE follower_id = '$userId' AND following_id = '$followUserId'");
            return "unfollowed";
        } else {
            // Follow
            $conn->query("INSERT INTO followers (follower_id, following_id) VALUES ('$userId', '$followUserId')");
            return "following";
        }
    }
    return null;
}

// Fetch notifications for the current user
function getNotifications() {
    if (isLoggedIn()) {
        $conn = dbConnect();
        $userId = $_SESSION['user_id'];
        $query = "SELECT * FROM notifications WHERE user_id = '$userId' ORDER BY created_at DESC";
        return $conn->query($query);
    }
    return [];
}

// Logout the user
function logout() {
    session_unset();
    session_destroy();
}
?>
