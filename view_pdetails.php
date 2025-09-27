<?php include 'db_connect.php' ?>
<?php
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT r.*,p.plan,p.amount as pamount,pp.package,pp.amount as ppamount,concat(m.lastname,' ',m.firstname,' ',m.middlename) as name,m.member_id as mid_no from registration_info r inner join members m on m.id = r.member_id inner join plans p on p.id = r.plan_id inner join packages pp on pp.id = r.package_id where r.id=" . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $v) {
		$$k = $v;
	}
	
}

?>
<div class="container-fluid">
    <p>Member ID: <b><?php echo $mid_no ?></b></p>
    <p>Name: <b><?php echo ucwords($name) ?></b></p>
    

    <hr class="divider">

</div>
<div class="modal-footer display p-0">
    <div class="row">
        <div class="col-md-12 p-0">
            <button class="btn float-right btn-secondary" type="button" data-dismiss="modal">Close</button>
            <button class="btn float-right btn-primary mr-2" type="button" id="payment">Payment</button>
            <?php if (strtotime(date('Y-m-d')) >= strtotime($end_date)) : ?>
               
            <button class="btn float-right btn-primary mr-2" type="button" id="renew">Renew</button>
            <?php endif; ?>
            <button class="btn float-right btn-primary mr-2" type="button" id="end">End Plan</button>
        </div>
    </div>
</div>


<style>
p {
    margin: unset;
}
#uni_modal .modal-body{
    padding:0 !important;

}
#uni_modal .modal-footer {
    display: none;
}

#uni_modal .modal-footer.display {
    display: block;
}
</style>
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