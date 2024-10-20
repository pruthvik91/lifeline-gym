<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>

<body>

    <form action="" method="post" class="mx-auto my-5 w-50">
        <div class="form-group">
            <label for="month" class="font-weight-bold">Select a month:</label>
            <select name="month" id="month" class="form-control">
                <option value="1">JANUARY</option>
                <option value="2">FEBRUARY</option>
                <option value="3">MARCH</option>
                <option value="4">APRIL</option>
                <option value="5">MAY</option>
                <option value="6">JUNE</option>
                <option value="7">JULY</option>
                <option value="8">AUGUST</option>
                <option value="9">SEPTEMBER</option>
                <option value="10">OCTOBER</option>
                <option value="11">NOVEMBER</option>
                <option value="12">DECEMBER</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <?php

    if (isset($_POST['month'])) {

        $conn = new mysqli('localhost', 'root', 'root', 'gym_db');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $month = mysqli_real_escape_string($conn, $_POST['month']);

        $query = "SELECT amount FROM payments WHERE MONTH(date_created)='$month'";


        $result = $conn->query($query);

        $sum = 0;

        while ($row = $result->fetch_assoc()) {
            $sum += $row['amount'];
        }
    ?>

        <div class="income-message">The income for <?php echo $month . ' month'; ?> is <?php echo $sum; ?>.</div>




    <?php }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .income-message {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            color: #333;
            margin: 20px 0;
        }
    </style>

</body>

</html>