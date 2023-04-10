<?php
// authentication check for all private pages

// do we have an active session var called 'user'?
session_start();

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    // if not, redirect to login
    header('Location: login.php');
    exit(); // stop all other processing on this page
}
?>
