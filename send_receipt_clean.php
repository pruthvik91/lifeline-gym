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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lifeline Fitness - Receipt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/font-awesome/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.7.2/socket.io.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: white !important;
            color: black !important;
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        /* Force remove any overlays */
        body * {
            background: white !important;
            color: black !important;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white !important;
            color: black !important;
            padding: 20px;
        }
        
        .receipt-header {
            text-align: center;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .receipt-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #333 !important;
            margin-bottom: 10px;
        }
        
        .receipt-header p {
            font-size: 1.1rem;
            color: #666 !important;
            margin: 5px 0;
        }
        
        .member-info {
            background: #f8f9fa !important;
            border: 2px solid #333;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .member-info h3 {
            color: #333 !important;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 0.9rem;
            color: #666 !important;
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 1rem;
            color: #333 !important;
            font-weight: 600;
        }
        
        .payment-section {
            background: #f8f9fa !important;
            border: 2px solid #333;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .payment-section h3 {
            color: #333 !important;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .payment-amount {
            font-size: 2rem;
            font-weight: 800;
            color: #28a745 !important;
            margin: 15px 0;
        }
        
        .payment-method {
            color: #666 !important;
            font-size: 1rem;
        }
        
        .qr-section {
            text-align: center;
            padding: 20px;
            background: #f8f9fa !important;
            border: 2px solid #333;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .qr-section h4 {
            color: #333 !important;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .qr-section img {
            max-width: 200px;
            height: auto;
            border: 2px solid #333;
        }
        
        .terms-section {
            background: #fff3cd !important;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .terms-section h4 {
            color: #856404 !important;
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .terms-section p {
            color: #856404 !important;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .rules-section {
            background: #f8f9fa !important;
            border: 2px solid #333;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .rules-section h4 {
            color: #333 !important;
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .rules-section ul {
            list-style: none;
            padding: 0;
        }
        
        .rules-section li {
            color: #333 !important;
            font-size: 0.9rem;
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }
        
        .rules-section li::before {
            content: '•';
            color: #333 !important;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        
        .receipt-footer {
            background: #333 !important;
            color: white !important;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
        }
        
        .receipt-footer p {
            margin: 5px 0;
            font-size: 0.9rem;
        }
        
        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .btn {
            background: #007bff !important;
            color: white !important;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            font-weight: 600;
        }
        
        .btn:hover {
            background: #0056b3 !important;
        }
        
        .btn-success {
            background: #28a745 !important;
        }
        
        .btn-success:hover {
            background: #1e7e34 !important;
        }
        
        /* Print styles */
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            
            .action-buttons {
                display: none !important;
            }
            
            .receipt-container {
                box-shadow: none !important;
                border: none !important;
            }
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .receipt-container {
                margin: 0;
                padding: 10px;
            }
            
            .receipt-header h1 {
                font-size: 2rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="action-buttons">
        <button class="btn" onclick="downloadReceipt()">
            <i class="fas fa-download"></i> Download
        </button>
        <button class="btn btn-success" id="whatsapp_send" onclick="sendInvoice()">
            <i class="fas fa-paper-plane"></i> Send Receipt
        </button>
    </div>
    
    <div class="receipt-container">
        <div class="receipt-header">
            <h1><i class="fas fa-dumbbell"></i> LIFELINE FITNESS</h1>
            <p>J.T MALL, ABOVE HDFC BANK, AMBAVADI, KESHOD</p>
            <p>Mobile: 9909568777</p>
        </div>
        
        <?php
        $member_query = $conn->query("SELECT *,concat(lastname,' ',firstname,' ',middlename) as name FROM members where member_id=" . $id);
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
                            echo '<div class="payment-amount" style="color: #dc2626 !important;">FEES PENDING</div>';
                        }
                    else :
                        echo '<p style="color: #f59e0b !important; font-weight: 600;">Your membership plan will expire in <strong>' . $days_remaining . ' days</strong>. Please renew it to avoid service interruption.</p>';
                    endif;
                else :
                    echo '<p style="color: #dc2626 !important; font-weight: 600;">Your membership plan has <strong>expired</strong>. Please renew it for uninterrupted service.</p>';
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
            <p style="background: #fff3cd !important; padding: 10px; border-radius: 4px; margin-top: 10px; font-weight: 600;">
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
            </ul>
        </div>
        
        <div class="receipt-footer">
            <p>Thank you for choosing Lifeline Fitness!</p>
            <p>Generated on <?php echo date('d/m/Y H:i:s') ?></p>
        </div>
    </div>
    
    <script>
        // Fix download overlay issues
        function downloadReceipt() {
            // Remove any overlays
            document.body.style.background = 'white';
            document.body.style.color = 'black';
            
            // Remove any modal backdrops
            const overlays = document.querySelectorAll('.modal-backdrop, .overlay, [style*="background-color: rgba(0,0,0"], [style*="background: rgba(0,0,0"]');
            overlays.forEach(overlay => {
                overlay.style.display = 'none';
                overlay.remove();
            });
            
            // Force all elements to have white background
            const allElements = document.querySelectorAll('*');
            allElements.forEach(el => {
                el.style.background = 'white';
                el.style.color = 'black';
            });
            
            // Print the receipt
            window.print();
        }
        
        // Send receipt function
        function sendInvoice() {
            const mobile_number = prompt("Enter mobile number:");
            if (!mobile_number) return;
            
            const cleanNumber = createWhatsappPhone(mobile_number);
            if (!cleanNumber) {
                alert("Invalid mobile number format");
                return;
            }
            
            // Show loading state
            const btn = document.getElementById('whatsapp_send');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            btn.disabled = true;
            
            // Generate receipt image
            html2canvas(document.body, {
                backgroundColor: '#ffffff',
                scale: 2,
                useCORS: true,
                allowTaint: true
            }).then(canvas => {
                const imageData = canvas.toDataURL('image/png');
                
                // Send via WhatsApp
                const socket = io('localhost:3000');
                let connectionAttempts = 0;
                const maxRetries = 5;
                
                socket.on('connect', function() {
                    console.log('Connected to server');
                    socket.emit('sendMessage', {
                        token: '<?php echo $wa_token; ?>',
                        number: cleanNumber,
                        message: 'Here is your receipt from Lifeline Fitness:',
                        image: imageData
                    });
                });
                
                socket.on('messageSent', function(data) {
                    if (data.success) {
                        alert('Receipt sent successfully!');
                        // Log the message
                        $.ajax({
                            url: 'ajaxcall.php',
                            method: 'POST',
                            data: {
                                action: 'message_log',
                                user_id: <?php echo $_SESSION["login_id"]; ?>,
                                member_id: <?php echo $id; ?>,
                                to_number: cleanNumber,
                                wa_token: '<?php echo $wa_token; ?>',
                                status: 1
                            },
                            success: function(response) {
                                console.log('Message logged successfully');
                            }
                        });
                    } else {
                        alert('Failed to send receipt. Please try again.');
                    }
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    socket.disconnect();
                });
                
                socket.on('connect_error', function() {
                    connectionAttempts++;
                    if (connectionAttempts >= maxRetries) {
                        alert('Server connection failed. Please try again later.');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        socket.disconnect();
                    }
                });
                
            }).catch(function(err) {
                console.error('Error generating image:', err);
                alert('Error generating receipt image. Please try again.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
        
        // Clean phone number for WhatsApp
        function createWhatsappPhone(number) {
            number = number.replace(/[+\s-]/g, '');
            if (!/^\d+$/.test(number)) {
                return false;
            }
            if (number.length < 10) {
                return false;
            }
            if (number.startsWith("91") && number.length == 12) {
                return number;
            }
            if (number.startsWith("0") && number.length == 11) {
                return "91" + number.substring(1);
            }
            if (number.length == 10) {
                return "91" + number;
            }
            return number;
        }
        
        // Auto-fix overlays on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Remove any existing overlays
            const overlays = document.querySelectorAll('.modal-backdrop, .overlay, [style*="background-color: rgba(0,0,0"], [style*="background: rgba(0,0,0"]');
            overlays.forEach(overlay => overlay.remove());
            
            // Ensure clean background
            document.body.style.background = 'white';
            document.body.style.color = 'black';
        });
    </script>
</body>
</html>
<?php
}
?>
