<?php include('db_connect.php'); ?>

<div class="container-fluid py-2">
    <!-- Header Section -->
    <div class="d-flex align-items-end justify-content-between mb-5">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-slate-400 fw-600 small">Dashboard</a></li>
                    <li class="breadcrumb-item active text-slate-900 fw-600 small" aria-current="page">Staff Management</li>
                </ol>
            </nav>
            <h2 class="fw-800 text-slate-900 mb-0" style="letter-spacing: -0.5px;">Staff Members</h2>
        </div>
        <button class="btn btn-primary shadow-premium px-4 py-2" type="button" id="new_user">
            <i class="fas fa-plus me-2"></i> Add Staff
        </button>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-4 shadow-soft overflow-hidden border border-slate-100">
        <div class="p-4 border-bottom border-slate-100 bg-slate-50 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-700 text-slate-700">System Access Control</h6>
        </div>
        <div class="table-responsive">
            <table class="table align-middle" id="user-table">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 80px;">No.</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th class="text-end pe-4" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $type = array("", "Administrator", "Staff Member", "Alumnus/Alumna");
                    $users = $conn->query("SELECT * FROM users order by name asc");
                    $i = 1;
                    while ($row = $users->fetch_assoc()) :
                    ?>
                        <tr class="user-row">
                            <td class="ps-4">
                                <span class="text-slate-400 fw-700 small"><?php echo str_pad($i++, 2, '0', STR_PAD_LEFT) ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-placeholder">
                                        <?php echo strtoupper(substr($row['name'], 0, 1)) ?>
                                    </div>
                                    <div class="fw-700 text-slate-900"><?php echo ucwords($row['name']) ?></div>
                                </div>
                            </td>
                            <td>
                                <span class="text-slate-600 fw-600 small"><i class="fas fa-at me-2 text-slate-200"></i><?php echo $row['username'] ?></span>
                            </td>
                            <td>
                                <?php if($row['type'] == 1): ?>
                                    <span class="badge bg-primary-light text-primary px-3 py-2 rounded-pill fw-700 small">
                                        <i class="fas fa-shield-alt me-1"></i> <?php echo $type[$row['type']] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-slate-100 text-slate-600 px-3 py-2 rounded-pill fw-700 small">
                                        <i class="fas fa-user me-1"></i> <?php echo $type[$row['type']] ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <button class="icon-btn-premium icon-btn-edit edit_user" title="Edit" data-id="<?php echo $row['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="icon-btn-premium icon-btn-delete delete_user" title="Delete" data-id="<?php echo $row['id'] ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
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
    .user-avatar-placeholder {
        width: 32px;
        height: 32px;
        background: var(--slate-100);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-weight: 800;
        margin-right: 12px;
        font-size: 0.8rem;
    }

    .bg-primary-light { background: #eef2ff; }

    .user-row:hover {
        background-color: #fafafa !important;
    }

    .icon-btn-premium {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: var(--slate-400);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .icon-btn-premium:hover {
        background: var(--slate-100);
        color: var(--primary);
    }
</style>

<script>
    $(document).ready(function() {
        $('#user-table').DataTable({
            "dom": 'rtip',
            "pageLength": 25,
            "autoWidth": false
        });
    });

    $('#new_user').click(function() {
        uni_modal("<i class='fas fa-user-plus me-2'></i>Create New Staff Account", "manage_user.php")
    })

    $('.edit_user').click(function() {
        uni_modal("<i class='fas fa-user-edit me-2'></i>Edit Staff Account", "manage_user.php?id=" + $(this).attr('data-id'))
    })

    $('.delete_user').click(function() {
        _conf("Are you sure you want to delete this staff account?", "delete_user", [$(this).attr('data-id')])
    })

    function delete_user($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_user',
            method: 'POST',
            data: { id: $id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Staff account successfully removed", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)
                }
            }
        })
    }
</script>