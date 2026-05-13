<?php
include 'C:/wamp64/www/MAINGYM/db_connect.php';
$qry = $conn->query("SELECT * FROM plans");
echo "Plans:\n";
while($row = $qry->fetch_assoc()){
    echo "ID=" . $row['id'] . ", Plan=" . $row['plan'] . " months, Amount=" . $row['amount'] . "\n";
}

$qry = $conn->query("SELECT r.*, pl.plan FROM registration_info r LEFT JOIN plans pl ON pl.id = r.plan_id WHERE r.member_id = 94 ORDER BY r.id DESC LIMIT 1");
$row = $qry->fetch_assoc();
echo "\nLatest Registration for Member 94:\n";
print_r($row);
?>
