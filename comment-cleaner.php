<?php
/*
Plugin Name: Comment Cleaner
Plugin URI: https://github.com/connectpritam/wp-delete-comments
Description: Simple interface to delete all comments.
Version: 1.0
Author: Pritam Mullick
Author URI: https://www.linkedin.com/in/connectpritam/
*/

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Admin menu hook
add_action('admin_menu', 'comment_cleaner_menu');

// Create the menu link in the admin dashboard
function comment_cleaner_menu() {
    add_menu_page('Comment Cleaner', 'Comment Cleaner', 'manage_options', 'comment-cleaner', 'comment_cleaner_admin_page');
}

// Admin page view and functionalities
function comment_cleaner_admin_page() {
    if (!current_user_can('manage_options')) {
        echo '<p>Insufficient privileges to access this page.</p>';
        return;
    }

    echo '<div class="wrap"><h1>Comment Cleaner</h1>';

    // Form for deletion
    echo '<form method="post" action="">';
    wp_nonce_field('clean_comments');
    echo '<input type="hidden" name="clean_comments" value="1"/>';
    echo '<input type="submit" value="Clean All Comments" class="button button-primary" onclick="return confirm(\'Are you sure you want to delete all comments?\');"/>';
    echo '</form>';

    // If the form is submitted
    if (isset($_POST['clean_comments']) && check_admin_referer('clean_comments')) {
        comment_cleaner_delete_comments();
    }

    echo '</div>';
}

// Function to delete all comments
function comment_cleaner_delete_comments() {
    $comments = get_comments();
    foreach ($comments as $comment) {
        wp_delete_comment($comment->comment_ID, true);
    }
    echo '<p>All comments have been deleted.</p>';
}
