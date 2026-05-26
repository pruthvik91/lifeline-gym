<?php include 'db_connect.php' ?>
<div class="container-fluid">
	<form action="" id="pause-membership-form">
		<input type="hidden" name="rid" value="<?php echo $_GET['rid'] ?>">
		<div class="form-group mb-3">
			<label for="pause_days" class="control-label fw-600 text-slate-700">Days to Pause</label>
			<input type="number" class="form-control form-control-lg bg-slate-50 border-slate-200" name="pause_days" id="pause_days" value="10" min="1" required>
            <small class="text-muted mt-2 d-block">The member's end date will be extended by this number of days.</small>
		</div>
	</form>
</div>
<script>
	$('#pause-membership-form').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=pause_membership',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp == 1){
					alert_toast("Membership successfully paused",'success')
					setTimeout(function(){
						location.reload()
					},750)
				}
			}
		})
	})
</script>
