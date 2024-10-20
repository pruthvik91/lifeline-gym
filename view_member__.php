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
                          $badge_class = $days_remaining <= 7 ? 'badge-danger' : 'badge-success';
                          echo "<span class='badge $badge_class'>$days_remaining days remaining</span>";
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
                        $days_remaining = ceil((strtotime($row['end_date']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24));
                        if ($days_remaining > 5) :
                          $sql = "SELECT * FROM payments  where member_id = $member_id order by id desc limit 1";
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
                          echo "<p>Dear member, Your membership plan will expire in <b>$days_remaining days</b>. Please renew it to avoid service interruption.</p>";
                        endif;
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
                  <img src="./assets/img/lifeline.png" alt="">
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

              
                <ul >
        <b>LIFE LINE FITNESS RULES</b>
        <li>GYM ના થોડા નિયમો, જે આપે પાલન કરવા ફરજીયાત છે</li>
        <li>GYM ની અંદર આપે ટ્રેકશુટ અને બુટ પહેરવા ફરજીયાત છે. બુટ બેગમાં લઈને આવવા અથવા અહિંયા મુકીને જવા.</li>
        <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ પાન-માવા ખાવાની સખ્ત મનાઈ છે.</li>
        <li>Trainer તરફથી જે પણ Exercise નું કહેવામાં આવે એટલી જ Exercise કરવી. તમારી રીતે કોઇ પણ Exercise કરવામાં આવશે</li>
        <li>તો તેની સંપૂર્ણ જવાબદારી તમારી રહેશે. સાધનો જેમકે Dumbbells - Barbells - Matt - Handles બધી જ કસરતો પૂરી થયા પછી તેમની નક્કી કરેલી જગ્યા પર મુકવા ફરજીયાત છે.</li>
        <li>Barbell બનાવતી વખતે તેમાં ક્લિપ લગાવવી ફરજીયાત છે.</li>
        <li>આપની ફી સમયસર જમા તેમજ રીન્યુ કરાવવી.</li>
<li><b style="color:red;">જીમ બંધ કર્યા પછી બે દિવસની અંદર તમારા શૂઝ જીમથી લઈ જવા ત્યારબાદ તમારા શૂઝની કોઈપણ જાતની જવાબદારી જીમની રહેશે નહીં</b></li>
        <li>GYM ની અંદર ખરાબ શબ્દો તેમજ ગેરવ્યાજબી વર્તન કરવાની સખ્ત મનાઈ છે.</li>
        <li>Treadmill નો વપરાશ ૧૦ મિનિટ પુરતો મર્યાદિત છે. એથી વધારે કરવું હોય તો ચાર્જ અલગથી આપવાનો રહેશે.</li>
        <li>Treadmill થઈ ગયા પછી સ્વિચ બંધ કરવી.</li>
        <li>આપે પાડેલી રજા ફી માંથી બાદ થશે નહિં.</li>
        <li>GYM પુરી રીતે કેમેરાથી સુરક્ષિત છે.</li>
        <li>GYM ના કોઈ પણ સભ્ય એ પોતાના મિત્રો ને સાથે બેસવા લઇ આવવા નહિં.</li>
        <li>GYM માં કામ વગર બેસવાની સખ્ત મનાઇ છે.</li>
        <li>ઉપર મુજબના બધા નિયમ પાળવા તમે સજ્જ હોવ તો જ GYM ની મેમ્બરશીપ લેવી</li>

      </ul>
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


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.js"></script>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<script type="text/javascript">
  function autoClick() {
    $("#download").click();
  }

  $(document).ready(function() {
    var element = document.getElementById("htmlContent");

    $("#download").on('click', function() {
      html2canvas(element, {
        scrollY: -window.scrollY,
        scale: 2,
        useCORS: true
      }).then(function(canvas) {
        var imageData = canvas.toDataURL("image/jpeg");
        var link = document.createElement('a');
        link.href = imageData;
        link.download = 'receipt.jpg';
        link.click();
      });
    });
  });

  function closeOpenModal() {
    var openModals = document.querySelectorAll('.modal.show');
    openModals.forEach(function(modal) {
      $(modal).modal('hide');
    });
  }

  document.addEventListener('keydown', function(event) {
    if (event.keyCode === 27) {
      closeOpenModal();
    }
  });
</script>
