<?php
session_start();
include 'db_connect.php';
$wa_token = '';
global $pdoconn;
$wa_result = $pdoconn->prepare("SELECT wa_token,contact_number FROM whatsapp_token where user_id =:user_id AND status=1");
$wa_result->execute(array(':user_id' => $_SESSION['login_id']));
$wa_rows = $wa_result->fetchAll(PDO::FETCH_OBJ);
if (count($wa_rows) > 0) {
  $wa_token = $wa_rows[0]->wa_token;
}

$id = $_GET['id'] ?? null;
if (!empty($id)) {
    $qry = $conn->query("SELECT *,concat(firstname,' ',lastname,' ',middlename) as name FROM members where id=$id")->fetch_array();
    foreach ($qry as $k => $v) {
        $$k = $v;
    }
}
?>
<!-- Google Fonts & Font Awesome -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/font-awesome/css/all.min.css">
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<style>
    #htmlContent {
        background: white;
        padding: 50px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #1e293b;
        width: 800px;
        margin: 0 auto;
        min-height: 1123px;
        box-shadow: 0 0 40px rgba(0,0,0,0.05);
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
        font-size: 2.5rem;
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
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
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
        padding: 12px 15px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        font-weight: 800;
        border-bottom: 2px solid #e2e8f0;
    }

    .receipt-details td {
        padding: 15px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
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
</style>

<div id="htmlContent">
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
            <div class="p-3 bg-light rounded-3 border" style="width: fit-content;">
                <p class="small text-muted fw-700 mb-1 text-uppercase">Payment Method</p>
                <p class="fw-800 text-dark mb-0"><i class="fas fa-wallet me-2 text-primary"></i> Digital Payment / Cash</p>
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
                $paid_qry = $conn->query("SELECT r.*,pl.plan,pa.package FROM registration_info r inner join plans pl on pl.id = r.plan_id inner join packages pa on pa.id = r.package_id where r.member_id = $id order by id desc limit 1");
                while ($row = $paid_qry->fetch_assoc()) :
                $today = strtotime(date('Y-m-d'));
                $expiry = strtotime($row['end_date']);
                $is_expired = ($today > $expiry) || ($row['status'] == 0);
                ?>
                <tr>
                    <td><b><?php echo $row['plan'] ?> Months</b> Subscription</td>
                    <td><?php echo $row['package'] ?></td>
                    <td><?php echo date("d M, Y", strtotime($row['start_date'])) ?></td>
                    <td style="color: #e11d48;"><?php echo date("d M, Y", $expiry) ?></td>
                    <td class="text-end">
                        <?php if (!$is_expired) : ?>
                            <span class="badge bg-success text-white px-2 py-1">Active</span>
                        <?php else : ?>
                            <span class="badge bg-danger text-white px-2 py-1">Expired</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Payment & QR Section -->
    <?php
    $reg = $conn->query("SELECT r.* FROM registration_info r where r.member_id = $id order by id desc limit 1 ")->fetch_assoc();
    $is_expired = false;
    if ($reg) {
        $today = strtotime(date('Y-m-d'));
        $expiry = strtotime($reg['end_date']);
        $is_expired = ($today > $expiry) || ($reg['status'] == 0);
    }
    ?>
    <div class="payment-summary <?php echo $is_expired ? 'pending' : 'paid' ?>">
        <div class="payment-status">
            <?php if (!$is_expired) : 
                $p_sql = "SELECT * FROM payments where member_id = $id order by id desc limit 1";
                $p_res = $conn->query($p_sql);
                if ($p_res->num_rows > 0) {
                    $p_row = $p_res->fetch_assoc();
                    echo "<div class='payment-badge-success'>FEES PAID</div>";
                    echo "<h4 class='fw-800'>₹" . number_format($p_row["amount"]) . "</h4>";
                    echo "<p class='mb-0 text-muted'>Received via " . $p_row["remarks"] . "</p>";
                } else {
                    echo "<div class='payment-badge-success'>FEES PAID</div>";
                    echo "<h4 class='fw-800'>Payment Verified</h4><p class='mb-0 text-muted'>Subscription active</p>";
                }
            else : ?>
                <div class='payment-badge-danger'>FEES PENDING</div>
                <h4 class='fw-800'>MEMBERSHIP EXPIRED</h4>
                <p class='mb-0 text-muted'>Please renew your plan to continue services.</p>
            <?php endif; ?>
        </div>
        <div class="qr-section">
            <?php if ($is_expired) : ?>
                <img src="./assets/img/lifeline.png" alt="QR">
                <p>SCAN TO PAY NOW</p>
            <?php else : ?>
                <div class='text-center opacity-50'><i class='fas fa-check-circle fa-3x mb-2 text-success'></i><p class='extra-small fw-800 text-muted'>ACCOUNT SECURE</p></div>
            <?php endif; ?>
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
</div>
