<?php include('db_connect.php');?>

<div class="container-fluid py-4">
	<div class="row g-4">
		<!-- FORM Panel -->
		<div class="col-lg-4">
			<form action="" id="manage-package">
				<div class="card border-0 shadow-premium rounded-4">
					<div class="card-header bg-white py-3">
						<h5 class="mb-0 fw-800 text-slate-800"><i class="fas fa-box-open me-2 text-primary"></i>Package Form</h5>
					</div>
					<div class="card-body p-4">
						<input type="hidden" name="id">
						<div class="form-group mb-3">
							<label class="form-label fw-700 text-slate-600">Package Name</label>
							<input type="text" class="form-control border-2 rounded-3 py-2" name="package" placeholder="e.g. Full Gym Access">
						</div>
						<div class="form-group mb-0">
							<label class="form-label fw-700 text-slate-600">Description</label>
							<textarea class="form-control border-2 rounded-3" cols="30" rows='4' name="description" placeholder="Describe the inclusions..."></textarea>
						</div>
					</div>
					<div class="card-footer bg-slate-50 border-0 p-4 rounded-bottom-4">
						<div class="d-grid gap-2 d-md-flex justify-content-md-end">
							<button class="btn btn-light rounded-pill px-4 fw-700" type="button" onclick="_reset()">Cancel</button>
							<button class="btn btn-primary rounded-pill px-4 shadow-sm fw-700">Save Package</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		
		<!-- Table Panel -->
		<div class="col-lg-8">
			<div class="card border-0 shadow-premium rounded-4 overflow-hidden">
				<div class="card-header bg-white py-3">
					<h5 class="mb-0 fw-800 text-slate-800"><i class="fas fa-layer-group me-2 text-primary"></i>Service Packages</h5>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table align-middle mb-0">
							<thead>
								<tr>
									<th class="ps-4" style="width: 80px;">#</th>
									<th>Package Details</th>
									<th class="text-end pe-4">Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$package = $conn->query("SELECT * FROM packages order by id asc");
								while($row=$package->fetch_assoc()):
								?>
								<tr class="athlete-row">
									<td class="ps-4">
										<span class="text-slate-400 fw-700"><?php echo str_pad($i++, 2, '0', STR_PAD_LEFT) ?></span>
									</td>
									<td>
										<div class="d-flex align-items-start">
											<div class="athlete-avatar">
												<i class="fas fa-gift"></i>
											</div>
											<div>
												<div class="fw-800 text-slate-900 fs-6"><?php echo $row['package'] ?></div>
												<div class="text-slate-500 small fw-500 mt-1"><?php echo $row['description'] ?></div>
											</div>
										</div>
									</td>
									<td class="text-end pe-4">
										<div class="d-flex align-items-center justify-content-end gap-2">
											<button class="action-btn edit_package" title="Edit Package" 
												data-id="<?php echo $row['id'] ?>" 
												data-package="<?php echo $row['package'] ?>" 
												data-description="<?php echo $row['description'] ?>"
                                                style="background: #eef2ff; color: #4338ca;">
												<i class="fas fa-edit"></i>
											</button>
											<button class="action-btn delete_package" title="Delete Package" 
												data-id="<?php echo $row['id'] ?>"
                                                style="background: #fee2e2; color: #b91c1c;">
												<i class="fas fa-trash-alt"></i>
											</button>
										</div>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    
    .athlete-avatar {
        width: 40px;
        height: 40px;
        background: var(--slate-100);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1rem;
        margin-right: 15px;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        filter: brightness(0.9);
    }
</style>

<script>
	function _reset(){
		$('#manage-package').get(0).reset()
		$('#manage-package input,#manage-package textarea').val('')
	}
	
	$('#manage-package').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_package',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Package successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
				else if(resp==2){
					alert_toast("Package successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})
	})
	
	$('.edit_package').click(function(){
		start_load()
		var form = $('#manage-package')
		form.get(0).reset()
		form.find("[name='id']").val($(this).attr('data-id'))
		form.find("[name='package']").val($(this).attr('data-package'))
		form.find("[name='description']").val($(this).attr('data-description'))
		end_load()
        
        if(window.innerWidth < 992) {
            form[0].scrollIntoView({ behavior: 'smooth' });
        }
	})
	
	$('.delete_package').click(function(){
		_conf("Are you sure to delete this package?","delete_package",[$(this).attr('data-id')])
	})
    
	function delete_package($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_package',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Package successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})
	}
    
	$('table').dataTable({
        "pageLength": 10,
        "language": {
            "search": "",
            "searchPlaceholder": "Filter packages..."
        },
        "initComplete": function() {
            $('.dataTables_filter input').addClass('form-control border bg-white px-3 fw-600 text-slate-600 rounded-pill shadow-sm').css({
                'height': '36px',
                'border-color': '#e2e8f0',
                'margin-bottom': '10px'
            });
        }
    });
</script>