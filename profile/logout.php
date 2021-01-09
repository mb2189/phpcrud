<?php
// Destroy log in session cookies to log out the user and return them to login page
session_start();
unset($_SESSION['name']);
unset($_SESSION['user_id']);
header("Location: index.php");
?>