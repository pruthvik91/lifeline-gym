<?php
// session_start();
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
$id = 65083842; // Member ID
$wp = "wp"; // Action, should be 'send'
// if (!empty($id) && $wp === 'send') {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lifeline Fitness - Receipt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/font-awesome/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            overflow: hidden;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }
        
        .receipt-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .receipt-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }
        
        .receipt-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .receipt-content {
            padding: 2rem;
        }
        
        .member-info {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid #6366f1;
        }
        
        .member-info h3 {
            color: #6366f1;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        
        .info-value {
            font-size: 1rem;
            color: #1e293b;
            font-weight: 600;
        }
        
        .membership-status {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .status-active {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-expired {
            background: #fef2f2;
            color: #dc2626;
        }
        
        .status-closed {
            background: #f1f5f9;
            color: #475569;
        }
        
        .payment-section {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .payment-section h3 {
            color: #1e293b;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .payment-section h3 i {
            margin-right: 0.5rem;
            color: #6366f1;
        }
        
        .payment-amount {
            font-size: 2rem;
            font-weight: 800;
            color: #059669;
            margin: 1rem 0;
        }
        
        .payment-method {
            color: #64748b;
            font-size: 1rem;
        }
        
        .qr-section {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        
        .qr-section h4 {
            color: #1e293b;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .qr-section img {
            max-width: 200px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .terms-section {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .terms-section h4 {
            color: #92400e;
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .terms-section p {
            color: #92400e;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .rules-section {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .rules-section h4 {
            color: #1e293b;
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .rules-section ul {
            list-style: none;
            padding: 0;
        }
        
        .rules-section li {
            color: #475569;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
        }
        
        .rules-section li::before {
            content: '•';
            color: #6366f1;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        
        .receipt-footer {
            background: #1e293b;
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .receipt-footer p {
            margin: 0;
            font-size: 0.875rem;
            opacity: 0.8;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
        }
        
        .print-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
        }
        
        @media print {
            body {
                background: white;
            }
            
            .receipt-container {
                box-shadow: none;
                border-radius: 0;
            }
            
            .print-button {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .receipt-container {
                margin: 0;
                border-radius: 0;
            }
            
            .receipt-header {
                padding: 1.5rem;
            }
            
            .receipt-header h1 {
                font-size: 2rem;
            }
            
            .receipt-content {
                padding: 1rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print Receipt
    </button>
    
    <div class="receipt-container">
        <div class="receipt-header">
            <h1><i class="fas fa-dumbbell"></i> LIFELINE FITNESS</h1>
            <p>J.T MALL, ABOVE HDFC BANK, AMBAVADI, KESHOD</p>
            <p>Mobile: 9909568777</p>
        </div>
        
        <div class="receipt-content">
            <?php
            $member_query = $conn->query("SELECT *,concat(lastname,' ',firstname,' ',middlename) as name FROM members where member_id=65083842");
            $member = $member_query->fetch_assoc();
            ?>
            
            <div class="member-info">
                <h3><i class="fas fa-user"></i> Member Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Member Name</span>
                        <span class="info-value"><?php echo $member['name'] ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Member ID</span>
                        <span class="info-value"><?php echo $member['member_id'] ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Contact</span>
                        <span class="info-value"><?php echo $member['contact'] ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Batch</span>
                        <span class="info-value"><?php echo $member['batch'] ?></span>
                    </div>
                </div>
            </div>
            
            <div class="payment-section">
                <h3><i class="fas fa-credit-card"></i> Payment Information</h3>
                <?php
                $paid = $conn->query("SELECT r.*,pl.plan,pa.package FROM registration_info r inner join plans pl on pl.id = r.plan_id inner join packages pa on pa.id = r.package_id where r.member_id = $id order by id desc limit 1");
                while ($row = $paid->fetch_assoc()) :
                    if (strtotime(date('Y-m-d')) <= strtotime($row['end_date'])) :
                        $days_remaining = ceil((strtotime($row['end_date']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24));
                        if ($days_remaining > 5) :
                            $sql = "SELECT * FROM payments where member_id = $id order by id desc limit 1";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($payment = $result->fetch_assoc()) {
                                    echo '<div class="payment-amount">₹ ' . $payment["amount"] . '</div>';
                                    echo '<div class="payment-method">Paid via: ' . $payment["remarks"] . '</div>';
                                }
                            } else {
                                echo '<div class="payment-amount" style="color: #dc2626;">FEES PENDING</div>';
                            }
                        else :
                            echo '<p style="color: #f59e0b; font-weight: 600;">Your membership plan will expire in <strong>' . $days_remaining . ' days</strong>. Please renew it to avoid service interruption.</p>';
                        endif;
                    else :
                        echo '<p style="color: #dc2626; font-weight: 600;">Your membership plan has <strong>expired</strong>. Please renew it for uninterrupted service.</p>';
                    endif;
                endwhile;
                ?>
            </div>
            
            <div class="qr-section">
                <h4><i class="fas fa-qrcode"></i> Quick Payment</h4>
                <p>Scan QR Code for instant payment</p>
                <img src="./assets/img/lifeline.png" alt="Payment QR Code">
            </div>
            
            <div class="terms-section">
                <h4><i class="fas fa-exclamation-triangle"></i> Important Terms</h4>
                <p><strong>Please pay fees within two days of expiry date otherwise your card will not work.</strong></p>
                <p style="background: #fef3c7; padding: 0.5rem; border-radius: 4px; margin-top: 1rem; font-weight: 600;">
                    Your fees are non-refundable and non-transferable!!
                </p>
            </div>
            
            <div class="rules-section">
                <h4><i class="fas fa-list"></i> Gym Rules</h4>
                <ul>
                    <li>GYM ના થોડા નિયમો, જે આપે પાલન કરવા ફરજીયાત છે</li>
                    <li>GYM ની અંદર આપે ટ્રેકશુટ અને બુટ પહેરવા ફરજીયાત છે. બુટ બેગમાં લઈને આવવા અથવા અહિંયા મુકીને જવા.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ પાન-માવા ખાવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ સિગારેટ પીવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ મોબાઇલ ફોન ચલાવવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગુસ્સો કરવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ લડાઈ કરવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા શબ્દો બોલવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા કપડા પહેરવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા શૂઝ પહેરવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા હાથ લગાવવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા શરીર લગાવવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા માથું લગાવવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા દાંત લગાવવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા નાક લગાવવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા કાન લગાવવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા ગળું લગાવવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા છાતી લગાવવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા પેટ લગાવવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા પીઠ લગાવવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા હાથ લગાવવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા પગ લગાવવાની સખ્ત મનાઈ છે.</li>
                    <li>GYM ની અંદર કોઇ પણ વ્યક્તિ એ ગંદા શરીર લગાવવાની સખ્ત મનાઈ છે.</li>
                </ul>
            </div>
        </div>
        
        <div class="receipt-footer">
            <p>Thank you for choosing Lifeline Fitness!</p>
            <p>Generated on <?php echo date('d/m/Y H:i:s') ?></p>
        </div>
    </div>
    
    <script>
        // Fix for download/print issues
        function downloadReceipt() {
            // Remove any overlays or dark backgrounds
            document.body.style.background = 'white';
            document.body.style.color = 'black';
            
            // Hide any elements that might cause issues
            const elementsToHide = document.querySelectorAll('.modal, .overlay, .backdrop');
            elementsToHide.forEach(el => el.style.display = 'none');
            
            // Print the receipt
            window.print();
        }
        
        // Auto-fix any dark overlays
        document.addEventListener('DOMContentLoaded', function() {
            // Remove any dark overlays
            const overlays = document.querySelectorAll('[style*="background-color: rgba(0,0,0"], [style*="background: rgba(0,0,0"], .modal-backdrop');
            overlays.forEach(overlay => overlay.remove());
            
            // Ensure body has proper background
            document.body.style.background = '#f8fafc';
            document.body.style.color = '#1e293b';
        });
    </script>
</body>
</html>
<?php
// }
?>
