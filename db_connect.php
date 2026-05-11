<?php 

$conn= new mysqli('localhost','u593981822_lifeline','B7w~;;>h$D6','u593981822_gym_db')or die("Could not connect to mysql".mysqli_error($con));
$pdoconn = new PDO("mysql:host=localhost;dbname=u593981822_gym_db", "u593981822_lifeline", 'B7w~;;>h$D6');
$pdoconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
