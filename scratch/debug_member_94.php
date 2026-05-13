<?php
include 'C:/wamp64/www/MAINGYM/db_connect.php';
$id = 94; // Vishal Sutreja
echo "Member ID: $id\n";
$qry = $conn->query("SELECT * FROM members WHERE id = $id OR member_id = '$id'");
while($row = $qry->fetch_assoc()){
    echo "Found Member: ID=" . $row['id'] . ", DisplayID=" . $row['member_id'] . ", Name=" . $row['firstname'] . " " . $row['lastname'] . "\n";
    $mid = $row['id'];
    $regs = $conn->query("SELECT r.*, p.plan FROM registration_info r LEFT JOIN plans p ON p.id = r.plan_id WHERE r.member_id = $mid ORDER BY r.id DESC");
    echo "Registrations:\n";
    while($r = $regs->fetch_assoc()){
        echo " - RegID=" . $r['id'] . ", PlanID=" . $r['plan_id'] . ", PlanName=" . $r['plan'] . " months, Start=" . $r['start_date'] . ", End=" . $r['end_date'] . "\n";
    }
}
?>
