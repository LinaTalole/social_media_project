<?php
session_start();
require 'includes/config.php';
require 'includes/auth_session.php';
require 'includes/functions.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Fetch username from session
$username = $_SESSION['username'];

// Fetch all posts from the database
$posts_query = "SELECT posts.*, users.username, users.profile_pic 
                FROM posts 
                JOIN users ON posts.user_id = users.id 
                ORDER BY posts.created_at DESC";
$posts_result = $conn->query($posts_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styling/dashboard.css">
    <script src="js/scripts.js"></script>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <a href="profile.php">View Profile</a> 
	<a href="logout.php">Logout</a> 
	<a href="search.php">Search</a>

    <hr>

    <!-- Form to Create a New Post -->
    <form method="POST" action="post.php" enctype="multipart/form-data">
        <textarea name="content" placeholder="What's on your mind?" required></textarea>
        <input type="file" name="image" accept="image/*">
        <button type="submit">Post</button>
    </form>

    <hr>

    <!-- Display All Posts -->
    <?php if ($posts_result && $posts_result->num_rows > 0): ?>
        <?php while ($post = $posts_result->fetch_assoc()): ?>
            <div class="post">
                <div class="post-header">
                    <img src="images/profile_pics/<?php echo htmlspecialchars($post['profile_pic']); ?>" alt="Profile Picture" class="profile-pic">
                    <strong><?php echo htmlspecialchars($post['username']); ?></strong>
                </div>
                <p><?php echo htmlspecialchars($post['content']); ?></p>
                <?php if ($post['image']): ?>
                    <img src="images/uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" class="post-image">
                <?php endif; ?>

                <!-- Like Button -->
                <form method="POST" action="like.php">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <button type="submit">Like</button> (<?php echo getLikeCount($post['id'], $conn); ?> Likes)
                </form>

                <!-- Comment Form -->
                <form method="POST" action="comment.php">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <textarea name="comment" placeholder="Add a comment..." required></textarea>
                    <button type="submit">Comment</button>
                </form>

                <!-- Display Comments -->
                <?php
                $comment_query = "SELECT comments.*, users.username 
                                  FROM comments 
                                  JOIN users ON comments.user_id = users.id 
                                  WHERE comments.post_id = {$post['id']} 
                                  ORDER BY comments.created_at DESC";
                $comment_result = $conn->query($comment_query);
                ?>
                <div class="comments-section">
                    <?php if ($comment_result && $comment_result->num_rows > 0): ?>
                        <?php while ($comment = $comment_result->fetch_assoc()): ?>
                            <div class="comment">
                                <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> 
                                <?php echo htmlspecialchars($comment['content']); ?>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No comments yet.</p>
                    <?php endif; ?>
                </div>
            </div>
            <hr>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts available.</p>
    <?php endif; ?>
</body>
</html>
