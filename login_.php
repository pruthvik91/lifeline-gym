<!DOCTYPE html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
<?php 
session_start();
include('./db_connect.php');
ob_start();
if(!isset($_SESSION['system'])){
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
 	

<?php include('./header.php'); ?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

?>
 <script src="https://kit.fontawesome.com/a076d05399.js"></script>
<link rel="stylesheet" href="login.css">
</head>


<body>

    <div class="content">
 
      <div class="text">Login Form</div>
      <form action="#" id="login-form">
        <div class="field">
          <span class="fas fa-user"></span>
          
          <input type="text"  name="username" required>
          <label>Email or Phone</label>
          
        </div>
        <div class="field">
          <span class="fas fa-lock"></span>
          <input type="password" id="password" name="password">
         
        </div>
        <div class="forgot-pass"><a href="#">Forgot Password?</a></div>
        <button>Sign in</button>
        <div class="signup">Not a member?
          <a href="#">signup now</a>
        </div>
      </form>
  </div>


  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>	
</html>