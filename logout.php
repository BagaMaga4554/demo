<?php 
require_once('config.php');

$_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit;
?>