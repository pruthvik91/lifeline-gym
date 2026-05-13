<?php 

$conn= new mysqli('localhost','root','root','gym_db')or die("Could not connect to mysql".mysqli_error($con));
$pdoconn = new PDO("mysql:host=localhost;dbname=gym_db", "root", "root");
$pdoconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(!defined('URL_VERSION')) define('URL_VERSION', '1.0.1');
