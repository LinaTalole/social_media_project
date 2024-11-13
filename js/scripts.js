document.addEventListener('DOMContentLoaded', () => {
    // Handle Like Button Click
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.dataset.postId;
            const likeCount = this.nextElementSibling;

            fetch(`like.php?post_id=${postId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'liked') {
                        this.classList.add('liked');
                    } else {
                        this.classList.remove('liked');
                    }
                    likeCount.textContent = `${data.likes} Likes`;
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Toggle Comments Section
    document.querySelectorAll('.comment-toggle').forEach(button => {
        button.addEventListener('click', function () {
            const commentSection = this.nextElementSibling;
            commentSection.classList.toggle('hidden');
        });
    });

    // Handle Follow/Unfollow Button Click
    document.querySelectorAll('.follow-btn').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.dataset.userId;

            fetch(`follow.php?user_id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'following') {
                        this.textContent = 'Unfollow';
                        this.classList.add('unfollow');
                    } else {
                        this.textContent = 'Follow';
                        this.classList.remove('unfollow');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Notifications Pop-up
    const notificationBell = document.getElementById('notification-bell');
    const notificationList = document.getElementById('notification-list');

    if (notificationBell) {
        notificationBell.addEventListener('click', () => {
            notificationList.classList.toggle('hidden');
        });
    }
});
