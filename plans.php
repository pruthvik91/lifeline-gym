<?php include('db_connect.php'); ?>

<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- FORM Panel -->
        <div class="col-lg-4">
            <form action="" id="manage-plan">
                <div class="card border-0 shadow-premium rounded-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-800 text-slate-800"><i class="fas fa-edit me-2 text-primary"></i>Plan Editor</h5>
                    </div>
                    <div class="card-body p-4">
                        <input type="hidden" name="id">
                        <div class="form-group mb-0">
                            <label class="form-label fw-700 text-slate-600">Plan Duration (months)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-slate-50 border-2 border-end-0 rounded-start-3"><i class="fas fa-calendar-alt text-slate-400"></i></span>
                                <input type="number" class="form-control border-2 rounded-end-3 py-2" min="1" name="plan" placeholder="Enter number of months">
                            </div>
                            <small class="text-slate-400 mt-2 d-block">Specify the membership duration in months.</small>
                        </div>
                    </div>
                    <div class="card-footer bg-slate-50 border-0 p-4 rounded-bottom-4">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-light rounded-pill px-4 fw-700" type="button" onclick="_reset()">Cancel</button>
                            <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-700">Save Plan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Table Panel -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-800 text-slate-800"><i class="fas fa-list-ul me-2 text-primary"></i>Available Plans</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width: 100px;">#</th>
                                    <th>Membership Duration</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
								$i = 1;
								$plan = $conn->query("SELECT * FROM plans order by id asc");
								while ($row = $plan->fetch_assoc()) :
								?>
                                <tr class="athlete-row">
                                    <td class="ps-4">
                                        <span class="text-slate-400 fw-700"><?php echo str_pad($i++, 2, '0', STR_PAD_LEFT) ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="athlete-avatar">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div>
                                                <span class="fw-800 text-slate-900 fs-5"><?php echo $row['plan'] ?></span>
                                                <span class="text-slate-500 fw-600 ms-1">month/s</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <button class="action-btn edit_plan" title="Edit Plan" 
                                                data-id="<?php echo $row['id'] ?>" 
                                                data-plan="<?php echo $row['plan'] ?>"
                                                style="background: #eef2ff; color: #4338ca;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-btn delete_plan" title="Delete Plan" 
                                                data-id="<?php echo $row['id'] ?>"
                                                style="background: #fee2e2; color: #b91c1c;">
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
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    
    .athlete-avatar {
        width: 40px;
        height: 40px;
        background: var(--slate-100);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1rem;
        margin-right: 15px;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        filter: brightness(0.9);
    }
</style>

<script>
    function _reset() {
        $('#manage-plan').get(0).reset()
        $('#manage-plan input').val('')
    }
    
    $('#manage-plan').submit(function(e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_plan',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Plan successfully added", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1000)
                } else if (resp == 2) {
                    alert_toast("Plan successfully updated", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1000)
                }
            }
        })
    })
    
    $('.edit_plan').click(function() {
        start_load()
        var form = $('#manage-plan')
        form.get(0).reset()
        form.find("[name='id']").val($(this).attr('data-id'))
        form.find("[name='plan']").val($(this).attr('data-plan'))
        end_load()
        
        if(window.innerWidth < 992) {
            form[0].scrollIntoView({ behavior: 'smooth' });
        }
    })
    
    $('.delete_plan').click(function() {
        _conf("Are you sure to delete this membership plan?", "delete_plan", [$(this).attr('data-id')])
    })

    function delete_plan($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_plan',
            method: 'POST',
            data: { id: $id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Plan successfully deleted", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1000)
                }
            }
        })
    }
    
    $('table').dataTable({
        "pageLength": 10,
        "language": {
            "search": "",
            "searchPlaceholder": "Filter plans..."
        },
        "initComplete": function() {
            $('.dataTables_filter input').addClass('form-control border bg-white px-3 fw-600 text-slate-600 rounded-pill shadow-sm').css({
                'height': '36px',
                'border-color': '#e2e8f0',
                'margin-bottom': '10px'
            });
        }
    });
</script>