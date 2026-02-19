<?php
session_start();
require 'functions.php'; // include functions file

logout(); // remove session
header("Location: index.php"); // redirect to login page
exit;
?>

