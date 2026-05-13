<?php
include 'db_connect.php';
$conn->query("CREATE TABLE IF NOT EXISTS gym_notices (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255), content TEXT, border_color VARCHAR(50) DEFAULT '#4f46e5', date_created DATETIME DEFAULT CURRENT_TIMESTAMP)");
echo "Notices table created";
?>
