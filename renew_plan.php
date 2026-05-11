<?php include 'db_connect.php' ;
$rid = $_GET['rid'];
$reg = $conn->query("SELECT plan_id FROM registration_info WHERE id = '$rid'")->fetch_assoc();
$current_plan_id = $reg ? $reg['plan_id'] : 0;?>
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
<div class="container-fluid">
    <form id="renew-form">
        <div class="form-group">
            <label for="plan_id">Select Plan to Renew</label>
            <select class="form-control" id="plan_id" name="plan_id" required>
                <option value="">-- Select Plan --</option>
                 <?php
                $plans = $conn->query("SELECT id, plan FROM plans ORDER BY plan ASC");
                while ($row = $plans->fetch_assoc()):
                    $selected = ($row['id'] == $current_plan_id) ? 'selected' : '';
                ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo $selected ?>>
                        <?php echo htmlspecialchars($row['plan']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>
</div>

<div class="modal-footer display">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    <button type="button" class="btn btn-primary" id="submitRenew">Confirm Renew</button>
</div>

<script>
$('#submitRenew').click(function(){
    var plan_id = $('#plan_id').val();
    if(!plan_id){
        alert_toast("Please select a plan","error");
        return false;
    }
    start_load();
    $.ajax({
        url: 'ajax.php?action=renew_membership',
        method: 'POST',
        data: {
            rid: '<?php echo $_GET['rid'] ?>',
            new_plan_id: plan_id
        },
        success: function(resp){
            if(resp > 0){
                alert_toast("Membership Successfully Renewed","success");
                end_load();
                uni_modal("<i class='fa fa-address-card'></i> Member Plan Details",
                    "view_pdetails.php?id=" + resp, '');
            }
        }
    })
});
</script>
