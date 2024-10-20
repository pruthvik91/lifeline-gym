<?php

$conn = mysqli_connect('localhost', 'root', '', 'in');


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$select = "SELECT * FROM info2";
$result = mysqli_query($conn, $select);

if ($result) {
    
    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Name</th>";
    echo "<th>Age</th>";
    echo "<th>Contact</th>";
    echo "</tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        
        echo "<tr>";
        
        echo "<td><a href='det.php?id=" . $row['id'] . "'>" . $row['id'] . "</a></td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['age'] . "</td>";
        echo "<td>" . $row['contact'] . "</td>";
        echo "</tr>";
    }

    
    echo "</table>";
}

mysqli_close($conn);
?>