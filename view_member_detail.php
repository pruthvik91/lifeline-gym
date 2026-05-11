<?php
include 'db_connect.php';
session_start();

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT *,concat(firstname,' ',lastname,' ',middlename) as name FROM members where id=" . $_GET['id'])->fetch_array();
    foreach ($qry as $k => $v) {
        $$k = $v;
    }
}
?>

<style>
    #member-profile { padding: 5px; }
    
    .profile-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        border: 1px solid #e2e8f0;
        height: 100%;
    }

    .profile-img-container {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 16px;
    }

    .profile-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 24px;
        border: 3px solid white;
        box-shadow: var(--shadow-soft);
    }

    .status-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 16px;
        height: 16px;
        background: #22c55e;
        border: 3px solid white;
        border-radius: 50%;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
        margin-top: 20px;
    }

    .info-item {
        background: white;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        text-align: left;
    }

    .info-label {
        font-size: 0.6rem;
        text-transform: uppercase;
        font-weight: 800;
        color: #94a3b8;
        margin-bottom: 2px;
        display: block;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 0.85rem;
        font-weight: 700;
        color: #1e293b;
        word-break: break-word;
    }

    .membership-card {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border-radius: 16px;
        padding: 20px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
    }

    .membership-card::after {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 120px;
        height: 120px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .plan-badge {
        background: rgba(255,255,255,0.2);
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 700;
        backdrop-filter: blur(4px);
        display: inline-block;
    }
    
    .quick-stat-box {
        padding: 12px;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        background: #f8fafc;
        text-align: center;
        flex: 1;
    }
</style>

<div class="container-fluid" id="member-profile">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="profile-card">
                <div class="profile-img-container">
                    <?php if (!empty($profile_pic)): ?>
                        <img src="assets/uploads/<?php echo $profile_pic ?>" class="profile-img">
                    <?php else: ?>
                        <div class="profile-img d-flex align-items-center justify-content-center bg-primary text-white fs-1 fw-800">
                            <?php echo strtoupper(substr($name, 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div class="status-dot"></div>
                </div>
                
                <h3 class="fw-800 text-slate-900 mb-1"><?php echo ucwords($name) ?></h3>
                <p class="text-slate-400 fw-600 mb-0">Member ID: #<?php echo $member_id ?></p>
                
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Contact</span>
                        <span class="info-value"><?php echo $contact ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Gender</span>
                        <span class="info-value"><?php echo ucwords($gender) ?></span>
                    </div>
                    <div class="info-item" style="grid-column: span 2;">
                        <span class="info-label">Address</span>
                        <span class="info-value"><?php echo $address ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <h6 class="fw-800 text-slate-900 mb-3 px-2">Active Membership</h6>
            <?php
            $paid = $conn->query("SELECT r.*,pl.plan,pa.package FROM registration_info r inner join plans pl on pl.id = r.plan_id inner join packages pa on pa.id = r.package_id where r.member_id = $id order by id desc limit 1");
            if($row = $paid->fetch_assoc()):
                $days_remaining = ceil((strtotime($row['end_date']) - time()) / (60 * 60 * 24));
            ?>
                <div class="membership-card">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <span class="plan-badge"><?php echo $row['plan'] ?> Months Plan</span>
                            <h2 class="mt-2 fw-800 mb-0"><?php echo $row['package'] ?></h2>
                        </div>
                        <i class="fas fa-crown fs-1 opacity-25"></i>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="small opacity-75 fw-600">Start Date</div>
                            <div class="fw-700"><?php echo date("d M, Y", strtotime($row['start_date'])) ?></div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="small opacity-75 fw-600">Expiry Date</div>
                            <div class="fw-700"><?php echo date("d M, Y", strtotime($row['end_date'])) ?></div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-hourglass-half"></i>
                            <span class="fw-700"><?php echo max(0, $days_remaining) ?> Days Remaining</span>
                        </div>
                        <?php if($days_remaining > 0): ?>
                            <span class="badge bg-white text-primary rounded-pill px-3">Active</span>
                        <?php else: ?>
                            <span class="badge bg-danger text-white rounded-pill px-3">Expired</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mt-5 px-2">
                    <h6 class="fw-800 text-slate-900 mb-3 small text-uppercase">System Records</h6>
                    <div class="d-flex gap-3">
                        <div class="quick-stat-box">
                            <div class="text-slate-400 extra-small fw-800 mb-1">TRAINING BATCH</div>
                            <div class="text-slate-900 fw-800"><?php echo $batch ?></div>
                        </div>
                        <div class="quick-stat-box">
                            <div class="text-slate-400 extra-small fw-800 mb-1">MEMBER SINCE</div>
                            <div class="text-slate-900 fw-800"><?php echo date("Y", strtotime($row['start_date'])) ?></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="p-5 text-center bg-slate-50 rounded-4 border border-dashed border-slate-300">
                    <i class="fas fa-user-slash fs-1 text-slate-300 mb-3"></i>
                    <p class="text-slate-500 fw-600">No active membership found for this member.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
