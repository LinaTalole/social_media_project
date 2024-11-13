<?php
require 'includes/config.php';
require 'includes/functions.php';
require 'includes/auth_session.php';

$user = getCurrentUser();
$username = $_GET['username'] ?? $user['username'];
$posts = getPostsByUser($username);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styling/profile.css">
    <title><?php echo $username; ?>'s Profile</title>
</head>
<body>
    <h2><?php echo $username; ?>'s Posts</h2>
    <div class="post-container">
        <?php while ($post = $posts->fetch_assoc()) { ?>
            <div class="post">
                <p><?php echo $post['content']; ?></p>
                <p>Likes: <?php echo getLikeCount($post['id']); ?></p>
                <form method="POST" action="like.php">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <button type="submit">Like</button>
                </form>
            </div>
        <?php } ?>
    </div>
</body>
</html>
