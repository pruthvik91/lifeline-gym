<?php
session_start();
include 'db_connect.php';
$wa_token = '';
global $pdoconn;
$wa_result = $pdoconn->prepare("SELECT wa_token,contact_number FROM whatsapp_token where user_id =:user_id AND status=1");
$wa_result->execute(array(':user_id' => $_SESSION['login_id']));
$wa_rows = $wa_result->fetchAll(PDO::FETCH_OBJ);
$wa_rowcount = count($wa_rows);
if ($wa_rowcount > 0) {
  foreach ($wa_rows as $wa_row) {
    $wa_token = $wa_row->wa_token;
    $contact_number = $wa_row->contact_number;
  }
} 
$id = $_GET['id'] ?? null; // Member ID
$wp = $_GET['wp'] ?? null; // Action, should be 'send'
if (!empty($id) && $wp === 'send') {
?>
<meta content="" name="descriptison">
<meta content="" name="keywords">



<!-- Google Fonts -->
<link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">
<link rel="stylesheet" href="assets/font-awesome/css/all.min.css">


<!-- Vendor CSS Files -->
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
<link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
<link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
<link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<link href="assets/DataTables/datatables.min.css" rel="stylesheet">
<link href="assets/css/jquery.datetimepicker.min.css" rel="stylesheet">
<link href="assets/fullcalendar/main.css" rel="stylesheet">
<link href="assets/css/select2.min.css" rel="stylesheet">


<!-- Template Main CSS File -->
<link href="assets/css/style.css" rel="stylesheet">
<link type="text/css" rel="stylesheet" href="assets/css/jquery-te-1.4.0.css">
<!-- heh -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.css"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.css"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.png"></script>

<script src="https://code.jquery.com/jquery.min.js" integrity="sha256-iauvHiRxsAUlsGlASOF5wPOaJnTjvLNEYOprxIAYgr4=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.4.js" integrity="sha256-iCknuarbJQS1xqgjvYyMUW8h3sbkQf4sj6Io41lRvMg=" crossorigin="anonymous"></script> -->
<script src="assets/vendor/jquery/jquery.min.js"></script> 
<!-- heh -->
<script src="assets/DataTables/datatables.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/venobox/venobox.min.js"></script>
<script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
<script src="assets/vendor/counterup/counterup.min.js"></script>
<script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="assets/js/select2.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="assets/font-awesome/js/all.min.js"></script>
<style>
  img {
		max-width: 227px;
		margin-left: 60px;
		max-height: 150px;
	}
</style>
<!-- <script type="text/javascript" src="assets/js/jquery-te-1.4.0.min.js" charset="utf-8"></script> -->
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
            <input type="hidden" name="mobile_number" value="<?php echo $contact; ?>">
            <input type="hidden" name="user_name" value="<?php echo $name; ?>">
            <input type="hidden" name="invoice_id" value="<?php echo $id; ?>">
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
<div class="invoice-footer">
  <a id="download"  class="button" onclick="downloadInvoice()">Download</a>
  <a id="whatsapp_send" class="button whatsapp_btn">Send Invoice</a>
</div>
<?php // At the beginning of send_receipt.php
// Log incoming parameters
echo json_encode(['status' => 'success', 'message' => 'Message sent successfully.']);
 ?>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="assets/js/socket.io.min.js"></script>
<script src="assets/js/sweetalert2.js"></script>

<script type="text/javascript">
  var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    showClass: {
      popup: 'animate__animated animate__slideInRight'
    },
    hideClass: {
      popup: 'animate__animated animate__slideOutRight'
    },
    timerProgressBar: true,
  });

  function errorMessage(text, title = "Error", html = '', icon = 'warning') {
    Swal.fire({
      icon: icon,
      title: '' + title + '',
      text: text,
      html: html,
      allowEnterKey: false,
      allowEscapeKey: false
    });
  }


  function sendInvoice() {
  var mobile_number = $("input[name='mobile_number']").val();
  mobile_number = createWhatsappPhone(mobile_number);
  const socket = io('localhost:3000');
  let connectionAttempts = 0;
  const maxRetries = 3;

  socket.on('connect', function() {
    console.log('Connected to the Socket');
  });

  socket.on('connect_error', function(error) {
    connectionAttempts++;
    if (connectionAttempts >= maxRetries) {
      console.log('Max connection attempts reached. Stopping Socket.');
      errorMessage('Please try again after some time.', 'Server Disconnected!', '', 'error');
      $("#whatsapp_send").html('Send Message').prop('disabled', false);
      socket.disconnect();
      return; // Exit the function on error
    }
  });

  html2canvas(document.getElementById('htmlContent')).then(function(canvas) {
    const base64PDF = canvas.toDataURL('image/png');

    const wa_token = '<?php echo $wa_token ?>';
    const number = mobile_number;
    const message = `Your gym membership ended. Don't miss out, renew today!`;
    var invoice_id = $('[name="invoice_id"]').val();
    var user_name = $('[name="user_name"]').val().trim();

    socket.emit('send-media', {
      wa_token: wa_token,
      number: number,
      message: message,
      base64Data: base64PDF
    });

    socket.on('messageStatus', function(data) {
        if (data.code == '200') {
        Toast.fire({
            icon: 'success',
            title: 'Message sent successfully'
        }).then((result) => {
            // Notify the parent to remove the iframe
            
            var data = {
                action: 'message_log',
                user_id: <?php echo $_SESSION["login_id"]; ?>,
                member_id: invoice_id,
                to_number: number,
                wa_token: wa_token,
                status: 1
            }
            $.ajax({
                type: "POST",
                url: "ajaxcall.php",
                ContentType: 'application/json',
                dataType: 'json',
                data: data,
                success: function(data) {
                    if (data.status == 'OK') {
                        window.parent.removeIframe('iframe_'+invoice_id,'success');
                    } else {

                    }
                },
                error: function(data) {
                },
                complete: function(data) {
                    // You can perform additional actions here if needed
                }
            });
        });
      } else {
        window.parent.removeIframe('iframe_'+invoice_id,'failed');
        errorMessage('Message could not be sent. Please try again.', 'Error Sending Message', '', 'error');
        $("#whatsapp_send").html('Send Message').prop('disabled', false);
        socket.disconnect();
        return; // Exit the function on error
      }
    });

    socket.on('userLogout', function(userLogout) {
      if (userLogout.code == 401) {
        var data = {
          action: 'authenticateWhatsappSession',
          user_id: <?php echo $_SESSION["login_id"]; ?>,
          wa_token: userLogout.wa_token,
          status: 0
        }
        $.ajax({
          type: "POST",
          url: "ajaxcall.php",
          ContentType: 'application/json',
          dataType: 'json',
          data: data,
          success: function(data) {
            if (data.status == 'OK') {
              $("#WhatsappModal #btnSubmit").html('<i class="fa fa-paper-plane"></i>Share on whatsapp');
              $("#WhatsappModal #btnSubmit").prop('disabled', false);
              $('#WhatsappModal').modal('toggle');
              errorMessage('Please Login With WhatsApp', 'Whatsapp is Logout', '', 'error');
            } else {
              errorMessage('Please try again!', 'Something went wrong', '', 'error');
            }
          },
          error: function(data) {
            errorMessage('AJAX request failed. Please try again.', 'AJAX Error', '', 'error');
          },
          complete: function(data) {
            // You can perform additional actions here if needed
          }
        });

      } else {
        errorMessage(userLogout.message, 'Something went wrong', '', 'error');
      }
    });
  }).catch(function(err) {
    errorMessage('Could not generate invoice image. Please try again.', 'Error', '', 'error');
    $("#whatsapp_send").html('Send Message').prop('disabled', false);
  });
}

  function createWhatsappPhone(number) {
    number = number.replace("+", "");
    number = number.replace("/", "");
    number = number.replace("/", "");
    number = number.replace(" ", "");
    number = number.replace(" ", "");
    number = number.replace("-", "");
    number = number.replace("-", "");
    if (!(/^\d+$/.test(number))) {
      return false;
    } else if (/^\d+$/.test(number) && number.length < 10) {
      return false;
    } else if (number.startsWith("91") && number.length == 12) {
      return number;
    } else if (number.startsWith("0") && number.length == 11) {
      number = number.substring(1);
      return "91" + number;
    } else if (/^\d+$/.test(number) && number.length == 10) {
      return "91" + number;
    } else if (/^\d+$/.test(number)) {
      return number;
    } else {
      return false;
    }
  }
</script>



<script type="text/javascript">

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
</script><?php
if(isset($_GET['wp']) && $_GET['wp']=='send')
{?>
<script>
    sendInvoice();
    </script>
<?php }
}
 ?>

