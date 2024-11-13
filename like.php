<?php
require 'includes/config.php';
require 'includes/functions.php';
require 'includes/auth_session.php';

if (isset($_POST['post_id'])) {
    $postId = $_POST['post_id'];
    toggleLike($postId);
}
header("Location: dashboard.php");
exit();
?>
