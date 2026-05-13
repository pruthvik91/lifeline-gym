<?php include('db_connect.php'); ?>

<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-800 text-slate-900">Member Login Logs</h4>
            <p class="text-slate-400 mb-0 extra-small">View history of member portal accesses</p>
        </div>
        <button class="btn btn-outline-danger shadow-sm rounded-pill px-4 extra-small fw-700" onclick="clearLogs()">
            <i class="fas fa-trash-alt mr-2"></i> Clear Logs
        </button>
    </div>

    <div class="card card-modern">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-modern mb-0">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">Timestamp</th>
                            <th class="px-4 py-3">Member ID</th>
                            <th class="px-4 py-3">Member Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $logs = $conn->query("SELECT * FROM member_login_logs ORDER BY id DESC");
                        if($logs->num_rows > 0):
                            while($row = $logs->fetch_assoc()):
                        ?>
                        <tr>
                            <td class='px-4 py-3'><span class='badge bg-light text-slate-900 border fw-600'><i class='far fa-clock mr-1'></i> <?php echo date("Y-m-d h:i:s A", strtotime($row['login_time'])) ?></span></td>
                            <td class='px-4 py-3 fw-700 text-primary'><?php echo $row['member_mid'] ?></td>
                            <td class='px-4 py-3 fw-600 text-slate-800'><?php echo $row['member_name'] ?></td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <tr><td colspan='3' class='text-center py-5 text-slate-400'><i class='fas fa-clipboard-list fa-3x mb-3 opacity-25'></i><br>No login logs found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function clearLogs(){
        if(confirm("Are you sure you want to clear all login logs? This cannot be undone.")){
            start_load();
            $.ajax({
                url: 'ajax.php?action=clear_logs',
                method: 'POST',
                success: function(resp){
                    if(resp == 1){
                        alert_toast("Logs successfully cleared", "success");
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }
                }
            })
        }
    }
</script>
