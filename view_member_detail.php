<?php
include 'db_connect.php';
session_start();

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT *,concat(lastname,' ',firstname,' ',middlename) as name FROM members where id=" . $_GET['id'])->fetch_array();
    foreach ($qry as $k => $v) {
        $$k = $v;
    }
}
?>
<div class="container-fluid" id="member-profile">
    <div class="row">
        <div class="col-md-4 text-center">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($profile_pic)): ?>
                        <img src="assets/uploads/<?php echo $profile_pic ?>" alt="Member Photo" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php else: ?>
                        <img src="assets/img/default-avatar.png" alt="No Photo" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php endif; ?>
                    <h4 class="mt-3"><?php echo ucwords($name) ?></h4>
                    <p class="text-muted">Member ID: <?php echo $member_id ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <b>Member Details</b>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Contact:</strong> <br> <?php echo $contact ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Gender:</strong> <br> <?php echo ucwords($gender) ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Address:</strong> <br> <?php echo $address ?>
                        </div>
                    </div>
                    <hr>
                    <h5>Membership Info</h5>
                     <?php
                    $pcount = 0;
                    $paid = $conn->query("SELECT r.*,pl.plan,pa.package FROM registration_info r inner join plans pl on pl.id = r.plan_id inner join packages pa on pa.id = r.package_id where r.member_id = $id order by id desc limit 1");
                    while ($row = $paid->fetch_assoc()) :
                        $pcount++;
                    ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                             <strong>Plan:</strong> <?php echo $row['plan'] . ' months' ?>
                        </div>
                        <div class="col-md-6">
                             <strong>Package:</strong> <?php echo $row['package'] ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                         <div class="col-md-6">
                             <strong>Start Date:</strong> <?php echo date("M d, Y", strtotime($row['start_date'])) ?>
                        </div>
                        <div class="col-md-6">
                             <strong>End Date:</strong> <span style="color: red; font-weight: 800;"><?php echo date("M d, Y", strtotime($row['end_date'])) ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                             <strong>Status:</strong>
                             <?php if ($row['status'] == 1) : ?>
                                <?php
                                $end_date = $row['end_date'];
                                $days_remaining = ceil((strtotime($end_date) - time()) / (60 * 60 * 24));
                                if ($days_remaining > 0) {
                                    $badge_class = $days_remaining <= 7 ? 'badge-danger' : 'badge-success';
                                    echo "<span class='badge $badge_class'>$days_remaining days remaining</span>";
                                } else {
                                    echo "<span class='badge badge-danger'>Expired</span>";
                                }
                                ?>
                             <?php elseif ($row['status'] == 2) : ?>
                                <span class="badge badge-secondary">Closed</span>
                              <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#view_receipt').click(function(){
        uni_modal("<i class='fa fa-file-invoice'></i> Receipt","view_receipt.php?id="+$(this).attr('data-id'),'large')
    })
</script>
