<style>
	.logo {
    margin: auto;
    font-size: 20px;
    padding: 7px 11px;
    border-radius: 50% 50%;
    color: #000000b3;
}

.navbar-brand {
    font-weight: 700;
    font-size: 1.5rem;
    color: white !important;
    text-decoration: none;
}

.navbar-brand:hover {
    color: rgba(255, 255, 255, 0.8) !important;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.5rem;
    font-weight: 600;
    color: white;
}
</style>

<nav class="navbar navbar-expand-lg navbar-light fixed-top">
  <div class="container-fluid">
    <div class="d-flex align-items-center">
      <a class="navbar-brand" href="index.php?page=home">
        <i class="fas fa-dumbbell me-2"></i>
        Lifeline Fitness
      </a>
    </div>
    
    <div class="d-flex align-items-center">
      <div class="dropdown">
        <a href="#" class="dropdown-toggle d-flex align-items-center text-white text-decoration-none" id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <div class="user-avatar">
            <?php echo strtoupper(substr($_SESSION['login_name'], 0, 1)); ?>
          </div>
          <span class="me-2"><?php echo $_SESSION['login_name'] ?></span>
          <i class="fas fa-chevron-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="account_settings">
          <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account">
            <i class="fas fa-user-cog me-2"></i> Manage Account
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="ajax.php?action=logout">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>

<script>
  $('#manage_my_account').click(function(){
    uni_modal("Manage Account","manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own")
  })
</script>