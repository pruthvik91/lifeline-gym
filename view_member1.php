<div class="container" id="htmlContent">

  <section class="card p-3">
    <div class="card-body">
      <!-- Invoice Company Details -->
      <div id="invoice-company-details" class="row">
        <div class="col-md-4 col-sm-12 text-center text-md-left">
          <div class="media">




            <div class="media-body ">
              <ul class="ml-2 px-0 list-unstyled">
                <li class="text-bold-800">LIFELINE FITNESS</li>
                <li> J.T MALL , ABOVE HDFC BANK</li>
                <li>AMBAVADI,KESHOD</li>
                <li>Mo.9909568777</li>

              </ul>
            </div>
          </div>

        </div>
        <div class="col-md-4 col-sm-12 text-center text-md-left">

          <img src="assets/img/logo.png" alt="">


        </div>

        <div class="col-md-4 col-sm-12 text-center text-md-right">
          <h2>INVOICE</h2>
          <p class="pb-3"># INV-001001</p>

        </div>
      </div>
      <!--/ Invoice Company Details -->
      <!-- Invoice Customer Details -->
      <div id="invoice-customer-details" class="row pt-2">
        <div class="col-sm-12 text-center text-md-left">
          <p class="text-muted">Bill To</p>
        </div>
        <?php include 'db_connect.php' ?>
        <?php
        if (isset($_GET['id'])) {
          $qry = $conn->query("SELECT *,concat(lastname,' ',firstname,' ',middlename) as name FROM members where id=" . $_GET['id'])->fetch_array();
          foreach ($qry as $k => $v) {
            $$k = $v;
          }
        }

        ?>
        <div class="col-md-6 col-sm-12 text-center text-md-left">
          <ul class="px-0 list-unstyled">
            <li><b>ID:<?php echo ucwords($id) ?></b></li>
            <li class="text-bold-800"> <b><?php echo ucwords($name) ?></b></li>
            <li><b><?php echo ucwords($contact) ?></b></li>
            <li><b><?php echo $address ?></b></li>
            <li>
              <p>Batch :</i> <b><?php echo $batch ?></b></p>
            </li>
          </ul>
        </div>
        <div class="col-md-6 col-sm-12 text-center text-md-right">
          <p>
            <span class="text-muted">Invoice Date :</span> <?php echo date("d/m/Y") ?>
          </p>
          <!-- <p>
            <span class="text-muted">Terms :</span> Due on Receipt
          </p>
          <p>
            <span class="text-muted">Due Date :</span> 10/05/2016
          </p> -->
        </div>
      </div>
      <!--/ Invoice Customer Details -->
      <!-- Invoice Items Details -->
      <div id="invoice-items-details" class="pt-2">
        <div class="row">
          <div class="table-responsive col-sm-12">
            <table class="table">
              <thead>
                <tr>

                  <th>Plan </th>
                  <th> Package</th>
                  <th class="text-left">start date</th>
                  <th class="text-left">End date</th>
                  <th class="text-left">status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $pcount = 0;
                $paid = $conn->query("SELECT r.*,pl.plan,pa.package FROM registration_info r inner join plans pl on pl.id = r.plan_id inner join packages pa on pa.id = r.package_id where r.member_id = $id order by id desc limit 1");
                while ($row = $paid->fetch_assoc()) :
                  $pcount++;
                ?>
                  <tr>

                    <td>
                      <p><?php echo $row['plan'] . ' months.' ?></p>

                    </td>
                    <td class="text-left"><?php echo $row['package'] ?></td>
                    <td class="text-left"><?php echo date("M d,Y", strtotime($row['start_date'])) ?></td>
                    <td class="text-left" style="color: red; font-weight: 800;"><?php echo date("M d,Y", strtotime($row['end_date'])) ?></td>
                    <td>

                      <?php if ($row['status'] == 1) : ?>
  <?php 
    $end_date = $row['end_date'];
    $days_remaining = ceil((strtotime($end_date) - time()) / (60 * 60 * 24));
    if ($days_remaining > 0) {
      echo "<span class='badge badge-success'>$days_remaining days remaining</span>";
    } else {
      echo "<span class='badge badge-danger'>Expired</span>";
    }
  ?>
<?php elseif ($row['status'] == 2) : ?>
  <span class="badge badge-secondary">Closed</span>
<?php endif; ?>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6  text-center text-md-left">


            <p class="lead"> <b> Payment </b> </p>



            <div class="row">

              <div class="col-md-8">
                <table class="table table-borderless table-sm">
                  <tbody>
                    <?php
                    $pcount = 0;
                    $paid = $conn->query("SELECT r.*,pl.plan,pa.package FROM registration_info r inner join plans pl on pl.id = r.plan_id inner join packages pa on pa.id = r.package_id where r.member_id = $id order by id desc limit 1 ");
                    while ($row = $paid->fetch_assoc()) :
                      $pcount++;
                      if (strtotime(date('Y-m-d')) <= strtotime($row['end_date'])) :
                        $sql = "SELECT * FROM payments  where member_id = $member_id order by id desc limit 1   ";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          // output data of each row
                          while ($row = $result->fetch_assoc()) {
                            echo "<h4>" . "PAID AMOUNT:" . "" . '<strong> ' . $row["amount"] . '</strong>' . "</h4>";
                            echo "PAID VIA: " . $row["remarks"];
                          }
                        } else {
                          echo  "<h2>FEES PENDING </h2>";
                        }
                      else :
                        echo "<p>Dear member, Your membership plan has been <b>expired</b> please renew it for uninterrupted service.</p>";
                      endif;
                    endwhile;
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-12 text-center text-md-right">

            <?php
            $pcount = 0;
            $paid = $conn->query("SELECT r.*,pl.plan,pa.package FROM registration_info r inner join plans pl on pl.id = r.plan_id inner join packages pa on pa.id = r.package_id where r.member_id = $id order by id desc limit 1");
            while ($row = $paid->fetch_assoc()) :
              $pcount++;
            ?>
              <?php if ($row['status'] == 1) : ?>
                <?php if (strtotime(date('Y-m-d')) <= strtotime($row['end_date'])) : ?>

                <?php else : ?>
                  <B style="margin-top: 50px; font-size: 17px;">USE QR CODE FOR PAYMENT NOW!</B>
                  <img src="./assets/img/lifeline.png" alt="" >
                <?php endif; ?>
              <?php endif; ?>
            <?php endwhile; ?>

          </div>



          <!-- Invoice Footer -->
          <div id="invoice-footer">
            <div class="row">
              <div class="col-md-12 col-sm-12">
                <h6 style="margin-left:477px ;">Terms &amp; Condition</h6>
                <p style="margin-left:239px ;">Please pay fees within two days of expiry date otherwise your <b style="color: red;"> card will not work.</b></p>
                <b style="margin-left: 315px;font-size: 20px; background-color: yellow;">Your fees are non-refundable and non-transferable!!</b>
              </div>

            </div>
          </div>
          <!--/ Invoice Footer -->
        </div>
  </section>
</div>
<a id="download">Download</a>




<script src="assets/js/html2canvas.js">
</script>


<script type="text/javascript">
  function autoClick() {
    $("#download").click();
  }

  $(document).ready(function() {
    var element = $("#htmlContent");

    $("#download").on('click', function() {

      html2canvas(element, {
        onrendered: function(canvas) {
          var imageData = canvas.toDataURL("image/jpg");
          var newData = imageData.replace(/^data:image\/jpg/,
            "data:application/octet-stream");
          $("#download").attr("download", "image.jpg").attr("href", newData);

        }
      });

    });
  });
</script>