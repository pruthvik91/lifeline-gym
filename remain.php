<?php 
require_once('db_connect.php');
$sql = "SELECT * FROM registration_info where id=1";
if ($res = mysqli_query($conn, $sql)) {
    if (mysqli_num_rows($res) > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>end</th>";
        echo "<th>start</th>";
     
        echo "</tr>";
        while ($row = mysqli_fetch_array($res)) {
            echo "<tr>";
            echo "<td>".$row['end_date']."</td>";
            echo "<td>".$row['start_date']."</td>";
            echo "<td>".$row['start_date - end_date']."</td>";

           
            echo "</tr>";
        }
        echo "</table>";
        mysqli_free_result($res);
    }
    else {
        echo "No matching records are found.";
    }
}
?>