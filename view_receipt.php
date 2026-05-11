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

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT *,concat(lastname,' ',firstname,' ',middlename) as name FROM members where id=" . $_GET['id'])->fetch_array();
    foreach ($qry as $k => $v) {
        $$k = $v;
    }
    
    // Check expiration status for the design mode
    $latest = $conn->query("SELECT end_date, status FROM registration_info where member_id = $id order by id desc limit 1")->fetch_assoc();
    $is_expired = false;
    if ($latest) {
        $is_expired = (strtotime(date('Y-m-d')) > strtotime($latest['end_date'])) || ($latest['status'] == 0);
    }
}

$theme_color = $is_expired ? '#f59e0b' : '#4f46e5';
$header_text = $is_expired ? 'RENEWAL NOTICE' : 'OFFICIAL RECEIPT';
?>

<style>
    .receipt-modern {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        font-family: 'Inter', sans-serif;
        color: #1e293b;
    }
    .receipt-header-banner {
        background: <?php echo $theme_color; ?>;
        padding: 2.5rem;
        color: white;
        position: relative;
    }
    .status-badge-premium {
        position: absolute;
        top: 2rem;
        right: 2rem;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.75rem;
        letter-spacing: 1px;
        backdrop-filter: blur(4px);
    }
    .info-label-receipt {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 800;
        color: #94a3b8;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    .info-value-receipt {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.95rem;
    }
    .receipt-table th {
        background: #f8fafc;
        border: none !important;
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 800;
        color: #64748b;
        padding: 1rem !important;
    }
    .receipt-table td {
        padding: 1.25rem 1rem !important;
        border-bottom: 1px solid #f1f5f9 !important;
        vertical-align: middle !important;
    }
    .qr-payment-container {
        background: #fffbeb;
        border: 2px dashed #f59e0b;
        border-radius: 12px;
        padding: 1.5rem;
    }
    .rules-list {
        list-style: none;
        padding: 0;
        columns: 1;
    }
    .rules-list li {
        font-size: 0.8rem;
        margin-bottom: 0.5rem;
        padding-left: 1.5rem;
        position: relative;
        color: #475569;
    }
    .bg-success-light {
        background: #dcfce7 !important;
    }
    .bg-danger-light {
        background: #fee2e2 !important;
    }
    .rules-list li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: <?php echo $theme_color; ?>;
        font-weight: 900;
    }
    .invoice-footer-actions {
        display: flex;
        gap: 12px;
        margin-top: 2rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
    }
    .btn-receipt {
        flex: 1;
        padding: 0.75rem;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    @media (max-width: 768px) {
        .receipt-header-banner {
            padding: 1.5rem !important;
        }
        .receipt-header-banner img {
            height: 40px !important;
        }
        .receipt-header-banner h2 {
            font-size: 1.25rem !important;
        }
        .status-badge-premium {
            top: 1rem !important;
            right: 1rem !important;
            font-size: 0.65rem !important;
            padding: 0.35rem 0.75rem !important;
        }
        .p-5 {
            padding: 1.5rem !important;
        }
        .row.g-4.mb-5 {
            margin-bottom: 2rem !important;
        }
        .text-md-end {
            text-align: left !important;
            margin-top: 1.5rem;
        }
        .receipt-table th {
            font-size: 0.6rem !important;
            padding: 0.75rem !important;
        }
        .receipt-table td {
            padding: 1rem 0.75rem !important;
            font-size: 0.8rem !important;
        }
        .qr-payment-container {
            padding: 1.25rem !important;
        }
        .invoice-footer-actions {
            flex-direction: column !important;
            padding: 0.75rem !important;
        }
    }
</style>

<div class="container-fluid p-0" id="htmlContent">
    <div class="receipt-modern shadow-lg">
        <!-- Header Section -->
        <div class="receipt-header-banner">
            <div class="status-badge-premium"><?php echo $header_text; ?></div>
            <div class="d-flex align-items-center gap-4">
                <img src="assets/img/logo.png" alt="Lifeline" style="height: 60px; filter: brightness(0) invert(1);">
                <div>
                    <h2 class="fw-900 mb-1 tracking-tighter">LIFELINE FITNESS</h2>
                    <p class="small mb-0 opacity-75 fw-600">J.T MALL, ABOVE HDFC BANK, KESHOD • 9909568777</p>
                </div>
            </div>
        </div>

        <div class="p-5">
            <!-- Client Info -->
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="info-label-receipt">BILLED TO</div>
                    <div class="h5 fw-900 text-slate-900 mb-1"><?php echo ucwords($name) ?></div>
                    <div class="info-value-receipt"><?php echo $contact ?></div>
                    <div class="small text-slate-400 mt-1"><?php echo $address ?></div>
                    <input type="hidden" name="mobile_number" value="<?php echo $contact; ?>">
                    <input type="hidden" name="user_name" value="<?php echo $name; ?>">
                    <input type="hidden" name="invoice_id" value="<?php echo $id; ?>">
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="info-label-receipt">INVOICE DETAILS</div>
                    <div class="info-value-receipt mb-1">REF #<?php echo str_pad($id, 6, '0', STR_PAD_LEFT) ?></div>
                    <div class="info-value-receipt"><?php echo date("d M, Y") ?></div>
                    <div class="mt-2">
                        <span class="badge <?php echo $is_expired ? 'bg-warning text-dark' : 'bg-primary' ?> px-3 py-2 rounded-pill fw-800">
                             BATCH: <?php echo $batch ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-responsive mb-5">
                <table class="table receipt-table">
                    <thead>
                        <tr>
                            <th>Plan Details</th>
                            <th>Package</th>
                            <th>Timeline</th>
                            <th class="text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $paid = $conn->query("SELECT r.*,pl.plan,pa.package FROM registration_info r inner join plans pl on pl.id = r.plan_id inner join packages pa on pa.id = r.package_id where r.member_id = $id order by id desc limit 1");
                        while ($row = $paid->fetch_assoc()) :
                        ?>
                        <tr>
                            <td>
                                <div class="fw-800 text-slate-900"><?php echo $row['plan'] ?> Months Subscription</div>
                                <div class="extra-small text-slate-400 fw-600">Standard Membership Plan</div>
                            </td>
                            <td><span class="fw-700 text-slate-600"><?php echo $row['package'] ?></span></td>
                            <td>
                                <div class="small fw-700 text-slate-900"><?php echo date("M d", strtotime($row['start_date'])) ?> - <?php echo date("M d, Y", strtotime($row['end_date'])) ?></div>
                            </td>
                            <td class="text-end">
                                <?php if ($is_expired): ?>
                                    <span class="badge bg-danger-light text-danger fw-800 rounded-pill px-3 py-1">EXPIRED</span>
                                    <?php $message = "Your gym membership ended. Don't miss out, renew today!"; ?>
                                <?php else: ?>
                                    <span class="badge bg-success-light text-success fw-800 rounded-pill px-3 py-1">ACTIVE</span>
                                    <?php $message = "Congratulations! Your gym membership has been successfully upgraded."; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Bottom Section: Payment / QR -->
            <div class="row g-5 align-items-start">
                <div class="col-md-7">
                    <?php if ($is_expired): ?>
                        <div class="qr-payment-container">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="fas fa-clock small"></i>
                                </div>
                                <div>
                                    <div class="badge bg-danger-light text-danger fw-800 rounded-pill px-3 py-1 mb-1" style="font-size: 0.65rem;">FEES PENDING</div>
                                    <h6 class="fw-900 text-slate-900 mb-0">MEMBERSHIP EXPIRED</h6>
                                </div>
                            </div>
                            <p class="small fw-600 text-slate-600 mb-4">Your membership has ended. Please use the QR code below to settle pending fees and reactivate your access card.</p>
                            <div class="row align-items-center">
                                <div class="col-6">
                                     <img src="./assets/img/lifeline.png" class="img-fluid rounded-3 border bg-white p-2">
                                </div>
                                <div class="col-6">
                                    <div class="extra-small fw-800 text-slate-400 mb-1 uppercase">Scan to Pay</div>
                                    <div class="fw-900 text-slate-900 h6">LIFELINE FITNESS</div>
                                    <div class="extra-small text-slate-400">Accepted via UPI, PhonePe, GPay</div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-slate-50 rounded-4 p-4">
                            <div class="badge bg-success-light text-success fw-800 rounded-pill px-3 py-1 mb-3" style="font-size: 0.65rem;">FEES PAID</div>
                            <h6 class="fw-900 text-slate-900 mb-3">PAYMENT CONFIRMATION</h6>
                            <?php
                            $payment = $conn->query("SELECT * FROM payments where member_id = $id order by id desc limit 1");
                            if ($payment->num_rows > 0):
                                $p_data = $payment->fetch_assoc();
                            ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small fw-600 text-slate-400">Amount Paid</span>
                                    <span class="h5 fw-900 text-primary mb-0">₹ <?php echo number_format($p_data['amount'], 2) ?></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="small fw-600 text-slate-400">Method</span>
                                    <span class="small fw-800 text-slate-900"><?php echo $p_data['remarks'] ?></span>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <div class="extra-small fw-800 text-warning mb-1">NO RECENT PAYMENT FOUND</div>
                                    <p class="extra-small text-slate-400 mb-0">Please contact administration for manual verification.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-5">
                    <h6 class="fw-900 text-slate-900 mb-3">GYM PROTOCOLS</h6>
                    <ul class="rules-list">
                        <li>Tracksuit and shoes are mandatory inside the gym.</li>
                        <li>Carry separate indoor shoes to keep the floor clean.</li>
                        <li>Tobacco and smoking are strictly prohibited.</li>
                        <li>Follow your trainer's instructions only.</li>
                        <li>Return all weights to their designated places after use.</li>
                        <li>Maintain decorum and use appropriate language.</li>
                        <li>Fees are non-refundable and non-transferable.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="p-4 bg-slate-900 text-white text-center">
            <p class="extra-small fw-700 opacity-50 mb-0 tracking-widest">POWERED BY LIFELINE MANAGEMENT SYSTEM • <?php echo date("Y") ?></p>
        </div>
    </div>
</div>

<div class="invoice-footer-actions no-print">
    <button id="download" class="btn-receipt btn btn-white border shadow-sm" onclick="downloadInvoice()">
        <i class="fas fa-file-download text-primary"></i> DOWNLOAD PNG
    </button>
    <button id="whatsapp_send" class="btn-receipt btn btn-primary shadow-premium">
        <i class="fab fa-whatsapp"></i> SEND TO CUSTOMER
    </button>
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
    var invoiceContent = document.getElementById('htmlContent');
    var invoice_id = $('[name="invoice_id"]').val();
    var user_name = $('[name="user_name"]').val().trim();
    html2canvas(invoiceContent, { scale: 2 }).then(function(canvas) {
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
    
    html2canvas(document.getElementById('htmlContent'), { scale: 2 }).then(function(canvas) {
      const base64PDF = canvas.toDataURL('image/png');
      const wa_token = '<?php echo $wa_token ?>';
      const number = mobile_number;
      const message = `<?php echo $message; ?>`;
      var invoice_id = $('[name="invoice_id"]').val();
      
      socket.emit('send-media', {
        wa_token: wa_token,
        number: number,
        user_id: <?php echo $_SESSION["login_id"]; ?>,
        inv_id: invoice_id,
        from_number: "receipt",
        message: message,
        base64Data: base64PDF,
        mimeType: "image/jpeg",
        filename: "receipt.jpg"
      });
      
      socket.on('messageStatus', function(data) {
        if (data.code == '200') {
          Toast.fire({ icon: 'success', title: 'Receipt shared successfully' });
          $("#whatsapp_send").html('<i class="fab fa-whatsapp"></i> SEND TO CUSTOMER').prop('disabled', false);
          setTimeout(function(){
            $('.modal').modal('hide');
          }, 1500);
        }
      });
    });
  }

  $("#whatsapp_send").on('click', function() {
      $(this).html('<i class="fas fa-spinner fa-spin"></i> GENERATING...').prop('disabled', true);
      sendInvoice();
    });

  function createWhatsappPhone(number) {
    number = number.replace(/[^0-9]/g, "");
    if (number.length == 10) return "91" + number;
    if (number.length == 12) return number;
    return number;
  }
</script>


    });
  }
  $("#whatsapp_send").on('click', function() {
      $(this).html('Sharing ...').prop('disabled', true);
      sendInvoice();
    });
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
</script>

