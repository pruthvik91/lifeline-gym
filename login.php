<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('./db_connect.php');
ob_start();
if (!isset($_SESSION['system'])) {
    // $system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
    // foreach($system as $k => $v){
    //     $_SESSION['system'][$k] = $v;
    // }
}
ob_end_flush();
?>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>LifeLine Fitness</title>
	
    <?php include('./header.php'); ?>
    <?php
    if (isset($_SESSION['login_id']))
	header("location:index.php?page=home");
?>
	<link rel="stylesheet" href="./login.css">
</head>

<body class="login-body">
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="login-logo">
          <i class="fas fa-dumbbell"></i>
        </div>
        <h2 class="login-title">Lifeline Fitness</h2>
        <p class="login-subtitle">Welcome back! Please sign in to your account.</p>
      </div>
      
      <form id="login-form" class="login-form">
        <div class="form-group">
          <div class="input-group">
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="username" class="form-control" placeholder="Username" required>
          </div>
        </div>
        
        <div class="form-group">
          <div class="input-group">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
          </div>
        </div>
        
        <button type="submit" class="login-btn">
          <span class="btn-text">Sign In</span>
          <i class="fas fa-arrow-right btn-icon"></i>
        </button>
      </form>
    </div>
  </div>

	<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

</body>
<script>
	$('#login-form').submit(function(e) {
		e.preventDefault()
		const $btn = $(this).find('button[type="submit"]');
		const $btnText = $btn.find('.btn-text');
		const $btnIcon = $btn.find('.btn-icon');
		
		// Set loading state
		$btn.addClass('loading').attr('disabled', true);
		$btnText.text('Signing in...');
		$btnIcon.removeClass('fa-arrow-right').addClass('fa-spinner fa-spin');
		
		if ($(this).find('.alert-danger').length > 0)
			$(this).find('.alert-danger').remove();
			
		$.ajax({
			url: 'ajax.php?action=login',
			method: 'POST',
			data: $(this).serialize(),
			error: err => {
				console.log(err)
				// Reset button state
				$btn.removeClass('loading').removeAttr('disabled');
				$btnText.text('Sign In');
				$btnIcon.removeClass('fa-spinner fa-spin').addClass('fa-arrow-right');
			},
			success: function(resp) {
				if (resp == 1) {
					// Success - redirect
					$btnText.text('Success!');
					$btnIcon.removeClass('fa-spinner fa-spin').addClass('fa-check');
					setTimeout(() => {
						location.href = 'index.php?page=home';
					}, 500);
				} else {
					// Error - reset button and show error
					$btn.removeClass('loading').removeAttr('disabled');
					$btnText.text('Sign In');
					$btnIcon.removeClass('fa-spinner fa-spin').addClass('fa-arrow-right');
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
				}
			}
		})
	})
</script>

</html>
