    <?php
    include 'db_connect.php';

    if(isset($_POST['member_id'])){
        $member_id = $_POST['member_id'];

        // Perform the deletion query
        $sql = "DELETE FROM payments WHERE member_id = $member_id";
        if ($conn->query($sql) === TRUE) {
            echo 1; // Success message
        } else {
            echo 0; // Error message
        }
    }

    $conn->close();
    ?>
