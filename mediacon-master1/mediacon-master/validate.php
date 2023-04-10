<?php
// capture form inputs from $_POST array
$username = $_POST['username'];
$password = $_POST['password'];

// connect
require('shared/db.php');

// check if username exists
$sql = "SELECT * FROM users WHERE username = :username";
$cmd = $db->prepare($sql);
$cmd->bindParam(':username', $username, PDO::PARAM_STR, 50);
$cmd->execute();
$user = $cmd->fetch();

if (empty($user)) {
    $db = null;
    header('location:login.php?valid=false');
    exit();
}
else {
    // check if hashed password exists
    if (password_verify($password, $user['password']) == false) {
        $db = null;
        header('location:login.php?valid=false');
        exit();
    }
    else {
        // if both credentials found, store the user identity in the $_SESSION object as a var
        // redirect to posts feed
        // *** code dictation lines go here ***
        session_start(); // access the current session automatically created on the web server
        $_SESSION['user'] = $username;
        header('location:posts.php');
        $db = null;
    }
}
?>