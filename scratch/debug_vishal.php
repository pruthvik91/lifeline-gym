<?php
include 'C:/wamp64/www/MAINGYM/db_connect.php';
$qry = $conn->query("SELECT id, member_id, firstname, lastname FROM members WHERE firstname LIKE '%VISHAL%' AND lastname LIKE '%SUTREJA%'");
while($row = $qry->fetch_assoc()){
    echo "ID=" . $row['id'] . ", DisplayID=" . $row['member_id'] . ", Name=" . $row['firstname'] . " " . $row['lastname'] . "\n";
    $mid = $row['id'];
    $regs = $conn->query("SELECT r.*, pl.plan FROM registration_info r LEFT JOIN plans pl ON pl.id = r.plan_id WHERE r.member_id = $mid ORDER BY r.id DESC");
    while($r = $regs->fetch_assoc()){
        echo "   -> RegID=" . $r['id'] . ", Plan=" . ($r['plan'] ?? 'N/A') . ", Start=" . $r['start_date'] . ", End=" . $r['end_date'] . "\n";
    }
}
?>
