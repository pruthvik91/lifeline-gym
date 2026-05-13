<?php 

$conn= new mysqli('localhost','root','root','gym_db')or die("Could not connect to mysql".mysqli_error($con));

// Set PHP timezone
date_default_timezone_set('Asia/Kolkata');

// Set MySQL timezone
$conn->query("SET time_zone = '+05:30'");

$pdoconn = new PDO("mysql:host=localhost;dbname=gym_db", "root", "root");
$pdoconn->exec("SET time_zone = '+05:30'");
$pdoconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(!defined('URL_VERSION')) define('URL_VERSION', '1.0.1');
