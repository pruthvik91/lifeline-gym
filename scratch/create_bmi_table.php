<?php
include 'db_connect.php';
$conn->query("CREATE TABLE IF NOT EXISTS member_bmi_logs (id INT AUTO_INCREMENT PRIMARY KEY, member_id INT, weight FLOAT, height FLOAT, bmi FLOAT, date_created DATETIME DEFAULT CURRENT_TIMESTAMP)");
echo "Table created or already exists";
?>
