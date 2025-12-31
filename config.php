<?php 
date_default_timezone_set('Asia/Manila'); // Set default timezone
session_start(); // Start session management


$db = new DBConnection(); // Create a new database connection instance
$conn = $db->conn; // Get the connection object

?>
