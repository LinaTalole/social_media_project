<?php
// comment.php
require 'includes/config.php';
require 'includes/auth_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $comment_content = $_POST['comment'];

    // Insert the comment into the database
    $stmt = $conn->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $post_id, $comment_content);
    if ($stmt->execute()) {
        // Redirect back to the dashboard or the same page
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
