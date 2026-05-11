<?php include 'db_connect.php' ?>

<div class="container-fluid py-4">
    <!-- Sophisticated Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-5 gap-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="bg-primary rounded-circle" style="width: 6px; height: 6px;"></div>
                <span class="text-slate-400 fw-800 extra-small text-uppercase tracking-wider">Lifeline Management System</span>
            </div>
            <h1 class="fw-900 text-slate-900 mb-0 display-6" style="letter-spacing: -1.5px;">
                Good Morning, <?php echo explode(' ', $_SESSION['login_name'])[0] ?>.
            </h1>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end">
                <div class="small fw-800 text-slate-900"><?php echo date('l, d M') ?></div>
                <div class="extra-small fw-600 text-slate-400">System Pulse Active</div>
            </div>
            <div class="bg-white p-3 rounded-circle border border-slate-100 shadow-sm d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <div class="status-dot-pulse"></div>
            </div>
        </div>
    </div>

    <!-- Minimalist Stats Row -->
    <div class="row g-4 mb-5">
        <div class="col-lg-4">
            <div class="bg-white rounded-4 p-4 shadow-premium border border-slate-50 h-100">
                <div class="d-flex align-items-start justify-content-between mb-4">
                    <div class="stat-icon-square-minimal bg-slate-50 text-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="text-success extra-small fw-800 bg-success-light px-2 py-1 rounded-pill">+2.4%</span>
                </div>
                <h6 class="text-slate-400 fw-700 extra-small text-uppercase mb-2">Active Members</h6>
                <div class="h2 fw-900 text-slate-900 mb-0">
                    <?php echo $conn->query("SELECT id FROM registration_info where status = 1")->num_rows; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="bg-white rounded-4 p-4 shadow-premium border border-slate-50 h-100 border-top border-4 border-warning">
                <div class="d-flex align-items-start justify-content-between mb-4">
                    <div class="stat-icon-square-minimal bg-slate-50 text-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <h6 class="text-slate-400 fw-700 extra-small text-uppercase mb-2">Expiring Soon</h6>
                <div class="h2 fw-900 text-slate-900 mb-0">
                    <?php 
                        $seven_days = date('Y-m-d', strtotime('+7 days'));
                        echo $conn->query("SELECT id FROM registration_info where status = 1 AND end_date <= '$seven_days'")->num_rows; 
                    ?>
                </div>
                <div class="extra-small text-slate-400 fw-600 mt-2">Next 7 days period</div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="bg-white rounded-4 p-4 shadow-premium border border-slate-50 h-100">
                <div class="d-flex align-items-start justify-content-between mb-4">
                    <div class="stat-icon-square-minimal bg-slate-50 text-success">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <h6 class="text-slate-400 fw-700 extra-small text-uppercase mb-2">Session Traffic</h6>
                <div class="d-flex align-items-end gap-3">
                    <div class="h2 fw-900 text-slate-900 mb-0">
                         <?php echo $conn->query("SELECT id FROM members")->num_rows; ?>
                    </div>
                    <div class="extra-small fw-800 text-slate-400 mb-2">RECORDS</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Intelligence Section -->
    <div class="row g-5">
        <!-- Minimalist Feed -->
        <div class="col-lg-6">
            <div class="h-100">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="fw-900 text-slate-900 mb-0">Recent Activity</h5>
                    <a href="index.php?page=members" class="text-primary fw-800 extra-small text-decoration-none">VIEW DIRECTORY</a>
                </div>
                <div class="bg-white rounded-4 border border-slate-100 shadow-sm overflow-hidden">
                    <div class="list-group list-group-flush">
                        <?php 
                        $recent = $conn->query("SELECT r.*, m.firstname, m.lastname, p.plan, pk.package FROM registration_info r INNER JOIN members m ON m.id = r.member_id INNER JOIN plans p ON p.id = r.plan_id INNER JOIN packages pk ON pk.id = r.package_id ORDER BY r.date_created DESC LIMIT 5");
                        while($row = $recent->fetch_assoc()):
                        ?>
                        <div class="list-group-item p-4 border-slate-50">
                            <div class="d-flex align-items-center justify-content-between no-gap">
                                <div class="d-flex align-items-center gap-3 no-gap">
                                    <div class="activity-avatar-minimal">
                                        <?php echo substr($row['firstname'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <div class="fw-800 text-slate-900 small"><?php echo $row['firstname'].' '.$row['lastname'] ?></div>
                                        <div class="extra-small text-slate-400 fw-600 mt-1">Joined <?php echo $row['package'] ?></div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="extra-small fw-800 text-slate-900"><?php echo date('d M', strtotime($row['date_created'])) ?></div>
                                    <div class="extra-small text-slate-400 fw-600 mt-1"><?php echo $row['plan'] ?> Months</div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Intelligence -->
        <div class="col-lg-6">
            <div class="h-100">
                <h5 class="fw-900 text-slate-900 mb-4">System Insights</h5>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="bg-white rounded-4 p-4 border border-slate-100 shadow-sm d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-800 text-slate-900 mb-1">Morning Productivity</h6>
                                <p class="text-slate-400 extra-small fw-600 mb-0">Live tracking of morning sessions</p>
                            </div>
                            <div class="text-end">
                                <div class="h3 fw-900 text-primary mb-0">
                                    <?php echo $conn->query("SELECT id FROM members where batch = 'Morning'")->num_rows; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-white rounded-4 p-4 border border-slate-100 shadow-sm d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-800 text-slate-900 mb-1">Evening Performance</h6>
                                <p class="text-slate-400 extra-small fw-600 mb-0">Evening session load monitoring</p>
                            </div>
                            <div class="text-end">
                                <div class="h3 fw-900 text-primary mb-0">
                                    <?php echo $conn->query("SELECT id FROM members where batch = 'Evening'")->num_rows; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="cta-banner-minimal rounded-4 p-4 d-flex align-items-center justify-content-between">
                            <div class="text-white">
                                <h6 class="fw-800 mb-1">Accelerate Growth</h6>
                                <p class="extra-small fw-600 opacity-75 mb-0">Ready to onboard a new premium member?</p>
                            </div>
                            <button class="btn btn-white-minimal fw-800 extra-small px-4 shadow-sm" onclick="location.href='index.php?page=members'">
                                ADD NEW MEMBER
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tracking-wider { letter-spacing: 0.1em; }
    
    .stat-icon-square-minimal {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
    }
    
    .activity-avatar-minimal {
        width: 38px; height: 38px; border-radius: 50%;
        background: var(--slate-50); color: var(--primary);
        display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;
    }
    
    .status-dot-pulse {
        width: 10px; height: 10px; background: #22c55e; border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
        animation: pulse-green 2s infinite;
    }
    
    .cta-banner-minimal {
        background: var(--slate-900);
    }
    
    .btn-white-minimal {
        background: white; color: var(--slate-900); border: none;
        transition: all 0.2s ease;
    }
    .btn-white-minimal:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }
    
    .list-group-item:last-child { border-bottom: none !important; }
</style>
