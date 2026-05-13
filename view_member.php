<?php
session_start();
include 'db_connect.php';
$wa_token = '';
global $pdoconn;
if (isset($_SESSION['login_id'])) {
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
} ?>

<style>
    .receipt-body-container {
        background: white;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #1e293b;
        margin: 0 auto;
        transition: all 0.3s ease;
    }

    /* WIDE VIEW: Default/Admin/Download */
    .wide-view {
        width: 800px;
        padding: 60px;
        min-height: 1123px;
    }
    .wide-view .bill-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }
    .wide-view .receipt-details th { padding: 12px 15px; font-size: 0.75rem; }
    .wide-view .receipt-details td { padding: 15px; font-size: 0.9rem; }
    .wide-view .receipt-info h1 { font-size: 2.5rem; }

    /* COMPACT VIEW: Member Preview */
    .compact-view {
        width: 100%;
        max-width: 500px;
        padding: 30px;
        min-height: auto;
    }
    .compact-view .bill-section {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }
    .compact-view .receipt-details th { padding: 8px 10px; font-size: 0.6rem; }
    .compact-view .receipt-details td { padding: 10px; font-size: 0.75rem; }
    .compact-view .receipt-info h1 { font-size: 1.8rem; }
    .compact-view .rules-list { grid-template-columns: 1fr; }

    .receipt-container-wrapper {
        background: #f8fafc;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        border-radius: 1.5rem;
    }

    .receipt-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 40px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 30px;
    }

    .receipt-brand h2 {
        font-weight: 800;
        letter-spacing: -1px;
        color: #0f172a;
        margin-bottom: 5px;
        font-size: 1.5rem;
    }

    .receipt-brand p {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 2px;
        font-weight: 500;
    }

    .receipt-info {
        text-align: right;
    }

    .receipt-info h1 {
        font-weight: 900;
        color: #e2e8f0;
        margin-bottom: 0;
        line-height: 1;
    }

    .receipt-info p {
        font-weight: 700;
        color: #6366f1;
        margin-top: 5px;
    }

    .bill-section {
        margin-bottom: 40px;
    }

    .bill-to h6 {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 1px;
        color: #94a3b8;
        font-weight: 800;
        margin-bottom: 15px;
    }

    .bill-to h4 {
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .bill-to p {
        font-size: 0.9rem;
        color: #475569;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .receipt-details table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .receipt-details th {
        text-align: left;
        background: #f8fafc;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        font-weight: 800;
        border-bottom: 2px solid #e2e8f0;
    }

    .receipt-details td {
        border-bottom: 1px solid #f1f5f9;
        font-weight: 600;
        color: #334155;
    }

    .payment-summary {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        padding: 25px;
        border-radius: 16px;
        margin-bottom: 40px;
    }

    .payment-summary.paid {
        background: #f0fdf4;
        border: 1px solid #dcfce7;
    }

    .payment-summary.pending {
        background: #fdf2f8;
        border: 1px solid #fce7f3;
    }

    .payment-status h4 {
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 5px;
    }

    .payment-badge-success {
        display: inline-block;
        background: #dcfce7;
        color: #15803d;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .payment-badge-danger {
        display: inline-block;
        background: #fee2e2;
        color: #b91c1c;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .payment-status p {
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 0;
        font-weight: 500;
    }

    .qr-section {
        text-align: center;
    }

    .qr-section img {
        width: 120px;
        height: 120px;
        margin-bottom: 8px;
        border: 4px solid white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }

    .qr-section p {
        font-size: 0.65rem;
        font-weight: 800;
        color: #1e293b;
        text-transform: uppercase;
    }

    .rules-section {
        border-top: 2px dashed #e2e8f0;
        padding-top: 30px;
    }

    .rules-section h5 {
        font-weight: 800;
        font-size: 0.9rem;
        color: #0f172a;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .rules-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 30px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .rules-list li {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
        line-height: 1.4;
        position: relative;
        padding-left: 15px;
    }

    .rules-list li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: #6366f1;
        font-weight: 900;
    }

    .invoice-footer-btns {
        padding: 20px;
        background: #f8fafc;
        display: flex;
        justify-content: center;
        gap: 15px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-receipt {
        padding: 12px 25px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        border: none;
    }

    .btn-download { background: #0f172a; color: white; }
    .btn-whatsapp { background: #25d366; color: white; }

    .btn-receipt:hover { transform: translateY(-2px); filter: brightness(1.1); }

    /* Responsive overrides only apply to the view version, not the capture version */
    @media (max-width: 850px) {
        #htmlContent {
            width: 100% !important;
            max-width: 500px !important;
            padding: 8px 5px !important;
            min-height: auto !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        
        #htmlContent .receipt-header {
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            gap: 10px !important;
            margin-bottom: 15px !important;
            padding-bottom: 15px !important;
        }
        
        #htmlContent .receipt-brand img {
            width: 100px !important;
            margin-bottom: 10px !important;
        }
        
        #htmlContent .receipt-brand h2 {
            font-size: 1.25rem !important;
        }
        
        #htmlContent .receipt-info h1 {
            font-size: 1.8rem !important;
        }

        #htmlContent .receipt-info {
            text-align: center !important;
        }

        #htmlContent .bill-section {
            grid-template-columns: 1fr !important;
            gap: 15px !important;
            margin-bottom: 15px !important;
        }
        
        #htmlContent .bill-to h6 {
             margin-bottom: 5px !important;
        }
        
        #htmlContent .bill-to h4 {
            font-size: 1.1rem !important;
        }

        #htmlContent .bill-section .text-end {
            text-align: center !important;
            align-items: center !important;
        }

        #htmlContent .receipt-details table {
            display: block;
            overflow-x: auto;
        }

        #htmlContent .receipt-details td, #htmlContent .receipt-details th {
            padding: 8px 5px !important;
            font-size: 0.8rem !important;
        }

        #htmlContent .payment-summary {
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            gap: 10px !important;
            padding: 12px !important;
            margin-bottom: 15px !important;
        }
        
        #htmlContent .qr-section img {
            width: 100px !important;
            height: 100px !important;
        }

        #htmlContent .rules-list {
            grid-template-columns: 1fr !important;
            gap: 5px !important;
        }

        #htmlContent .rules-section {
            padding-top: 15px !important;
        }
        
        #htmlContent .rules-section h5 {
            font-size: 0.8rem !important;
            margin-bottom: 10px !important;
        }

        .invoice-footer-btns {
            flex-direction: column !important;
            width: 100% !important;
            padding: 20px !important;
            gap: 12px !important;
        }

        .btn-receipt {
            width: 100% !important;
            justify-content: center !important;
        }
    }

    /* Capture Area Styling - Forced A4 */
    #captureArea {
        position: absolute;
        left: -9999px;
        top: 0;
        width: 800px;
        background: white;
    }
    #a4Content {
        width: 800px;
        background: white;
        padding: 50px;
        min-height: 1123px;
    }
</style>

<?php
if (isset($_GET['id'])) {
  $qry = $conn->query("SELECT *,concat(firstname,' ',lastname,' ',middlename) as name FROM members where id=" . $_GET['id'])->fetch_array();
  foreach ($qry as $k => $v) {
    $$k = $v;
  }
}
?>

<?php
ob_start();
?>
        <!-- Receipt Header -->
        <div class="receipt-header">
            <div class="receipt-brand">
                <img src="assets/img/logo.png" alt="Logo" style="width: 120px; margin-bottom: 15px;">
                <h2>LIFELINE FITNESS</h2>
                <p>J.T Mall, Above HDFC Bank</p>
                <p>Ambavadi, Keshod</p>
                <p><i class="fas fa-phone-alt me-1"></i> +91 99095 68777</p>
            </div>
            <div class="receipt-info">
                <h1>RECEIPT</h1>
                <p>#INV-<?php echo str_pad($id, 6, '0', STR_PAD_LEFT) ?></p>
                <div class="mt-4">
                    <span style="color: #94a3b8; font-weight: 700; font-size: 0.8rem;">DATE:</span>
                    <span style="font-weight: 800; color: #1e293b;"><?php echo date("d M, Y") ?></span>
                </div>
            </div>
        </div>

        <!-- Bill Section -->
        <div class="bill-section">
            <div class="bill-to">
                <h6>Billed To Member</h6>
                <h4><?php echo ucwords($name) ?></h4>
                <p><i class="fas fa-id-card me-2 opacity-50"></i> Member ID: <b><?php echo $id ?></b></p>
                <p><i class="fas fa-phone me-2 opacity-50"></i> <?php echo $contact ?></p>
                <p><i class="fas fa-map-marker-alt me-2 opacity-50"></i> <?php echo $address ?></p>
                <p><i class="fas fa-clock me-2 opacity-50"></i> Training Batch: <b><?php echo $batch ?></b></p>
            </div>
            <div class="text-end d-flex flex-column justify-content-end align-items-end">
                <div class="p-3 bg-slate-50 rounded-3 border border-slate-100" style="width: fit-content;">
                    <p class="small text-slate-400 fw-700 mb-1 text-uppercase">Payment Method</p>
                    <p class="fw-800 text-slate-800 mb-0"><i class="fas fa-wallet me-2 text-primary"></i> Digital Payment / Cash</p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="receipt-details">
            <table>
                <thead>
                    <tr>
                        <th>Membership Plan</th>
                        <th>Service Package</th>
                        <th>Start Date</th>
                        <th>Expiry Date</th>
                        <th class="text-end">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $paid = $conn->query("SELECT r.*,pl.plan,pa.package FROM registration_info r inner join plans pl on pl.id = r.plan_id inner join packages pa on pa.id = r.package_id where r.member_id = $id order by id desc limit 1");
                    while ($row = $paid->fetch_assoc()) :
                    $today = strtotime(date('Y-m-d'));
                    $expiry = strtotime($row['end_date']);
                    $is_expired = ($today > $expiry) || ($row['status'] == 0);
                    $days_remaining = ceil(($expiry - $today) / (60 * 60 * 24));
                    ?>
                    <tr>
                        <td><b><?php echo $row['plan'] ?> Months</b> Subscription</td>
                        <td><?php echo $row['package'] ?></td>
                        <td><?php echo date("d M, Y", strtotime($row['start_date'])) ?></td>
                        <td style="color: #e11d48;"><?php echo date("d M, Y", $expiry) ?></td>
                        <td class="text-end">
                            <?php if (!$is_expired) : ?>
                                <span class="badge bg-success text-white px-2 py-1"><?php echo $days_remaining ?> Days Left</span>
                                <?php $message = "Congratulations! Your gym membership is active and valid until " . date("d M, Y", $expiry) . "."; ?>
                            <?php else : ?>
                                <span class="badge bg-danger text-white px-2 py-1">Expired</span>
                                <?php $message = "Your gym membership has expired. Please renew it to continue your fitness journey!"; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Payment & QR Section -->
        <?php
        $paid = $conn->query("SELECT r.*,pl.plan,pa.package FROM registration_info r inner join plans pl on pl.id = r.plan_id inner join packages pa on pa.id = r.package_id where r.member_id = $id order by id desc limit 1 ");
        $row = $paid->fetch_assoc();
        if ($row) {
            $today = strtotime(date('Y-m-d'));
            $expiry = strtotime($row['end_date']);
            $is_expired = ($today > $expiry) || ($row['status'] == 0);
        }
        ?>
        <div class="payment-summary <?php echo $is_expired ? 'pending' : 'paid' ?>">
            <div class="payment-status">
                <?php
                if ($row) :
                    $today = strtotime(date('Y-m-d'));
                    $expiry = strtotime($row['end_date']);
                    if (!$is_expired) :
                        // If NOT expired, show PAID amount
                        $sql = "SELECT * FROM payments where member_id = $id order by id desc limit 1";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($p_row = $result->fetch_assoc()) {
                                echo "<div class='payment-badge-success'>FEES PAID</div>";
                                echo "<h4>₹" . number_format($p_row["amount"]) . "</h4>";
                                echo "<p class='mb-0 text-slate-500'>Received via " . $p_row["remarks"] . " on " . date('d M, Y', strtotime($p_row['date_created'])) . "</p>";
                            }
                        } else {
                            echo "<div class='payment-badge-success'>FEES PAID</div>";
                            echo "<h4>Payment Verified</h4><p class='mb-0 text-slate-500'>Your subscription is active until " . date('d M, Y', $expiry) . "</p>";
                        }
                    else :
                        // If EXPIRED, show PENDING
                        echo "<div class='payment-badge-danger'>FEES PENDING</div>";
                        echo "<h4>MEMBERSHIP EXPIRED</h4><p class='mb-0 text-slate-500'>Please renew your plan to continue services and avoid interruption.</p>";
                    endif;
                endif;
                ?>
            </div>
            <div class="qr-section">
                <?php
                if ($row) :
                    $today = strtotime(date('Y-m-d'));
                    $expiry = strtotime($row['end_date']);
                    $is_expired = ($today > $expiry) || ($row['status'] == 0);
                    $days_remaining = ceil(($expiry - $today) / (60 * 60 * 24));
                    
                    if ($is_expired || $days_remaining <= 5) :
                ?>
                    <img src="./assets/img/lifeline.png" alt="Payment QR">
                    <p>SCAN TO PAY NOW</p>
                <?php 
                    else:
                        echo "<div class='text-center opacity-50'><i class='fas fa-check-circle fa-3x mb-2 text-success'></i><p class='extra-small fw-800 text-slate-400'>ACCOUNT SECURE</p></div>";
                    endif;
                endif; ?>
            </div>
        </div>

        <!-- Rules Section -->
        <div class="rules-section">
            <h5><i class="fas fa-shield-alt text-primary"></i> LIFE LINE FITNESS RULES & POLICIES</h5>
            <ul class="rules-list">
                <li>GYM માં ટ્રેકશુટ અને બુટ પહેરવા ફરજીયાત છે.</li>
                <li>બુટ બહારથી પહેરીને આવવા નહિ, અલગથી સાથે લાવવા.</li>
                <li>GYM ની અંદર પાન-માવા ખાવાની સખ્ત મનાઈ છે.</li>
                <li>ટ્રેનરની સૂચના મુજબ જ કસરત કરવી.</li>
                <li>સાધનો વાપર્યા બાદ તેની યોગ્ય જગ્યાએ મૂકવા.</li>
                <li>ફી સમયસર જમા તેમજ રીન્યુ કરાવવી.</li>
                <li>ખરાબ શબ્દો કે ગેરવ્યાજબી વર્તન પર સખત પ્રતિબંધ છે.</li>
                <li>Treadmill નો વપરાશ ૧૦ મિનિટ સુધી મર્યાદિત છે.</li>
                <li>રજા પાડેલી હશે તો તે ફી માંથી બાદ થશે નહિ.</li>
                <li>ફી રીફંડેબલ કે ટ્રાન્સફરેબલ નથી.</li>
            </ul>
        </div>
<?php
$receipt_html = ob_get_clean();
?>

<div class="container-fluid p-0 receipt-container-wrapper">
    <div id="htmlContent" class="receipt-body-container <?php echo isset($_SESSION['login_id']) ? 'wide-view' : 'compact-view' ?>">
        <?php echo $receipt_html; ?>
    </div>
    
    <!-- Hidden Capture Area for Fixed A4 Snapshot -->
    <div id="captureArea">
        <div id="a4Content" class="receipt-body-container wide-view">
            <?php echo $receipt_html; ?>
        </div>
    </div>

    <!-- Actions Footer -->
    <div class="invoice-footer-btns">
        <input type="hidden" name="mobile_number" value="<?php echo $contact; ?>">
        <input type="hidden" name="user_name" value="<?php echo $name; ?>">
        <input type="hidden" name="invoice_id" value="<?php echo $id; ?>">
        
        <button id="download" class="btn-receipt btn-download" onclick="downloadInvoice()">
            <i class="fas fa-download"></i> Download PDF
        </button>
        <?php if(isset($_SESSION['login_id'])): ?>
        <button id="whatsapp_send" class="btn-receipt btn-whatsapp">
            <i class="fab fa-whatsapp"></i> Send on WhatsApp
        </button>
        <?php endif; ?>
    </div>
</div>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="assets/js/socket.io.min.js"></script>
<script src="assets/js/sweetalert2.js"></script>

<script type="text/javascript">
  var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
  });

  function downloadInvoice() {
    var invoiceContent = document.getElementById('a4Content'); // Use the fixed A4 version for capture
    var invoice_id = $('[name="invoice_id"]').val();
    var user_name = $('[name="user_name"]').val().trim();
    html2canvas(invoiceContent, { scale: 2, useCORS: true }).then(function(canvas) {
      var imageData = canvas.toDataURL('image/png');
      var link = document.createElement('a');
      link.download = 'Receipt_' + invoice_id + '_' + user_name + '.png';
      link.href = imageData;
      link.click();
    });
  }

  function sendInvoice() {
    var mobile_number = $("input[name='mobile_number']").val();
    mobile_number = createWhatsappPhone(mobile_number);
    const socket = io('https://utility.lifelinefitnessstudio.com');
    
    var captureElement = document.getElementById('a4Content'); // Use the fixed A4 version for capture
    html2canvas(captureElement, { scale: 2, useCORS: true }).then(function(canvas) {
      const base64PDF = canvas.toDataURL('image/png');
      const wa_token = '<?php echo $wa_token ?>';
      const number = mobile_number;
      const message = `<?php echo $message; ?>`;
      var invoice_id = $('[name="invoice_id"]').val();

      socket.emit('send-media', {
        wa_token: wa_token,
        number: number,
        user_id: <?php echo $_SESSION["login_id"] ?? 0; ?>,
        inv_id: invoice_id,
        from_number: "receipt",
        message: `<?php echo $message ?? ''; ?>`,
        base64Data: base64PDF,
        mimeType: "image/jpeg",
        filename: "receipt.jpg"
      });

      socket.on('messageStatus', function(data) {
        if (data.code == '200') {
          Toast.fire({ icon: 'success', title: 'Receipt sent to ' + number });
          $("#whatsapp_send").html('<i class="fab fa-whatsapp"></i> Send on WhatsApp').prop('disabled', false);
          setTimeout(function(){
            $('.modal').modal('hide');
          }, 1500);
        }
      });
    });
  }

  $("#whatsapp_send").on('click', function() {
      $(this).html('<i class="fas fa-spinner fa-spin"></i> Sending...').prop('disabled', true);
      sendInvoice();
    });

  function createWhatsappPhone(number) {
    number = number.replace(/[^0-9]/g, "");
    if (number.length == 10) return "91" + number;
    return number;
  }
</script>
