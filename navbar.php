<?php 
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon-modern">L</div>
        <span class="brand-text-modern">Lifeline</span>
    </div>

    <div class="nav-section-title">Main</div>
    <a href="index.php?page=home" class="nav-link-modern <?php echo $page == 'home' ? 'active' : '' ?>">
        <i class="fas fa-th-large"></i> Dashboard
    </a>
    
    <div class="nav-section-title">Management</div>
    <a href="index.php?page=members" class="nav-link-modern <?php echo $page == 'members' ? 'active' : '' ?>">
        <i class="fas fa-users"></i> Members
    </a>
    <a href="index.php?page=registered_members" class="nav-link-modern <?php echo $page == 'registered_members' ? 'active' : '' ?>">
        <i class="fas fa-calendar-check"></i> Validity
    </a>

    <?php if($_SESSION['login_type'] == 1): ?>
    <div class="nav-section-title">Gym Services</div>
    <a href="index.php?page=plans" class="nav-link-modern <?php echo $page == 'plans' ? 'active' : '' ?>">
        <i class="fas fa-layer-group"></i> Plans
    </a>
    <a href="index.php?page=packages" class="nav-link-modern <?php echo $page == 'packages' ? 'active' : '' ?>">
        <i class="fas fa-box-open"></i> Packages
    </a>
    
    <div class="nav-section-title">Financials</div>
    <a href="index.php?page=income_expense" class="nav-link-modern <?php echo $page == 'income_expense' ? 'active' : '' ?>">
        <i class="fas fa-wallet"></i> Income
    </a>
    <a href="index.php?page=cardlist" class="nav-link-modern <?php echo $page == 'cardlist' ? 'active' : '' ?>">
        <i class="fas fa-id-card"></i> ID Cards
    </a>
    
    <div class="nav-section-title">System</div>
    <a href="index.php?page=users" class="nav-link-modern <?php echo $page == 'users' ? 'active' : '' ?>">
        <i class="fas fa-user-shield"></i> Staff
    </a>
    <a href="index.php?page=whatsapp-setting" class="nav-link-modern <?php echo $page == 'whatsapp-setting' ? 'active' : '' ?>">
        <i class="fab fa-whatsapp"></i> WhatsApp
    </a>
    <a href="ajax.php?action=logout" class="nav-link-modern logout-link mt-4">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
    <?php endif; ?>
</nav>