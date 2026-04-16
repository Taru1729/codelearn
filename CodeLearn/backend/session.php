<?php
session_start();
require_once 'auth.php';

if (!isLoggedIn() && basename($_SERVER['PHP_SELF']) != 'login.html') {
    header('Location: login.html');
    exit();
}
?>