<?php
include('db_connect.php');
$sql = "CREATE TABLE IF NOT EXISTS member_login_logs (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    member_id INT(11) NOT NULL,
    member_mid VARCHAR(50) NOT NULL,
    member_name VARCHAR(150) NOT NULL,
    login_time DATETIME DEFAULT CURRENT_TIMESTAMP
)";
if($conn->query($sql) === TRUE) {
    echo "Table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
?>
