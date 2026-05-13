<?php 
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon-modern">L</div>
        <span class="brand-text-modern">Lifeline</span>
    </div>

    <div class="nav-section-title">Main</div>
    <a href="admin-home" class="nav-link-modern <?php echo $page == 'home' ? 'active' : '' ?>">
        <i class="fas fa-th-large"></i> Dashboard
    </a>
    
    <div class="nav-section-title">Management</div>
    <a href="admin-members" class="nav-link-modern <?php echo $page == 'members' ? 'active' : '' ?>">
        <i class="fas fa-users"></i> Members
    </a>
    <a href="admin-registered_members" class="nav-link-modern <?php echo $page == 'registered_members' ? 'active' : '' ?>">
        <i class="fas fa-calendar-check"></i> Validity
    </a>
    <a href="admin-notices" class="nav-link-modern <?php echo $page == 'notices' ? 'active' : '' ?>">
        <i class="fas fa-bullhorn"></i> Notices
    </a>
    <a href="admin-workout_requests" class="nav-link-modern <?php echo $page == 'workout_requests' ? 'active' : '' ?>">
        <i class="fas fa-dumbbell"></i> Workout Plans
    </a>

    <?php if($_SESSION['login_type'] == 1): ?>
    <div class="nav-section-title">Gym Services</div>
    <a href="admin-plans" class="nav-link-modern <?php echo $page == 'plans' ? 'active' : '' ?>">
        <i class="fas fa-layer-group"></i> Plans
    </a>
    <a href="admin-packages" class="nav-link-modern <?php echo $page == 'packages' ? 'active' : '' ?>">
        <i class="fas fa-box-open"></i> Packages
    </a>
    
    <div class="nav-section-title">Financials</div>
    <a href="admin-income_expense" class="nav-link-modern <?php echo $page == 'income_expense' ? 'active' : '' ?>">
        <i class="fas fa-wallet"></i> Income
    </a>
    <a href="admin-cardlist" class="nav-link-modern <?php echo $page == 'cardlist' ? 'active' : '' ?>">
        <i class="fas fa-id-card"></i> ID Cards
    </a>
    
    <div class="nav-section-title">System</div>
    <a href="admin-users" class="nav-link-modern <?php echo $page == 'users' ? 'active' : '' ?>">
        <i class="fas fa-user-shield"></i> Staff
    </a>
    <a href="admin-login_logs" class="nav-link-modern <?php echo $page == 'login_logs' ? 'active' : '' ?>">
        <i class="fas fa-history"></i> Login Logs
    </a>
    <a href="admin-whatsapp-setting" class="nav-link-modern <?php echo $page == 'whatsapp-setting' ? 'active' : '' ?>">
        <i class="fab fa-whatsapp"></i> WhatsApp
    </a>
    <a href="ajax.php?action=logout" class="nav-link-modern logout-link mt-4">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
    <?php endif; ?>
</nav>