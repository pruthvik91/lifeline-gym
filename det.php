<?php

$conn = mysqli_connect('localhost', 'root', '', 'in');


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$id = $_REQUEST['id'];


$select = "SELECT * FROM info2 WHERE id = '$id'";
$result = mysqli_query($conn, $select);


if ($result) {
   
    $row = mysqli_fetch_assoc($result);

   
    echo "Name: " . $row['name'] . "<br>";
    echo "Age: " . $row['age'] . "<br>";
    echo "Contact: " . $row['contact'] . "<br>";
}


mysqli_close($conn);
?>