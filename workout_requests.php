<?php include('db_connect.php'); ?>

<div class="container-fluid py-2">
    <!-- Header Section -->
    <div class="d-flex align-items-end justify-content-between mb-4 px-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="index.php?page=home" class="text-decoration-none text-slate-400 fw-600 small">Dashboard</a></li>
                    <li class="breadcrumb-item active text-slate-500 fw-600 small" aria-current="page">Workout Plans</li>
                </ol>
            </nav>
            <h2 class="fw-800 text-slate-900 mb-0">Workout Assignments</h2>
        </div>
    </div>

    <!-- Table Container -->
    <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
        <div class="p-3 p-md-4 border-bottom bg-white d-flex flex-wrap gap-3 align-items-center justify-content-between">
            <h6 class="mb-0 fw-800 text-slate-800"><i class="fas fa-dumbbell me-2 text-primary"></i> Pending & Assigned Plans</h6>
            <div id="table-search-container" class="flex-grow-1" style="max-width: 400px;"></div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="workout-list">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="ps-4 py-3 text-slate-400 fw-800 text-uppercase small border-0" style="width: 80px;">#</th>
                        <th class="py-3 text-slate-400 fw-800 text-uppercase small border-0">Member Information</th>
                        <th class="py-3 text-slate-400 fw-800 text-uppercase small border-0">Request Timeline</th>
                        <th class="py-3 text-slate-400 fw-800 text-uppercase small border-0">Current Status</th>
                        <th class="pe-4 py-3 text-slate-400 fw-800 text-uppercase small border-0 text-end" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $requests = $conn->query("SELECT w.*, m.firstname, m.lastname, m.member_id as m_id, m.profile_pic FROM member_workouts w INNER JOIN members m ON m.id = w.member_id ORDER BY w.status ASC, w.date_requested DESC");
                    while($row = $requests->fetch_assoc()):
                    ?>
                    <tr class="athlete-row transition-all border-bottom border-slate-50">
                        <td class="ps-4 py-4 fw-800 text-slate-300"><?php echo str_pad($i++, 2, '0', STR_PAD_LEFT) ?></td>
                        <td class="py-4">
                            <div class="d-flex align-items-center">
                                <div class="member-avatar me-3">
                                    <?php if(!empty($row['profile_pic'])): ?>
                                        <img src="assets/uploads/<?php echo $row['profile_pic'] ?>" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
                                    <?php else: ?>
                                        <?php echo substr($row['firstname'], 0, 1) ?>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="fw-800 text-slate-800"><?php echo ucwords($row['firstname'].' '.$row['lastname']) ?></div>
                                    <div class="small text-primary fw-800">#<?php echo $row['m_id'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4">
                            <div class="text-slate-700 fw-700 small mb-1">
                                <i class="far fa-calendar-alt me-1 text-slate-200"></i>
                                <?php echo date('d M, Y', strtotime($row['date_requested'])) ?>
                            </div>
                            <div class="text-slate-400 fw-600 x-small">
                                <i class="far fa-clock me-1 text-slate-100"></i>
                                <?php echo date('h:i A', strtotime($row['date_requested'])) ?>
                            </div>
                        </td>
                        <td class="py-4">
                            <?php if($row['status'] == 0): ?>
                                <span class="badge-custom badge-warning">
                                    <span class="pulse-dot"></span>
                                    Pending
                                </span>
                            <?php else: ?>
                                <span class="badge-custom badge-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Assigned
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="pe-4 py-4 text-end">
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <button class="icon-btn-premium icon-btn-edit assign_workout" title="Assign Plan" data-id="<?php echo $row['id'] ?>" data-name="<?php echo ucwords($row['firstname'].' '.$row['lastname']) ?>">
                                    <i class="fas fa-file-upload"></i>
                                </button>
                                            <?php if($row['status'] == 1 && $row['file_path']): ?>
                                                <a href="assets/uploads/<?php echo $row['file_path'] ?>" target="_blank" class="icon-btn-premium icon-btn-view" title="View Plan">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="icon-btn-premium icon-btn-delete delete_workout" title="Delete Plan" data-id="<?php echo $row['id'] ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .member-avatar {
        width: 38px;
        height: 38px;
        background: var(--slate-100);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-weight: 800;
        font-size: 0.9rem;
        overflow: hidden;
    }

    .badge-custom {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
    }

    .badge-warning { background: #fff7ed; color: #c2410c; }
    .badge-success { background: #f0fdf4; color: #15803d; }

    .pulse-dot {
        width: 6px;
        height: 6px;
        background: #f97316;
        border-radius: 50%;
        margin-right: 8px;
        box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4); }
        70% { box-shadow: 0 0 0 8px rgba(249, 115, 22, 0); }
        100% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0); }
    }

    .athlete-row:hover { background-color: #fafafa !important; }
    .x-small { font-size: 0.7rem; }
</style>

<script>
    $(document).ready(function(){
        var table = $('#workout-list').DataTable({
            "order": [],
            "pageLength": 25,
            "dom": 'f rtip',
            "language": {
                "search": "",
                "searchPlaceholder": "Search by member name or ID...",
            }
        });

        // Move search box to custom container
        $('.dataTables_filter').appendTo('#table-search-container');
        $('.dataTables_filter').addClass('w-100');
        $('.dataTables_filter input').addClass('form-control border bg-white px-3 fw-600 text-slate-600 rounded-pill shadow-sm').css({
            'height': '40px',
            'border-color': '#e2e8f0'
        });
    })
    
    $(document).on('click', '.assign_workout', function(){
        uni_modal("<i class='fas fa-dumbbell me-2'></i>Manage Plan Assignment","manage_workout.php?id="+$(this).attr('data-id'),"mid-large")
    })

    $(document).on('click', '.delete_workout', function(){
        _conf("Are you sure you want to remove this assigned plan? The request will go back to 'Pending' status.","remove_workout",[$(this).attr('data-id')])
    })

    function remove_workout($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=remove_workout',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp==1){
                    alert_toast("Workout plan removed successfully","success")
                    setTimeout(function(){
                        location.reload()
                    },1000)
                }
            }
        })
    }
</script>
