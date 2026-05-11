<?php include 'db_connect.php' ?>
<?php
if (isset($_GET['rid'])) {
	$qry = $conn->query("SELECT r.*,p.plan,p.amount as pamount,pp.package,pp.amount as ppamount,concat(m.lastname,', ',m.firstname,' ',m.middlename) as name,m.member_id as mid_no from registration_info r inner join members m on m.id = r.member_id inner join plans p on p.id = r.plan_id inner join packages pp on pp.id = r.package_id where r.id=" . $_GET['rid'])->fetch_array();
	foreach ($qry as $k => $v) {
		$$k = $v;
	}
	
}

?>
<div class="container-fluid py-3">
    <div class="row g-4">
        <!-- Payment History Section -->
        <div class="col-lg-7">
            <div class="bg-white rounded-4 border border-slate-100 shadow-sm h-100 overflow-hidden d-flex flex-column">
                <div class="p-4 bg-slate-50 border-bottom border-slate-100 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-1 fw-800 text-slate-800"><i class="fas fa-history me-2 text-primary"></i>Transaction History</h6>
                        <p class="text-slate-500 extra-small fw-600 mb-0">Record of all previous payments for this plan</p>
                    </div>
                    <?php 
                        $total_paid_rows = $conn->query("SELECT * FROM payments where registration_id = $id ")->num_rows; 
                    ?>
                    <span class="badge bg-primary-light text-primary rounded-pill px-3"><?php echo $total_paid_rows ?> Payments</span>
                </div>
                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 border-0 small fw-800 text-slate-400 text-uppercase letter-spacing-1">Date</th>
                                <th class="border-0 small fw-800 text-slate-400 text-uppercase letter-spacing-1 text-end">Amount</th>
                                <th class="pe-4 border-0 small fw-800 text-slate-400 text-uppercase letter-spacing-1">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $paid = $conn->query("SELECT * FROM payments where registration_id = $id ORDER BY date_created DESC");
                            if($paid->num_rows > 0):
                                while ($row = $paid->fetch_assoc()) :
                            ?>
                            <tr class="border-bottom border-slate-50">
                                <td class="ps-4 py-3 fw-600 text-slate-600"><?php echo date("d M, Y", strtotime($row['date_created'])) ?></td>
                                <td class="text-end py-3 fw-800 text-slate-900">₹<?php echo number_format($row['amount'], 2) ?></td>
                                <td class="pe-4 py-3 text-slate-500 small"><?php echo $row['remarks'] ?></td>
                            </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-receipt fs-1 text-slate-200 mb-3"></i>
                                        <p class="text-slate-400 fw-600">No payment records found</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- New Payment Form Section -->
        <div class="col-lg-5">
            <div class="bg-white rounded-4 border border-slate-100 shadow-sm p-4 h-100">
                <div class="mb-4">
                    <h6 class="fw-800 text-slate-800 mb-1"><i class="fas fa-plus-circle me-2 text-success"></i>New Payment</h6>
                    <p class="text-slate-500 extra-small fw-600">Process a new transaction for this member</p>
                </div>
                
                <form id="manage_payment">
                    <input type="hidden" name="registration_id" value="<?php echo $id ?>">
                    <input type="hidden" name="member_id" value="<?php echo $mid_no; ?>">
                    
                    <div class="bg-slate-50 rounded-4 p-4 mb-4 border border-slate-100">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-slate-500 small fw-700 text-uppercase letter-spacing-1">Plan Fee:</span>
                            <span class="fw-800 <?php echo ($total_paid_rows <= 0) ? 'text-slate-900' : 'text-success' ?>">
                                <?php echo ($total_paid_rows <= 0) ? '₹' . number_format($pamount, 2) : '<i class="fas fa-check-circle me-1"></i>PAID' ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-slate-500 small fw-700 text-uppercase letter-spacing-1">Package:</span>
                            <span class="fw-800 text-slate-900">₹<?php echo number_format($ppamount, 2) ?></span>
                        </div>
                        <div class="pt-3 border-top border-slate-200 d-flex justify-content-between align-items-center">
                            <span class="text-slate-900 fw-800">PAYABLE:</span>
                            <span class="text-primary fw-900 fs-4">₹<?php echo ($total_paid_rows <= 0) ? number_format($pamount + $ppamount, 2) : number_format($ppamount, 2) ?></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-slate-700 fw-800 small text-uppercase letter-spacing-1">Payment Amount</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-slate-400 fw-800">₹</span>
                            <input type="number" step="any" class="form-control border-start-0 ps-0 fw-800 fs-5 text-primary" name="amount" required placeholder="0.00">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-slate-700 fw-800 small text-uppercase letter-spacing-1">Remarks</label>
                        <textarea class="form-control fw-600 text-slate-600" name="remarks" rows="2" placeholder="Note (optional)"></textarea>
                    </div>

                    <button class="btn btn-primary w-100 py-3 fw-800 shadow-premium">
                        <i class="fas fa-save me-2"></i>SAVE TRANSACTION
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer px-4 py-3 bg-light border-top-0" style="display: flex !important;">
    <button class="btn btn-light px-4 fw-700" type="button" data-dismiss="modal">Close</button>
</div>

<style>
    .bg-primary-light { background: #eef2ff; color: #4f46e5; }
    #uni_modal .modal-footer { display: none !important; }
    .form-control:focus { box-shadow: none; border-color: var(--primary); }
</style>
<script>
$('#manage_payment').submit(function(e) {
    e.preventDefault()
    start_load()
    $.ajax({
        url: 'ajax.php?action=save_payment',
        method: 'POST',
        data: $(this).serialize(),
        success: function(resp) {
            if (resp == 1) {
                alert_toast('Payment Successfully saved', 'success')
                end_load()
                uni_modal('Payments', 'payment.php?rid=<?php echo $id ?>', 'large')
            }
        }
    })
})
$('.view__member').click(function() {
		uni_modal("<i class='fa fa-id-card'></i> Member Details", "view_member.php?id=" + $(this).attr('data-id'),
			'large')

	})
</script>