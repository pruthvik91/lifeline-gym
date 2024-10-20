<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('./db_connect.php');
ob_start();
if (!isset($_SESSION['system'])) {
	// $system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
	// foreach($system as $k => $v){
	// 	$_SESSION['system'][$k] = $v;
	// }
}
ob_end_flush();
?>

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>LifeLine Fitness</title>
<link rel="stylesheet" href="./assets/css/login.css">

	<?php include('./header.php'); ?>
	<?php
	if (isset($_SESSION['login_id']))
		header("location:index.php?page=home");

	?>
	
</head>


<body style="
    width: 341px;
    height: 500px;
    margin-left: 600px;
	margin-top: 180px;
">


<div class="container" style="
    MARGIN-LEFT: -504PX;
    MARGIN-TOP: 469PX;
">
    <div class="form">
      <h3 style="
    COLOR: YELLOW;
"class="title">Lifeline Fitness</h3>
      <form id="login-form">
        <div class="form-group">
          <input type="text" name="username" required/><label>Username</label>
        </div>
        <div class="form-group">
          <input type="password" name="password" id="password" required/><label>Password</label>
        </div>
        <input STYLE="COLOR:BLACK;" type="submit" value="submit" class="submit"> 
        
      </form>
    </div>
    
  </div>

	<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
	$('#login-form').submit(function(e) {
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled', true).html('Logging in...');
		if ($(this).find('.alert-danger').length > 0)
			$(this).find('.alert-danger').remove();
		$.ajax({
			url: 'ajax.php?action=login',
			method: 'POST',
			data: $(this).serialize(),
			error: err => {
				console.log(err)
				$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success: function(resp) {
				if (resp == 1) {
					location.href = 'index.php?page=home';
				} else {
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>

</html>