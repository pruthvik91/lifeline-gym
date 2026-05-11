<?php
include 'db_connect.php';
$wa_token = '';
global $pdoconn;
$wa_result = $pdoconn->prepare("SELECT wa_token,contact_number FROM whatsapp_token WHERE user_id =:user_id AND status=1");
$wa_result->execute(array(':user_id' => $_SESSION['login_id']));
$wa_rows = $wa_result->fetchAll(PDO::FETCH_OBJ);
if (count($wa_rows) > 0) {
    $wa_token = $wa_rows[0]->wa_token;
}
?>
<div class="container-fluid py-4">
    <!-- Premium Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 px-2">
        <div>
            <h2 class="fw-800 text-slate-900 mb-1">Expiration Manager</h2>
            <p class="text-slate-500 fw-500 mb-0">Identify and notify members with upcoming or past expirations</p>
        </div>
        <div class="connection-status-pill <?php echo !empty($wa_token) ? 'status-online' : 'status-offline' ?>">
            <i class="fas fa-circle me-2 small"></i>
            <span><?php echo !empty($wa_token) ? 'WhatsApp Connected' : 'WhatsApp Disconnected' ?></span>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-premium rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-4">
            <form method="POST" action="" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-700 text-slate-600">Start Date</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-2 border-end-0 rounded-start-3"><i class="fas fa-calendar-alt text-indigo-500"></i></span>
                        <input type="text" name="start_date" class='start_date_picker form-control border-2 rounded-end-3 py-2' 
                            value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-700 text-slate-600">End Date</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-2 border-end-0 rounded-start-3"><i class="fas fa-calendar-check text-indigo-500"></i></span>
                        <input type="text" name="end_date" class='end_date_picker form-control border-2 rounded-end-3 py-2' 
                            value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-indigo-premium w-100 py-2 rounded-3 fw-700">
                        <i class="fas fa-search me-2"></i> Find Expiring Members
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Card -->
    <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <h5 class="mb-0 fw-800 text-slate-800"><i class="fas fa-users me-2 text-indigo-600"></i>Matching Records</h5>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Mobile Select All -->
                <div class="d-lg-none d-flex align-items-center bg-slate-50 px-3 py-2 rounded-3 border">
                    <span class="extra-small fw-800 text-slate-500 me-2">ALL</span>
                    <label class="custom-check-container mb-0" style="width: 20px; height: 20px;">
                        <input type="checkbox" id="mobile_select_all">
                        <span class="checkmark" style="width: 20px; height: 20px;"></span>
                    </label>
                </div>

                <button class="btn btn-indigo-premium btn-sm rounded-pill px-4 fw-700" type="button" id="send_message">
                    <i class="fas fa-paper-plane me-2"></i> Notify Selected
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="expire-table">
                    <thead>
                        <tr>
                            <th class="ps-4">
                                <label class="custom-check-container">
                                    <input type="checkbox" id="select_all_custom">
                                    <span class="checkmark"></span>
                                </label>
                            </th>
                            <th>Member Details</th>
                            <th>Subscription</th>
                            <th>Expiration</th>
                            <th class="text-center">Remaining</th>
                            <th class="text-end pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $start = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d');
                        $end = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');
                        $members = $conn->query("
                            SELECT r.*, p.plan, pp.package, CONCAT(m.lastname,' ',m.firstname,' ',m.middlename) AS name, m.contact AS mobile_number
                            FROM registration_info r
                            INNER JOIN members m ON m.id = r.member_id
                            INNER JOIN plans p ON p.id = r.plan_id
                            INNER JOIN packages pp ON pp.id = r.package_id
                            WHERE r.status=1 AND DATE(r.end_date) BETWEEN '$start' AND '$end'
                            ORDER BY r.end_date ASC
                        ");
                        while ($row = $members->fetch_assoc()):
                        ?>
                            <tr class="athlete-row">
                                <td class="ps-4">
                                    <label class="custom-check-container">
                                        <input type="checkbox" class="member_checkbox" 
                                            data-mobile="<?php echo $row['mobile_number'] ?>" 
                                            data-id="<?php echo $row['member_id'] ?>">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="athlete-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-800 text-slate-900"><?php echo ucwords($row['name']) ?></div>
                                            <div class="text-slate-400 small fw-600">ID: <?php echo $row['member_id'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-700 text-slate-700"><?php echo $row['plan'] . ' Months' ?></div>
                                    <div class="text-slate-400 small fw-500"><?php echo $row['package'] ?></div>
                                </td>
                                <td>
                                    <div class="fw-700 text-indigo-600"><?php echo date('d M, Y', strtotime($row['end_date'])) ?></div>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $days_remaining = ceil((strtotime($row['end_date']) - time()) / (60 * 60 * 24));
                                    $badge_class = ($days_remaining > 5) ? 'status-active' : (($days_remaining > 0) ? 'status-warning' : 'status-expired');
                                    $label = ($days_remaining > 0) ? "$days_remaining Days Left" : "Expired";
                                    ?>
                                    <span class="validity-badge <?php echo $badge_class ?>">
                                        <i class="fas <?php echo ($days_remaining > 0) ? 'fa-hourglass-half' : 'fa-exclamation-circle' ?> me-1"></i> <?php echo $label ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4 delivery-status">
                                    <span class="text-slate-300"><i class="fas fa-clock me-1"></i> Waiting</span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Invisible Capture Container to prevent layout shifts -->
<div id="temp-capture-shell" style="position: fixed; top: 0; left: -5000px; width: 850px; z-index: -9999; pointer-events: none; background: white;">
    <div id="temp-canvas-container" style="width: 800px; background: white; padding: 20px;"></div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    
    :root {
        --indigo-500: #6366f1;
        --indigo-600: #4f46e5;
        --slate-100: #f1f5f9;
        --slate-300: #cbd5e1;
        --slate-400: #94a3b8;
        --slate-500: #64748b;
        --slate-600: #475569;
        --slate-800: #1e293b;
        --slate-900: #0f172a;
    }

    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    
    .shadow-premium { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02); }
    
    .connection-status-pill {
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
    }
    .status-online { background: #dcfce7; color: #166534; }
    .status-offline { background: #fee2e2; color: #991b1b; }

    .athlete-avatar {
        width: 40px;
        height: 40px;
        background: var(--slate-100);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--indigo-600);
        margin-right: 15px;
    }

    .validity-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
    }
    .status-active { background: #dcfce7; color: #166534; }
    .status-warning { background: #fef3c7; color: #92400e; }
    .status-expired { background: #fee2e2; color: #991b1b; }

    .btn-indigo-premium {
        background: linear-gradient(135deg, var(--indigo-600) 0%, #4338ca 100%);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-indigo-premium:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
        color: white;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .athlete-row { transition: all 0.2s; border-left: 4px solid transparent; }
    .athlete-row:hover { background-color: #f8fafc; border-left-color: var(--indigo-600); }

    /* Custom Premium Checkbox */
    .custom-check-container {
        display: block;
        position: relative;
        padding-left: 30px;
        cursor: pointer;
        user-select: none;
        width: 24px;
        height: 24px;
    }

    .custom-check-container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 24px;
        width: 24px;
        background-color: #fff;
        border: 2px solid var(--slate-300);
        border-radius: 8px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .custom-check-container:hover input ~ .checkmark {
        border-color: var(--indigo-500);
        background-color: #f5f7ff;
    }

    .custom-check-container input:checked ~ .checkmark {
        background-color: var(--indigo-600);
        border-color: var(--indigo-600);
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .custom-check-container input:checked ~ .checkmark:after {
        display: block;
    }

    .custom-check-container .checkmark:after {
        left: 8px;
        top: 4px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2.5px 2.5px 0;
        transform: rotate(45deg);
    }

    .input-group-text { border-color: #e2e8f0; }
    .form-control:focus { border-color: var(--indigo-600); box-shadow: none; }

    /* Mobile Responsive Overrides for Checkbox */
    @media (max-width: 991px) {
        #expire-table tbody td:first-child { 
            display: flex !important; 
            position: absolute !important;
            top: 15px !important;
            right: 15px !important;
            width: auto !important;
            border: none !important;
            background: transparent !important;
            padding: 0 !important;
            z-index: 10 !important;
        }
        
        #expire-table tbody tr {
            position: relative !important;
            padding-right: 50px !important; /* Make room for absolute checkbox */
        }

        /* Adjust Avatar/Details for mobile layout */
        .athlete-avatar {
            width: 45px !important;
            height: 45px !important;
            margin-right: 12px !important;
        }

        .fw-800.text-slate-900 {
            font-size: 1rem !important;
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Extreme Specificity Override */
        body .table-responsive #expire-table tbody tr td:first-child {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: absolute !important;
            top: 20px !important;
            right: 20px !important;
            width: 30px !important;
            height: 30px !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            background: transparent !important;
            z-index: 999 !important;
        }
        
        body .table-responsive #expire-table tbody tr {
            position: relative !important;
            padding-top: 1.5rem !important;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="assets/js/socket.io.min.js"></script>
<script src="assets/js/sweetalert2.js"></script>

<script>
    $(document).ready(function() {
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true,
        });

        const socket = io('https://utility.lifelinefitnessstudio.com/');

        $(".end_date_picker, .start_date_picker").datepicker({
            changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd'
        });

        $("#select_all_custom").change(function() {
            $('.member_checkbox').prop('checked', this.checked);
            $("#mobile_select_all").prop('checked', this.checked); // Sync mobile
            if(this.checked) {
                $('.athlete-row').addClass('bg-indigo-50 border-left-color: var(--indigo-600)');
            } else {
                $('.athlete-row').removeClass('bg-indigo-50');
            }
        });

        $("#mobile_select_all").change(function() {
            $('.member_checkbox').prop('checked', this.checked);
            $("#select_all_custom").prop('checked', this.checked); // Sync desktop
            if(this.checked) {
                $('.athlete-row').addClass('bg-indigo-50');
            } else {
                $('.athlete-row').removeClass('bg-indigo-50');
            }
        });

        $(document).on('change', '.member_checkbox', function() {
            if(this.checked) {
                $(this).closest('.athlete-row').addClass('bg-indigo-50');
            } else {
                $(this).closest('.athlete-row').removeClass('bg-indigo-50');
            }
        });

        function createWhatsappPhone(number) {
            number = String(number).replace(/\D/g, '');
            if (number.length == 10) return "91" + number;
            if (number.length == 11 && number.startsWith('0')) return "91" + number.substring(1);
            return number;
        }

        // Global Progress Listeners
        socket.off("bulk-progress");
        socket.on("bulk-progress", function(data) {
            let row = $('.member_checkbox').filter(function() {
                return createWhatsappPhone($(this).data('mobile')) === createWhatsappPhone(data.number);
            }).closest('tr');

            if (row.length) {
                if (data.status == "sent") {
                    row.find('.delivery-status').html('<span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-check me-1"></i> Sent</span>');
                } else {
                    row.find('.delivery-status').html('<span class="badge bg-danger rounded-pill px-3 py-2"><i class="fas fa-times me-1"></i> Failed</span>');
                }
            }
        });

        socket.off("bulk-complete");
        socket.on("bulk-complete", function(data) {
            $("#send_message").html('<i class="fas fa-paper-plane me-2"></i> Notify Selected').prop('disabled', false);
            Swal.fire({ title: 'Notifications Sent!', text: 'The broadcast has been completed successfully.', icon: 'success' });
        });

        // Handle Send
        $("#send_message").click(async function () {
            let selected = $('.member_checkbox:checked');
            if (selected.length == 0) return Toast.fire({ icon: 'warning', title: 'Please select at least one member' });

            const confirm = await Swal.fire({
                title: 'Launch Broadcast?',
                text: `You are about to send digital receipts to ${selected.length} members.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Send All',
                confirmButtonColor: '#4f46e5'
            });

            if (!confirm.isConfirmed) return;

            $(this).html('<i class="fas fa-spinner fa-spin me-2"></i> Preparing Broadcast...').prop('disabled', true);

            let numbers = [];
            let base64DataArray = [];
            let tempContainer = document.getElementById('temp-canvas-container');

            for (let i = 0; i < selected.length; i++) {
                let member_id = $(selected[i]).data('id');
                let mobile_number = createWhatsappPhone($(selected[i]).data('mobile'));
                
                // Update status in UI
                $(selected[i]).closest('tr').find('.delivery-status').html('<span class="text-indigo-600 fw-700 animate__animated animate__pulse animate__infinite"><i class="fas fa-sync fa-spin me-1"></i> Capturing...</span>');

                numbers.push(mobile_number);

                try {
                    const htmlContent = await $.get('send_receipt.php', { id: member_id, wp: 'send' });
                    const tempDiv = document.createElement('div');
                    tempDiv.style.width = '800px'; // Set fixed width for capture consistency
                    tempDiv.innerHTML = htmlContent;
                    tempContainer.appendChild(tempDiv);

                    // Small delay to allow browser to paint/layout
                    await new Promise(resolve => setTimeout(resolve, 300));

                    const canvas = await html2canvas(tempDiv, { 
                        useCORS: true, 
                        scale: 1.5, // Better quality
                        logging: false,
                        backgroundColor: '#ffffff',
                        windowWidth: 800
                    });
                    
                    base64DataArray.push(canvas.toDataURL('image/jpeg', 0.8));
                    tempContainer.removeChild(tempDiv);
                } catch (err) {
                    console.error("Capture error for member " + member_id, err);
                }
            }

            $(this).html('<i class="fas fa-paper-plane me-2"></i> Sending Messages...');

            socket.emit('send-bulk-media', {
                wa_token: '<?php echo $wa_token ?>',
                user_id: <?php echo $_SESSION['login_id']; ?>,
                numbers,
                message: `Dear member, your membership has expired. Renew today!`,
                mimeType: 'image/jpeg',
                filename: 'receipt.jpg',
                base64DataArray
            });
        });
    });
</script>