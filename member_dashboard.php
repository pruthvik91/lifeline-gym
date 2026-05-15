<?php 
session_start();
include('./db_connect.php');
if(!isset($_SESSION['member_id']))
    header("location:login");

$member_id = $_SESSION['member_id'];
$query = $conn->query("SELECT r.*, p.plan, pp.package, m.firstname, m.lastname, m.middlename, m.member_id as mid, m.email, m.contact, m.profile_pic, m.address 
                       FROM members m 
                       LEFT JOIN registration_info r ON r.member_id = m.id 
                       LEFT JOIN plans p ON p.id = r.plan_id 
                       LEFT JOIN packages pp ON pp.id = r.package_id 
                       WHERE m.id = $member_id 
                       ORDER BY r.id DESC LIMIT 1");
$data = $query->fetch_array();

// Calculate status
$status = 'No Active Plan';
$status_class = 'status-inactive';
$days_left = 0;

if($data && isset($data['end_date'])){
    $end = strtotime($data['end_date']);
    $now = time();
    $diff = $end - $now;
    $days_left = ceil($diff / (60 * 60 * 24));
    
    if($days_left > 5){
        $status = 'Active';
        $status_class = 'status-active';
    } elseif($days_left > 0){
        $status = 'Expiring Soon';
        $status_class = 'status-warning';
    } else {
        $status = 'Expired';
        $status_class = 'status-expired';
        $days_left = 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Dashboard | Member Portal</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --primary-dark: #4338ca;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748b;
            --slate-400: #94a3b8;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            color: var(--slate-900);
            padding-bottom: 6rem;
            -webkit-tap-highlight-color: transparent;
        }

        .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }
        .modal-xl { max-width: 90vw; }

        .container-main {
            max-width: 500px;
            margin: 0 auto;
        }

        /* Bottom Nav */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-around;
            padding: 0.8rem 0;
            z-index: 1000;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.05);
        }

        .bg-indigo { background: var(--primary) !important; }

        .nav-item {
            text-align: center;
            color: var(--slate-400);
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            flex: 1;
            position: relative;
        }

        .nav-item i {
            font-size: 1.3rem;
            display: block;
            margin-bottom: 4px;
        }

        .nav-item.active {
            color: var(--primary);
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: var(--primary);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--primary);
        }

        /* Sections */
        .page-section {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .page-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header Styles */
        .dashboard-header {
            padding: 2rem 1.5rem 4rem;
            background: linear-gradient(135deg, var(--slate-900), var(--slate-800));
            color: white;
            border-radius: 0 0 2.5rem 2.5rem;
            margin-bottom: 0;
            position: relative;
            overflow: hidden;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            z-index: 2;
        }

        .profile-img-container {
            width: 54px;
            height: 54px;
            border-radius: 1rem;
            border: 2px solid rgba(255,255,255,0.2);
            overflow: hidden;
            background: var(--slate-700);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 800;
            position: relative;
        }

        .profile-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .edit-pic-overlay {
            position: absolute;
            bottom: 0;
            right: 0;
            background: var(--primary);
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 6px;
            font-size: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid var(--slate-800);
        }

        .profile-info h1 {
            font-size: 1.25rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .profile-info p {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
            margin: 0;
            font-weight: 600;
        }

        /* Membership Card */
        .membership-hero {
            background: white;
            border-radius: 2rem;
            padding: 1.5rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.06);
            margin: -2.5rem 1.25rem 1.5rem;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255,255,255,0.8);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 0.8rem;
            border-radius: 0.75rem;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.8rem;
        }

        .status-active { background: #dcfce7; color: #166534; }
        .status-warning { background: #fef3c7; color: #92400e; }
        .status-expired { background: #fee2e2; color: #991b1b; }
        .status-inactive { background: #f1f5f9; color: #475569; }

        .plan-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 0.1rem;
        }

        .package-name {
            color: var(--slate-500);
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 1.2rem;
        }

        .countdown-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.8rem;
        }

        .stat-box {
            background: var(--slate-50);
            padding: 0.85rem;
            border-radius: 1.1rem;
            border: 1px solid var(--slate-100);
        }

        .stat-label {
            font-size: 0.6rem;
            font-weight: 800;
            color: var(--slate-400);
            text-transform: uppercase;
            margin-bottom: 0.2rem;
        }

        .stat-value {
            font-size: 1rem;
            font-weight: 800;
            color: var(--slate-900);
        }

        .stat-value span { font-size: 0.65rem; color: var(--slate-500); }

        /* Quick Actions Grid */
        .action-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            padding: 0 1.25rem;
        }

        .btn-action {
            background: white;
            border: 1px solid var(--slate-100);
            padding: 1.1rem;
            border-radius: 1.4rem;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .btn-action:active { transform: scale(0.96); background: var(--slate-50); }

        .btn-action i { font-size: 1.4rem; margin-bottom: 0.5rem; }
        .btn-action span { font-size: 0.75rem; font-weight: 700; color: var(--slate-800); }

        .btn-blue i { color: var(--primary); }
        .btn-orange i { color: #f97316; }
        .btn-green i { color: #10b981; }
        .btn-red i { color: #ef4444; }

        /* History & Lists */
        .history-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border: 1px solid var(--slate-100);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .history-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--slate-50);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .history-info { flex: 1; }
        .history-info h4 { font-size: 0.9rem; font-weight: 700; margin: 0; color: var(--slate-800); }
        .history-info p { font-size: 0.7rem; color: var(--slate-400); margin: 0; font-weight: 600; }
        .history-amount { text-align: right; }
        .history-amount .price { font-size: 0.95rem; font-weight: 800; color: var(--slate-900); display: block; }
        .history-amount .date { font-size: 0.65rem; color: var(--slate-400); font-weight: 700; }

        /* Notices */
        .notice-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border-left: 5px solid var(--primary);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        .notice-card h4 { font-size: 1rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.5rem; }
        .notice-card p { font-size: 0.85rem; color: var(--slate-600); line-height: 1.5; margin-bottom: 0.75rem; }
        .notice-date { font-size: 0.7rem; font-weight: 700; color: var(--slate-400); }

        /* BMI Progress */
        .bmi-history-table {
            width: 100%;
            background: white;
            border-radius: 1.5rem;
            overflow: hidden;
            border: 1px solid var(--slate-100);
        }

        .bmi-history-table table { width: 100%; border-collapse: collapse; }
        .bmi-history-table th { background: var(--slate-50); padding: 0.75rem 1rem; font-size: 0.65rem; font-weight: 800; color: var(--slate-400); text-transform: uppercase; text-align: left; width: 33.33%; }
        .bmi-history-table td { padding: 0.85rem 1rem; font-size: 0.85rem; font-weight: 700; border-top: 1px solid var(--slate-50); width: 33.33%; }

        /* Profile */
        .info-group {
            background: white;
            padding: 1rem 1.25rem;
            border-radius: 1.2rem;
            margin-bottom: 0.75rem;
            border: 1px solid var(--slate-100);
        }

        .info-label { font-size: 0.65rem; font-weight: 800; color: var(--slate-400); text-transform: uppercase; margin-bottom: 0.2rem; }
        .info-value { font-size: 0.95rem; font-weight: 700; color: var(--slate-800); }

        .section-title { font-size: 1rem; font-weight: 800; margin: 1.5rem 1.25rem 0.8rem; color: var(--slate-900); display: flex; align-items: center; justify-content: space-between; }
        
        /* Modal Fixes */
        .modal-content { border-radius: 2rem; border: none; }
        .modal-header { border-bottom: 1px solid var(--slate-50); padding: 1.5rem; }
        .modal-body { padding: 1.5rem; }
        .modal-footer { border-top: none; padding: 1rem 1.5rem 1.5rem; }
        .btn-primary { background: var(--primary); border: none; border-radius: 1rem; padding: 0.75rem 1.5rem; font-weight: 700; }
        .btn-secondary { background: var(--slate-100); border: none; color: var(--slate-600); border-radius: 1rem; padding: 0.75rem 1.5rem; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container-main">
        
        <!-- SECTION: HOME -->
        <div id="home-section" class="page-section active">
            <div class="dashboard-header">
                <div class="profile-section">
                    <div class="profile-img-container" onclick="<?php echo ($data && $data['profile_pic']) ? "viewer_modal('assets/uploads/".$data['profile_pic']."')" : "" ?>" style="cursor:pointer">
                        <?php if($data && $data['profile_pic']): ?>
                            <img src="assets/uploads/<?php echo $data['profile_pic'] ?>" alt="Profile">
                        <?php else: ?>
                            <span class="text-white"><?php echo substr($_SESSION['member_name'], 0, 1) ?></span>
                        <?php endif; ?>
                        <div class="edit-pic-overlay" onclick="event.stopPropagation(); $('#profile_pic_input').click();" title="Change Image">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    <form id="update-profile-pic-form" enctype="multipart/form-data" style="display:none">
                        <input type="hidden" name="id" value="<?php echo $member_id ?>">
                        <input type="file" id="profile_pic_input" name="img" onchange="updateProfilePic()" accept="image/*">
                    </form>
                    <div class="profile-info">
                        <p>Welcome Back,</p>
                        <h1><?php echo $_SESSION['member_name'] ?></h1>
                    </div>
                    <div class="ms-auto">
                        <a href="ajax.php?action=member_logout" class="text-white opacity-50"><i class="fas fa-power-off fa-lg"></i></a>
                    </div>
                </div>
            </div>

            <div class="membership-hero">
                <div class="status-badge <?php echo $status_class ?>">
                    <i class="fas fa-circle me-2" style="font-size: 0.5rem;"></i>
                    <?php echo $status ?>
                </div>
                
                <?php if($data && isset($data['plan'])): ?>
                    <div class="plan-title"><?php echo $data['plan'] ?> Months Plan</div>
                    <div class="package-name"><?php echo $data['package'] ?> Package</div>
                    
                    <div class="countdown-grid">
                        <div class="stat-box">
                            <div class="stat-label">Days Left</div>
                            <div class="stat-value"><?php echo $days_left ?> <span>days</span></div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Expiry Date</div>
                            <div class="stat-value" style="font-size: 0.85rem;"><?php echo date('d M, Y', strtotime($data['end_date'])) ?></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="plan-title">No Active Plan</div>
                    <p class="text-slate-500 fw-600 mt-2">Visit us to activate your plan!</p>
                <?php endif; ?>
            </div>

            <div class="section-title">Quick Actions</div>
            <div class="action-grid">
                <a href="javascript:void(0)" class="btn-action btn-blue" id="view_receipt_home">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Receipt</span>
                </a>
                <a href="javascript:void(0)" class="btn-action btn-green" id="log_bmi">
                    <i class="fas fa-weight-scale"></i>
                    <span>Log Weight</span>
                </a>
                <a href="https://wa.me/919909568777" target="_blank" class="btn-action btn-red">
                    <i class="fas fa-headset"></i>
                    <span>Support</span>
                </a>
            </div>
            
            <div class="section-title">Your Progress</div>
            <div class="px-3">
                <div class="bmi-history-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Weight</th>
                                <th>BMI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $bmis = $conn->query("SELECT * FROM member_bmi_logs WHERE member_id = $member_id ORDER BY date_created DESC LIMIT 3");
                            if($bmis->num_rows > 0):
                                while($row = $bmis->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo date('d M', strtotime($row['date_created'])) ?></td>
                                <td><?php echo $row['weight'] ?> kg</td>
                                <td class="fw-800 text-primary"><?php echo $row['bmi'] ? $row['bmi'] : '--' ?></td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr><td colspan="3" class="text-center text-slate-400 py-4">No records found</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SECTION: HISTORY -->
        <div id="history-section" class="page-section p-3">
            <h2 class="fw-800 mb-4 mt-3">Payment History</h2>
            <?php 
            $history = $conn->query("SELECT r.*, p.plan, pp.package, pay.amount, pay.date_created as pay_date 
                                     FROM registration_info r 
                                     INNER JOIN plans p ON p.id = r.plan_id 
                                     INNER JOIN packages pp ON pp.id = r.package_id 
                                     LEFT JOIN payments pay ON pay.registration_id = r.id
                                     WHERE r.member_id = $member_id 
                                     ORDER BY r.id DESC");
            if($history->num_rows > 0):
                while($h = $history->fetch_assoc()):
            ?>
            <div class="history-card">
                <div class="history-icon"><i class="fas fa-receipt"></i></div>
                <div class="history-info">
                    <h4><?php echo $h['plan'] ?> Months Plan</h4>
                    <p><?php echo $h['package'] ?></p>
                </div>
                <div class="history-amount text-end">
                    <span class="price">₹<?php echo number_format((float)($h['amount'] ?? 0), 2) ?></span>
                    <?php 
                        $display_date = $h['pay_date'] ?? $h['date_created'] ?? date('Y-m-d');
                    ?>
                    <span class="date"><?php echo date('d M, Y', strtotime($display_date)) ?></span>
                </div>
            </div>
            <?php endwhile; else: ?>
            <div class="text-center py-5">
                <i class="fas fa-history fa-3x text-slate-100 mb-3"></i>
                <p class="text-slate-400 fw-600">No payment history available.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- SECTION: WORKOUT -->
        <div id="workout-section" class="page-section p-3">
            <h2 class="fw-800 mb-4 mt-3">Workout Plan</h2>
            
            <?php 
            // Get latest ASSIGNED workout
            $assigned = $conn->query("SELECT * FROM member_workouts WHERE member_id = $member_id AND status = 1 ORDER BY date_assigned DESC LIMIT 1");
            $aw = $assigned->fetch_assoc();

            // Check if there is a PENDING request
            $pending = $conn->query("SELECT * FROM member_workouts WHERE member_id = $member_id AND status = 0 LIMIT 1");
            $pw = $pending->fetch_assoc();
            ?>

            <?php if($pw): ?>
                <div class="notice-card mb-4" style="border-left-color: #f97316; background: #fffcf9;">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-clock text-orange me-2"></i>
                        <h4 class="mb-0" style="font-size: 0.9rem;">New Request Pending</h4>
                    </div>
                    <p class="small mb-2">You requested a new plan on <?php echo date('d M', strtotime($pw['date_requested'])) ?>. Our trainers are working on it!</p>
                </div>
            <?php endif; ?>

            <?php if(!$aw): ?>
                <?php if(!$pw): ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-dumbbell fa-4x text-slate-100"></i>
                    </div>
                    <h3 class="fw-800 text-slate-800 mb-2">No Plan Requested</h3>
                    <p class="text-slate-500 mb-4">Request a personalized workout plan from our trainers!</p>
                    <button class="btn btn-primary rounded-pill px-5 py-3 fw-800 shadow-sm" id="btn-request-workout">
                        <i class="fas fa-plus me-2"></i> Request Workout
                    </button>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-indigo text-white p-3 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-800" style="font-size: 0.95rem;"><i class="fas fa-dumbbell me-2"></i>Current Workout Plan</h5>
                        <span class="small fw-700 opacity-75">Assigned <?php echo date('d M', strtotime($aw['date_assigned'])) ?></span>
                    </div>
                    <div class="card-body p-4 text-center">
                        <?php 
                        $ext = strtolower(pathinfo($aw['file_path'], PATHINFO_EXTENSION));
                        if(in_array($ext, ['jpg','jpeg','png','gif'])):
                        ?>
                            <img src="assets/uploads/<?php echo $aw['file_path'] ?>" class="img-fluid rounded-4 shadow-sm mb-4" onclick="viewer_modal('assets/uploads/<?php echo $aw['file_path'] ?>')">
                        <?php elseif($ext == 'pdf'): ?>
                            <div class="workout-pdf-viewer mb-4">
                                <div class="d-flex align-items-center justify-content-between p-3 bg-slate-50 border border-bottom-0 rounded-top-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-danger-light text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas fa-file-pdf small"></i>
                                        </div>
                                        <span class="fw-800 text-slate-700 small text-truncate" style="max-width: 120px;">Workout_Plan.pdf</span>
                                    </div>
                                    <div class="d-flex gap-1 gap-md-2">
                                        <button class="btn btn-white btn-sm border shadow-xs rounded-pill px-2 px-md-3 fw-700 text-slate-600" onclick="viewer_modal('assets/uploads/<?php echo $aw['file_path'] ?>', 'pdf')">
                                            <i class="fas fa-expand me-md-1"></i> <span class="d-none d-sm-inline">Full Screen</span>
                                        </button>
                                        <a href="assets/uploads/<?php echo $aw['file_path'] ?>" target="_blank" class="btn btn-white btn-sm border shadow-xs rounded-pill px-2 px-md-3 fw-700 text-slate-600">
                                            <i class="fas fa-external-link-alt me-md-1"></i> <span class="d-none d-sm-inline">New Tab</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="pdf-container rounded-bottom-4 overflow-hidden border border-slate-100 shadow-sm pdf-viewer-responsive">
                                    <iframe src="assets/uploads/<?php echo $aw['file_path'] ?>#toolbar=0&navpanes=0&scrollbar=0" width="100%" height="100%" frameborder="0"></iframe>
                                </div>
                            </div>
                            <style>
                                .pdf-viewer-responsive { height: 450px; }
                                @media (max-width: 768px) { .pdf-viewer-responsive { height: 350px; } }
                            </style>
                        <?php endif; ?>

                        <div class="d-grid gap-2">
                            <a href="assets/uploads/<?php echo $aw['file_path'] ?>" target="_blank" class="btn btn-light rounded-pill py-3 fw-700">
                                <i class="fas fa-download me-2"></i> Download Plan
                            </a>
                            <?php if(!$pw): ?>
                            <button class="btn btn-primary rounded-pill py-3 fw-700" id="btn-request-workout">
                                <i class="fas fa-redo me-2"></i> Request New Plan
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- HISTORY OF WORKOUTS -->
            <?php 
            $all_workouts = $conn->query("SELECT * FROM member_workouts WHERE member_id = $member_id AND status = 1 ORDER BY date_assigned DESC");
            if($all_workouts->num_rows > 1):
            ?>
            <div class="mt-5">
                <h3 class="fw-800 text-slate-800 mb-3" style="font-size: 1.1rem;">Past Workout Plans</h3>
                <?php 
                $skip = true;
                while($row = $all_workouts->fetch_assoc()):
                    if($skip){ $skip = false; continue; } // Skip current
                ?>
                <div class="history-card">
                    <div class="history-icon" style="background: var(--slate-100); color: var(--slate-400);"><i class="fas fa-history"></i></div>
                    <div class="history-info">
                        <h4>Workout Plan</h4>
                        <p>Assigned on <?php echo date('d M, Y', strtotime($row['date_assigned'])) ?></p>
                    </div>
                    <div class="history-amount text-end">
                        <button type="button" onclick="view_past_workout('assets/uploads/<?php echo $row['file_path'] ?>')" class="btn btn-sm btn-light rounded-pill px-3 fw-700">View</button>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- SECTION: NOTICES -->
        <div id="notices-section" class="page-section p-3">
            <h2 class="fw-800 mb-4 mt-3">Gym Notices</h2>
            <?php 
            $notices = $conn->query("SELECT * FROM gym_notices ORDER BY id DESC");
            if($notices->num_rows > 0):
                while($n = $notices->fetch_assoc()):
            ?>
            <div class="notice-card" style="border-left-color: <?php echo $n['border_color'] ?>;">
                <h4><?php echo $n['title'] ?></h4>
                <p><?php echo $n['content'] ?></p>
                <div class="notice-date"><?php echo date('d M, Y', strtotime($n['date_created'])) ?></div>
            </div>
            <?php endwhile; else: ?>
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-slate-100 mb-3"></i>
                <p class="text-slate-400 fw-600">No active notices.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- SECTION: PROFILE -->
        <div id="profile-section" class="page-section p-3">
            <h2 class="fw-800 mb-4 mt-3">My Profile</h2>
            <div class="info-group">
                <div class="info-label">Full Name</div>
                <div class="info-value"><?php echo $_SESSION['member_name'] ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">Member ID</div>
                <div class="info-value">#<?php echo $_SESSION['member_mid'] ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">Mobile Number</div>
                <div class="info-value"><?php echo $data['contact'] ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">Email Address</div>
                <div class="info-value"><?php echo $data['email'] ? $data['email'] : 'Not Provided' ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">Address</div>
                <div class="info-value"><?php echo $data['address'] ? $data['address'] : 'Not Provided' ?></div>
            </div>
            
            <a href="ajax.php?action=member_logout" class="btn btn-danger w-100 rounded-pill py-3 fw-700 mt-4 shadow-sm">
                <i class="fas fa-sign-out-alt me-2"></i> Log Out
            </a>
        </div>

    </div>

    <!-- Bottom Nav -->
    <div class="bottom-nav">
        <a href="javascript:void(0)" class="nav-item active" data-section="home">
            <i class="fas fa-home"></i>
            Home
        </a>
        <a href="javascript:void(0)" class="nav-item" data-section="history">
            <i class="fas fa-receipt"></i>
            History
        </a>
        <a href="javascript:void(0)" class="nav-item" data-section="notices">
            <i class="fas fa-bell"></i>
            Notices
        </a>
        <a href="javascript:void(0)" class="nav-item" data-section="workout">
            <i class="fas fa-dumbbell"></i>
            Workout
        </a>
        <a href="javascript:void(0)" class="nav-item" data-section="profile">
            <i class="fas fa-user"></i>
            Profile
        </a>
    </div>

    <!-- Global Modal -->
    <div class="modal fade" id="uni_modal" role='dialog'>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-800 text-slate-800"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-primary px-4 shadow-sm" id='submit' onclick="$('#uni_modal form').submit()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewer_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content bg-transparent border-0">
                <button type="button" class="btn-close ms-auto p-3 btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-0 text-center">
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Section Switching
        $('.nav-item').click(function(){
            var section = $(this).attr('data-section')
            showSection(section)
            location.hash = section // Persist in URL
        })

        function showSection(section){
            $('.nav-item').removeClass('active')
            $('.nav-item[data-section="'+section+'"]').addClass('active')
            $('.page-section').removeClass('active')
            $('#' + section + '-section').addClass('active')
            window.scrollTo(0,0)
        }

        window.view_past_workout = function(url){
            viewer_modal(url)
        }

        window.viewer_modal = function($src = ''){
            var t = $src.split('.').pop().toLowerCase();
            var view;
            
            if(t =='mp4'){
                view = $("<video src='"+$src+"' controls autocomplete='off' width='100%' height='440px'></video>")
            } else if(t == 'pdf') {
                view = $("<div class='rounded-4 overflow-hidden shadow-sm border' style='height:75vh; width:100%'><iframe src='"+$src+"' width='100%' height='100%' frameborder='0'></iframe></div>")
            } else {
                view = $("<img src='"+$src+"' class='img-fluid rounded-4' />")
            }
            
            $('#viewer_modal .modal-body').html(view)
            
            // Adjust modal size for PDF
            if(t == 'pdf'){
                $('#viewer_modal .modal-dialog').addClass('modal-lg modal-xl')
            } else {
                $('#viewer_modal .modal-dialog').removeClass('modal-lg modal-xl')
            }
            
            $('#viewer_modal').modal('show')
        }

        // Handle Refresh / Direct Link
        $(document).ready(function(){
            var hash = location.hash.replace('#', '')
            if(hash && $('#' + hash + '-section').length > 0){
                showSection(hash)
            }
        })

        window.uni_modal = function($title = '' , $url='', $size="", $footer = true){
            $.ajax({
                url:$url,
                error:err=>{ console.log(err); alert("An error occured") },
                success:function(resp){
                    if(resp){
                        $('#uni_modal .modal-title').html($title)
                        $('#uni_modal .modal-body').html(resp)
                        if($size != ''){
                            $('#uni_modal .modal-dialog').addClass($size)
                        }else{
                            $('#uni_modal .modal-dialog').removeClass('modal-a4')
                        }
                        
                        if($footer){
                            $('#uni_modal .modal-footer').show()
                        }else{
                            $('#uni_modal .modal-footer').hide()
                        }
                        
                        $('#uni_modal').modal('show')
                    }
                }
            })
        }

        $('#view_receipt_home').click(function(){
            uni_modal("<i class='fas fa-receipt me-2'></i>Membership Receipt","view_member.php?id=<?php echo $data['member_id'] ?>",'modal-a4', false)
        })

        $('#log_bmi').click(function(){
            uni_modal("<i class='fas fa-weight-scale me-2'></i>Log Progress","manage_bmi.php")
        })

        window.start_load = function(){
            $('#uni_modal button[type="submit"]').attr('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...')
        }

        window.alert_toast = function($msg = 'Success', $icon = 'success'){
            Swal.fire({
                title: $msg,
                icon: $icon,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            })
        }

        function updateProfilePic(){
            var fileInput = document.getElementById('profile_pic_input');
            var file = fileInput.files[0];
            
            if (!file) return;

            // 1. Validation: Is it an image?
            if (!file.type.match('image.*')) {
                alert_toast("Please select a valid image file", "error");
                fileInput.value = ''; // Reset input
                return;
            }

            // 2. Validation: Size less than 5MB (5 * 1024 * 1024 bytes)
            if (file.size > 5 * 1024 * 1024) {
                alert_toast("Image size must be less than 5MB", "error");
                fileInput.value = ''; // Reset input
                return;
            }

            var formData = new FormData($('#update-profile-pic-form')[0]);
            $.ajax({
                url: 'ajax.php?action=update_member_profile',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function(resp){
                    if(resp == 1){
                        alert_toast("Profile picture updated successfully","success")
                        setTimeout(function(){ location.reload() }, 1500)
                    } else {
                        alert_toast("An error occurred during upload","error")
                    }
                }
            })
        }

        $(document).on('click', '#btn-request-workout', function(){
            $(this).attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending Request...')
            $.ajax({
                url: 'ajax.php?action=request_workout',
                method: 'POST',
                data: {member_id: <?php echo $member_id ?>},
                success: function(resp){
                    if(resp == 1){
                        alert_toast("Request sent successfully","success")
                        setTimeout(function(){ location.reload() }, 1500)
                    }else if(resp == 2){
                        alert_toast("You already have a pending request","info")
                        location.reload()
                    }
                }
            })
        })


    </script>
</body>
</html>
