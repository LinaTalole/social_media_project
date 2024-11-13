<?php
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $image_name = basename($_FILES['image']['name']);
    $target_dir = "images/uploads/";
    $target_file = $target_dir . $image_name;
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];
    $conn->query("INSERT INTO posts (user_id, content, image) VALUES ('$user_id', '$content', '$image_name')");
    header('Location: dashboard.php');
}
?>
