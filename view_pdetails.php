<?php include 'db_connect.php' ?>
<?php
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT r.*,p.plan,p.amount as pamount,pp.package,pp.amount as ppamount,concat(m.lastname,' ',m.firstname,' ',m.middlename) as name,m.member_id as mid_no from registration_info r inner join members m on m.id = r.member_id inner join plans p on p.id = r.plan_id inner join packages pp on pp.id = r.package_id where r.id=" . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $v) {
		$$k = $v;
	}
	$is_currently_paused = ($is_paused == 1 && $resume_date && strtotime(date('Y-m-d')) < strtotime($resume_date));
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
        <div class="col-12 col-md-4">
            <div class="p-3 border border-slate-100 rounded-3 h-100">
                <div class="text-slate-400 extra-small fw-700 text-uppercase mb-1">Status</div>
                <?php if($is_currently_paused): ?>
                    <div class="fw-700 text-warning">Paused until <?php echo date('d M', strtotime($resume_date)) ?></div>
                <?php else: ?>
                    <div class="fw-700 text-success">Active</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="p-3 border border-slate-100 rounded-3 h-100">
                <div class="text-slate-400 extra-small fw-700 text-uppercase mb-1">Current Plan</div>
                <div class="fw-700 text-slate-800"><?php echo $plan ?> Months</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="p-3 border border-slate-100 rounded-3 h-100">
                <div class="text-slate-400 extra-small fw-700 text-uppercase mb-1">Service Package</div>
                <div class="fw-700 text-slate-800"><?php echo $package ?></div>
            </div>
        </div>
    </div>
</div>

<div id="pause_form_container" style="display:none; background: #fffbeb; border-color: #fcd34d !important;" class="mt-3 p-3 rounded-3 border text-center">
    <div class="d-flex align-items-center justify-content-center mb-2">
        <i class="fas fa-pause-circle text-warning fs-4 me-2"></i>
        <div class="fw-700 text-dark">Pause Membership</div>
    </div>
    <p class="small mb-3" style="color: #92400e;">Enter the number of days you want to pause this membership. The end date will be automatically extended.</p>
    <div class="d-flex flex-wrap justify-content-center gap-2">
        <input type="number" id="pause_days_input" class="form-control text-center" value="10" min="1" style="max-width: 120px;">
        <button class="btn btn-primary px-4 fw-700" type="button" id="confirm_pause_btn">Confirm Pause</button>
        <button class="btn btn-light px-4 fw-700" type="button" id="cancel_pause_btn">Cancel</button>
    </div>
</div>

<style>
    .extra-small { font-size: 0.65rem; letter-spacing: 0.5px; }
    #uni_modal .modal-footer { display: none !important; }
    .swal2-container { z-index: 99999 !important; }
</style>

<div id="custom_modal_footer" class="px-4 py-3 bg-light border-top-0 d-flex justify-content-end flex-wrap gap-2">
    <button class="btn btn-danger-soft px-4 fw-700" type="button" id="end">End Plan</button>
    <?php if (strtotime(date('Y-m-d')) >= strtotime($end_date)) : ?>
        <button class="btn btn-primary px-4 fw-700" type="button" id="renew">Renew</button>
    <?php else: ?>
        <?php if($is_currently_paused): ?>
            <button class="btn btn-warning px-4 fw-700 text-dark" type="button" id="resume_plan">Resume Early</button>
        <?php else: ?>
            <button class="btn btn-warning px-4 fw-700 text-dark" type="button" id="pause_plan">Pause Plan</button>
        <?php endif; ?>
    <?php endif; ?>
    <button class="btn btn-primary px-4 fw-700 shadow-premium" type="button" id="payment">Make Payment</button>
    <button class="btn btn-light px-4 fw-700" type="button" data-dismiss="modal">Close</button>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
$('#payment').click(function() {
    uni_modal('Payments', 'payment.php?rid=<?php echo $id ?>', 'large')
})
$('#renew').click(function() {
    uni_modal('Renew Membership', 'renew_plan.php?rid=<?php echo $id ?>', 'small');
});

$('#pause_plan').click(function() {
    $('#pause_form_container').slideDown();
    $('#custom_modal_footer').slideUp();
});
$('#cancel_pause_btn').click(function() {
    $('#pause_form_container').slideUp();
    $('#custom_modal_footer').slideDown();
});
$('#confirm_pause_btn').click(function() {
    var pause_days = parseInt($('#pause_days_input').val());
    if(!isNaN(pause_days) && pause_days > 0) {
        start_load();
        $.ajax({
            url:'ajax.php?action=pause_membership',
            method:'POST',
            data:{rid:'<?php echo $id ?>', pause_days: pause_days},
            success:function(resp){
                if(resp == 1){
                    alert_toast('Membership has been paused for ' + pause_days + ' days.', 'success');
                    setTimeout(function(){ location.reload() }, 750);
                }
            }
        });
    } else {
        alert_toast('Please enter a valid number of days', 'error');
    }
});

$('#resume_plan').click(function() {
    $('.modal').removeAttr('tabindex');
    Swal.fire({
        title: 'Resume Membership Early?',
        text: "The member's expiration date will be adjusted to refund the unused paused days.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Yes, resume it',
        customClass: { confirmButton: 'btn btn-primary rounded-pill px-4 m-2', cancelButton: 'btn btn-secondary rounded-pill px-4 m-2' },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            start_load();
            $.ajax({
                url:'ajax.php?action=resume_membership',
                method:'POST',
                data:{rid:'<?php echo $id ?>'},
                success:function(resp){
                    if(resp == 1){
                        Swal.fire({title: 'Resumed!', text: 'Membership is now active again.', icon: 'success', customClass: { confirmButton: 'btn btn-primary rounded-pill px-4 m-2' }, buttonsStyling: false}).then(()=>{
                            $('.modal').modal('hide');
                            setTimeout(function(){ location.reload() }, 500);
                        });
                    }
                }
            });
        }
    });
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