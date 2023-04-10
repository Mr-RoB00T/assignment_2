<?php
// access current session first
session_start();

// remove all session vars
session_unset();

// terminate the session
session_destroy();

// redirect to login
header('location:login.php');

?>