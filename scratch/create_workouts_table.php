<?php
include 'db_connect.php';
$sql = "CREATE TABLE IF NOT EXISTS member_workouts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    status TINYINT(1) DEFAULT 0 COMMENT '0=Pending, 1=Assigned',
    file_path TEXT,
    date_requested DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_assigned DATETIME,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
)";
if($conn->query($sql)){
    echo "member_workouts table created successfully";
}else{
    echo "Error creating table: " . $conn->error;
}
?>
