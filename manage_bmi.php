<?php 
include 'db_connect.php';
session_start();
?>
<div class="container-fluid">
	<form action="" id="manage-bmi">
		<input type="hidden" name="member_id" value="<?php echo $_SESSION['member_id'] ?>">
		<div class="mb-3">
			<label for="weight" class="control-label fw-700 text-slate-600">Weight (kg)</label>
			<input type="number" step="any" name="weight" id="weight" class="form-control rounded-pill border-2" placeholder="e.g. 70" required>
		</div>
		<div class="mb-3">
			<label for="height" class="control-label fw-700 text-slate-600">Height (cm)</label>
			<input type="number" step="any" name="height" id="height" class="form-control rounded-pill border-2" placeholder="e.g. 175" required>
		</div>
	</form>
</div>
<script>
	$('#manage-bmi').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_bmi',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp == 1){
					alert_toast("BMI Logged Successfully","success")
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	})
</script>
