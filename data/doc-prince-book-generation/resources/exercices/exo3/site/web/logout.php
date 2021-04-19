<?php
session_start();
unset($_SESSION['loggedIn']);
unset($_SESSION['username']);
unset($_SESSION['account']);
header("Location: index.php");
    exit;