<?php
require('shared/auth.php');

try {
    $postId = $_GET['postId'];
    require('shared/db.php');

    $sql = "SELECT * FROM posts WHERE postId = :postId";
    $cmd = $db->prepare($sql);
    $cmd->bindParam(':postId', $postId, PDO::PARAM_INT);
    $cmd->execute();
    $post = $cmd->fetch();

    if ($post['user'] != $_SESSION['user']) {
        $db = null;
        header('location:403.php');
        exit();
    }

    $sql = "DELETE FROM posts WHERE postId = :postId";
    $cmd = $db->prepare($sql);
    $cmd->bindParam(':postId', $postId, PDO::PARAM_INT);
    $cmd->execute();

    $db = null;

    header('location:posts.php');
} catch (Exception $error) {
    header('location:error.php');
    exit();
}
?>
