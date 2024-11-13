<?php
require 'includes/config.php';
require 'includes/functions.php';
require 'includes/auth_session.php';

if (isset($_GET['user_id'])) {
    $followUserId = $_GET['user_id'];
    toggleFollow($followUserId);
}
header("Location: profile.php?username=" . getCurrentUser()['username']);
exit();
?>
