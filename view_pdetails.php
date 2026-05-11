<?php include 'db_connect.php' ?>
<?php
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT r.*,p.plan,p.amount as pamount,pp.package,pp.amount as ppamount,concat(m.lastname,' ',m.firstname,' ',m.middlename) as name,m.member_id as mid_no from registration_info r inner join members m on m.id = r.member_id inner join plans p on p.id = r.plan_id inner join packages pp on pp.id = r.package_id where r.id=" . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $v) {
		$$k = $v;
	}
	
}

?>
<div class="container-fluid py-3">
    <div class="bg-slate-50 rounded-3 p-3 mb-4 border border-slate-100">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="rounded-circle bg-primary-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #eef2ff; color: #4f46e5;">
                    <i class="fas fa-id-badge fs-4"></i>
                </div>
            </div>
            <div class="col">
                <div class="text-slate-400 small fw-600 mb-0">Member Information</div>
                <div class="fw-800 text-slate-900 fs-5"><?php echo ucwords($name) ?></div>
                <div class="small text-slate-500 fw-700">ID: #<?php echo $mid_no ?></div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-6">
            <div class="p-3 border border-slate-100 rounded-3">
                <div class="text-slate-400 extra-small fw-700 text-uppercase mb-1">Current Plan</div>
                <div class="fw-700 text-slate-800"><?php echo $plan ?> Months</div>
            </div>
        </div>
        <div class="col-6">
            <div class="p-3 border border-slate-100 rounded-3">
                <div class="text-slate-400 extra-small fw-700 text-uppercase mb-1">Service Package</div>
                <div class="fw-700 text-slate-800"><?php echo $package ?></div>
            </div>
        </div>
    </div>
</div>

<style>
    .extra-small { font-size: 0.65rem; letter-spacing: 0.5px; }
    #uni_modal .modal-footer { display: none !important; }
</style>

<div class="modal-footer px-4 py-3 bg-light border-top-0 d-flex justify-content-end gap-2" style="display: flex !important;">
    <button class="btn btn-danger-soft px-4 fw-700" type="button" id="end">End Plan</button>
    <?php if (strtotime(date('Y-m-d')) >= strtotime($end_date)) : ?>
        <button class="btn btn-primary px-4 fw-700" type="button" id="renew">Renew</button>
    <?php endif; ?>
    <button class="btn btn-primary px-4 fw-700 shadow-premium" type="button" id="payment">Make Payment</button>
    <button class="btn btn-light px-4 fw-700" type="button" data-dismiss="modal">Close</button>
</div>
<script>
    
$('#payment').click(function() {
    uni_modal('Payments', 'payment.php?rid=<?php echo $id ?>', 'large')
})
$('#renew').click(function() {
    uni_modal('Renew Membership', 'renew_plan.php?rid=<?php echo $id ?>', 'small');
});


// $('#renew').click(function() {
//     start_load()
//     $.ajax({
//         url: 'ajax.php?action=renew_membership',
//         method: 'POST',
//         data: {
//             rid: '<?php // echo $id ?>'
//         },
//         success: function(resp) {
//             if (resp > 0) {
//                 alert_toast('Membership Successfully renewed', 'success')
//                 end_load()
//                 uni_modal("<i class='fa fa-address-card'></i> Member Plan Details",
//                     "view_pdetails.php?id=" + resp, '')
//             }
//         }
//     })
// })
$('#end').click(function() {
    start_load();
    $.ajax({
        url: 'delete_payments.php',
        method: 'POST',
        data: {
            member_id: '<?php echo $mid_no ?>'
        },
        success: function(resp) {
            if (resp > 0) {
                alert_toast('Membership Successfully Closed and Payments Removed', 'success');
                setTimeout(function() {
                    location.reload();
                }, 750);
                // Additional function
                additionalFunction();
            }
        }
    });
});

function additionalFunction() {
    start_load();
    $.ajax({
        url: 'ajax.php?action=end_membership',
        method: 'POST',
        data: {
            rid: '<?php echo $id ?>'
        },
        success: function(resp) {
            if (resp == 1) {
                alert_toast('Membership Successfully Closed', 'success');
                setTimeout(function() {
                    location.reload();
                }, 750);
            }
        }
    });
}

</script>