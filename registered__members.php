<?php include('db_connect.php'); ?>

<div class="container-fluid">
	<style>
		input[type=checkbox] {
			/* Double-sized Checkboxes */
			-ms-transform: scale(1.5);
			/* IE */
			-moz-transform: scale(1.5);
			/* FF */
			-webkit-transform: scale(1.5);
			/* Safari and Chrome */
			-o-transform: scale(1.5);
			/* Opera */
			transform: scale(1.5);
			padding: 10px;
		}
	</style>
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">

			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Active Member List</b>
		
						<span class="">

							<button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_member">
								<i class="fa fa-plus"></i> New</button>
						</span>
					</div>
					<div class="card-body">

						<table class="table table-bordered table-condensed table-hover">
							<colgroup>
								<col width="5%">
								<col width="15%">
								<col width="20%">
								<col width="20%">
								<col width="20%">
								<col width="20%">
								<col width="10%">
							</colgroup>
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Member ID</th>
									<th class="">Name</th>
									<th class="">Plan</th>
									<th class="">Package</th>
									<th class="">End date</th>
									<th class="">Status</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$member =  $conn->query("SELECT r.*,p.plan,pp.package,concat(m.lastname,' ',m.firstname,' ',m.middlename) as name,r.member_id from registration_info r inner join members m on m.id = r.member_id inner join plans p on p.id = r.plan_id inner join packages pp on pp.id = r.package_id where r.status = 1 order by r.id desc");
								while ($row = $member->fetch_assoc()) :
								?>
									<tr>

										<td class="text-center"><?php echo $i++ ?></td>
										<td class="">
											<p><b><?php echo $row['member_id'] ?></b></p>

										</td>
										<td class="">
											<p><b><?php echo ucwords($row['name']) ?></b></p>

										</td>
										<td class="">
											<p><b><?php echo $row['plan'] . 'Months' ?></b></p>
										</td>
										<td class="">
											<p><b><?php echo $row['package'] ?></b></p>

										</td>
										<td class="">
											<p ><?php echo date("d-m-Y", strtotime($row['end_date'])) ?></p>

										</td>
										<td class="text-center">
											<?php if (strtotime(date('Y-m-d')) <= strtotime($row['end_date'])) : ?>
												<span class="badge badge-success">Active</span>
											<?php else : ?>
												<span class="badge badge-danger">Exprired</span>
											<?php endif; ?>
										</td>
										<td class="text-center">
											<button class="btn btn-sm btn-outline-primary view_member" type="button" data-id="<?php echo $row['id'] ?>">View</button>
											<?php
											if (isset($_GET['receipt_id'])) {
												$qry = $conn->query("SELECT *,concat(lastname,' ',firstname,' ',middlename) as name FROM members where member_id=" . $_GET['receipt_id'])->fetch_array();
												foreach ($qry as $k => $v) {
													$$k = $v;
												}
											}

											?>
											<button class="btn btn-sm btn-outline-primary view__member" type="button" data-id="<?php echo $row['receipt_id'] ?>">Receipt</button>
										

										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>

</div>
<style>
	td {
		vertical-align: middle !important;
	}

	td p {
		margin: unset
	}

	img {
		max-width: 227px;
		margin-left: 60px;
		max-height: 150px;
	}
</style>
<script>
	
	$(document).ready(function() {
		$('table').dataTable()
	})
	$('#new_member').click(function() {
		uni_modal("<i class='fa fa-plus'></i> New Membership Plan", "manage_membership.php", '')
	})
	$('.view_member').click(function(){
		uni_modal("<i class='fa fa-address-card'></i> Member Plan Details","view_pdetails.php?id="+$(this).attr('data-id'),'')

	})
	$('.edit_member').click(function() {
		uni_modal("<i class='fa fa-edit'></i> Manage Member Details", "manage_member.php?id=" + $(this).attr('data-id'), 'mid-large')

	})
	$('.view__member').click(function(){
		uni_modal("<i class='fa fa-address-card'></i> Member Plan Details","view_member.php?id="+$(this).attr('data-id'),'large')

	})
	

	function delete_member($id) {
		start_load()

		$.ajax({

			url: 'ajax.php?action=delete_member',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
	
function myFunction() {
  // Declare variables
  var input, filter,  i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
 
  

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("name")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
