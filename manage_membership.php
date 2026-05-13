<?php include 'db_connect.php' ?>
<?php
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM members where id=" . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $v) {
		$$k = $v;
	}
}
?>

<style>
    .premium-form-group { margin-bottom: 1.5rem; }
    .premium-label { 
        font-size: 0.8rem; 
        font-weight: 800; 
        color: var(--slate-600); 
        text-transform: uppercase; 
        letter-spacing: 0.05em;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .premium-label i {
        font-size: 1rem;
        color: var(--primary);
        opacity: 0.8;
    }
    .form-control-premium {
        background: var(--slate-50);
        border: 1px solid var(--slate-100);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: var(--slate-800);
        transition: all 0.2s ease;
    }
    .form-control-premium:focus {
        background: white;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }
    
    /* Select2 Premium Customization */
    .select2-container--default .select2-selection--single {
        height: 48px !important;
        background: var(--slate-50) !important;
        border: 1px solid var(--slate-100) !important;
        border-radius: 12px !important;
        padding-top: 10px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--slate-800) !important;
        font-weight: 600 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
    }
    .select2-container--open {
        z-index: 9999999 !important;
    }
    .select2-dropdown {
        border: none !important;
        box-shadow: var(--shadow-premium) !important;
        border-radius: 12px !important;
        overflow: hidden;
    }
</style>

<div class="container-fluid py-2">
    <form action="" id="manage-member">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        
        <div class="premium-form-group">
            <label class="premium-label"><i class="fas fa-user"></i>Select Member</label>
            <select name="member_id" required="required" class="select2">
                <option value=""></option>
                <?php
				$qry = $conn->query("SELECT *,concat(firstname,' ',lastname,' ',middlename) as name FROM members where id not in (SELECT member_id from registration_info where status = 1) order by concat(firstname,' ',lastname,' ',middlename) asc");
				while ($row = $qry->fetch_assoc()) :
				?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($member_id) && $member_id == $row['id'] ? 'selected' : '' ?>>
                    <?php echo ucwords($row['name']) ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="premium-form-group">
                    <label class="premium-label"><i class="fas fa-calendar-check"></i>Membership Plan</label>
                    <select name="plan_id" required="required" class="select2">
                        <option value=""></option>
                        <?php
                        $qry = $conn->query("SELECT * FROM plans order by plan asc");
                        while ($row = $qry->fetch_assoc()) :
                        ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($plan_id) && $plan_id == $row['id'] ? 'selected' : '' ?>>
                            <?php echo ucwords($row['plan']) ?> Months
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="premium-form-group">
                    <label class="premium-label"><i class="fas fa-box"></i>Service Package</label>
                    <select name="package_id" required="required" class="select2">
                        <option value=""></option>
                        <?php
                        $qry = $conn->query("SELECT * FROM packages order by package asc");
                        while ($row = $qry->fetch_assoc()) :
                        ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($package_id) && $package_id == $row['id'] ? 'selected' : '' ?>>
                            <?php echo ucwords($row['package']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="premium-form-group">
            <label class="premium-label"><i class="fas fa-calendar-alt"></i>Start Date</label>
            <input type="date" name="start_date" class="form-control-premium w-100" value="<?php echo date('Y-m-d') ?>" required>
        </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        $('.select2').select2({
            placeholder: 'Choose Option',
            width: '100%',
            dropdownParent: $('#uni_modal')
        });

        $('#manage-member').submit(function(e) {
            e.preventDefault()
            start_load()
            $.ajax({
                url: 'ajax.php?action=save_membership',
                method: 'POST',
                data: $(this).serialize(),
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Plan successfully assigned.", 'success')
                        setTimeout(function() {
                            location.reload()
                        }, 1000)
                    }
                }
            })
        })
    })
</script>