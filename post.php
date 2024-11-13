<?php
require 'includes/config.php';
require 'includes/functions.php';
require 'includes/auth_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = sanitizeInput($_POST['content']);
    if (!empty($content)) {
        createPost($content);
    }
    header("Location: dashboard.php");
    exit();
}
?>
