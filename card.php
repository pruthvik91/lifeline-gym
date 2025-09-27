<?php
include('db_connect.php');
if (isset($_GET['id'])) {
  $qry = $conn->query("SELECT *,concat(lastname,' ',firstname,' ',middlename) as name FROM members where id=" . $_GET['id'])->fetch_array();
  foreach ($qry as $k => $v) {
    $$k = $v;
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Access Cards</title>
    <link rel="stylesheet" href="card.css">

</head>

<body>
    <button id="download" onclick="window.print()">Download</button>


    <div class="item">

        <div class="card" style="height:250px ">
            <div id="printable-area" class="item">
                <figure class="front" style="
    height: 222px;
    width: 377.5px;
">
                    <div class="qr"></div>
                    <p style="
    margin-right: 310px;
    margin-top: 5px;
    font-weight: 600;
    font-family: serif;
"><?php echo ucwords($id) ?></p>
                    <div class="left">
                        <div class="des">
                            <h3 style="
    font-weight: bold;  
    font-size: 14px;
    margin-top: 16px;
    font-family: serif;

"><?php echo ucwords($name) ?></h3>
                            <h5 style="font-size: medium;"><?php echo ucwords($batch) ?></h5>
                        </div>
                        <div class="tri"></div>
                        <div class="top"></div>
                        <div class="bottom"></div>
                        <div class="right">
                            <div class="logo">
                                <img src="./assets/img/logo.png" alt="Gym Logo">
                                <div class="desc"></div>
                            </div>
                            <div class="add">
                                <h5>GYM ACCESS CARD</h5>
                            </div>
                        </div>
                        <div class="foot">
                            <div class="full">
                                <div class="icon call" style="top: 6px;">
                                    <i style="color: white;font-size: 14px; margin-top: 6px;" class="fa fa-phone"
                                        aria-hidden="true"></i>

                                    <h5 style="font-size: 13px;margin-top: 24px;"><?php echo ucwords($contact) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </figure>
            </div>
        </div>
    </div>





</body>
<script>
function printDiv() {
    window.print();
}
</script>

</html>