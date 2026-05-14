<?php include('db_connect.php'); ?>

<div class="container-fluid py-2 py-md-4">
    <!-- Premium Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 px-2">
        <div>
            <h2 class="fw-800 text-slate-900 mb-1">Membership Validity</h2>
            <p class="text-slate-500 fw-500 mb-0">Track active plans and expiration dates</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary rounded-pill px-4" type="button" id="send_message">
                <i class="fas fa-receipt me-2"></i> <span>Send Receipt</span>
            </button>
            <button class="btn btn-outline-primary rounded-pill px-4" type="button" id="send_message_only">
                <i class="fab fa-whatsapp me-2"></i> <span>WhatsApp Msg</span>
            </button>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" type="button" id="new_member">
                <i class="fas fa-plus me-2"></i> <span>New Membership</span>
            </button>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="p-2 p-md-3 bg-white border-bottom d-flex flex-wrap gap-2 align-items-center">
                <div id="table-filter-container" class="flex-grow-1"></div>
                <select id="sort-filter" class="form-select form-select-sm border-0 bg-slate-50 fw-600 text-slate-600 rounded-pill px-3" style="width: auto; height: 34px;">
                    <option value="0">Sort: Default</option>
                    <option value="1">Sort: Name (A-Z)</option>
                    <option value="4">Sort: Expiry Date</option>
                    <option value="5">Sort: Days Left</option>
                </select>
                <select id="status-filter" class="form-select form-select-sm border-0 bg-slate-50 fw-600 text-slate-600 rounded-pill px-3" style="width: auto; height: 34px;">
                    <option value="">Filter: All</option>
                    <option value="status-active">Active Only</option>
                    <option value="status-warning">Expiring Soon</option>
                    <option value="status-expired">Expired Only</option>
                </select>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="validity-table">
                    <thead>
                        <tr>
                            <th class="ps-4">No.</th>
                            <th>Member</th>
                            <th>Subscription Plan</th>
                            <th>Service Package</th>
                            <th>Expiration</th>
                            <th class="text-center">Remaining</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data loaded via Ajax -->
                    </tbody>
                </table>
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
        font-weight: 800;
        margin-right: 12px;
        overflow: hidden;
    }

    .validity-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active { background: #dcfce7 !important; color: #166534 !important; }
    .status-warning { background: #fef3c7 !important; color: #92400e !important; }
    .status-expired { background: #fee2e2 !important; color: #991b1b !important; }

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
	$(document).ready(function() {
		var table = $('#validity-table').DataTable({
            "processing": true,
            "autoWidth": false,
            "ajax": "ajax.php?action=get_registered_members",
            "deferRender": true,
            "columns": [
                { 
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return '<span class="text-slate-400 fw-700 small ps-3">' + (meta.row + 1).toString().padStart(2, '0') + '</span>';
                    }
                },
                { 
                    "data": "name",
                    "render": function (data, type, row) {
                        var initial = data.charAt(0).toUpperCase();
                        var avatar_content = row.profile_pic ? '<img src="assets/uploads/' + row.profile_pic + '" class="img-fluid view_img" data-src="assets/uploads/' + row.profile_pic + '" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">' : initial;
                        return '<div class="d-flex align-items-center">' +
                                    '<div class="athlete-avatar">' + avatar_content + '</div>' +
                                    '<div>' +
                                        '<div class="fw-700 text-slate-900">' + data + '</div>' +
                                        '<div class="small text-slate-400 fw-600">ID: ' + row.member_id + '</div>' +
                                    '</div>' +
                                '</div>';
                    }
                },
                { 
                    "data": "plan",
                    "render": function (data) {
                        return '<div class="fw-700 text-slate-700"><i class="fas fa-calendar-alt me-2 text-slate-300"></i>' + data + ' Months</div>';
                    }
                },
                { 
                    "data": "package",
                    "render": function (data) {
                        return '<span class="badge bg-slate-100 text-slate-600 rounded-pill px-3 py-2 fw-700">' + data + '</span>';
                    }
                },
                { 
                    "data": "end_date",
                    "render": function (data) {
                        return '<div class="fw-600 text-slate-700">' + moment(data).format('DD MMM, YYYY') + '</div>';
                    }
                },
                { 
                    "data": "end_date",
                    "className": "text-center",
                    "render": function (data) {
                        var end = moment(data);
                        var now = moment();
                        var diff = end.diff(now, 'days');
                        var status_class = (diff > 5) ? 'status-active' : ((diff > 0) ? 'status-warning' : 'status-expired');
                        
                        // We include the class name in the text for custom filtering
                        if (diff > 0) {
                            return '<div class="validity-badge ' + status_class + '"><i class="fas fa-hourglass-half me-1"></i> ' + diff + ' days left <span class="d-none">' + status_class + '</span></div>';
                        } else {
                            return '<div class="validity-badge ' + status_class + '"><i class="fas fa-exclamation-circle me-1"></i> Expired <span class="d-none">' + status_class + '</span></div>';
                        }
                    }
                },
                { 
                    "data": "id",
                    "className": "text-end pe-4",
                    "render": function (data, type, row) {
                        return '<div class="d-flex align-items-center justify-content-end gap-2">' +
                                    '<button class="action-btn view_member" title="View Details" data-id="' + data + '" style="background: #f0fdf4; color: #166534;"><i class="fas fa-info-circle"></i></button>' +
                                    '<button class="action-btn view__member" title="Generate Receipt" data-id="' + row.member_db_id + '" style="background: #eef2ff; color: #4338ca;"><i class="fas fa-file-invoice-dollar"></i></button>' +
                                '</div>';
                    }
                }
            ],
            "pageLength": 25,
            "dom": 'f rtip',
            "stateSave": true,
            "language": {
                "search": "",
                "searchPlaceholder": "Filter records..."
            }
        });
        
        // Custom Filtering Logic
        $('#status-filter').on('change', function() {
            table.search($(this).val()).draw();
        });

        $('#sort-filter').on('change', function() {
            var colIndex = parseInt($(this).val());
            var direction = (colIndex === 1) ? 'asc' : 'desc'; // A-Z for name, latest/soonest for others
            table.order([colIndex, direction]).draw();
        });

        // Sync filters with saved state
        var state = table.state.loaded();
        if (state) {
            // Sync Status Filter
            var searchStr = state.search.search;
            if (searchStr) {
                $('#status-filter option').each(function() {
                    if ($(this).val() === searchStr) {
                        $('#status-filter').val(searchStr);
                    }
                });
            }
            
            // Sync Sort Filter
            if (state.order && state.order.length > 0) {
                var orderCol = state.order[0][0];
                $('#sort-filter').val(orderCol);
            }
        }

        $('.dataTables_filter').appendTo('#table-filter-container');
        $('.dataTables_filter').addClass('w-100');
        $('.dataTables_filter input').addClass('w-100 border bg-white px-3 shadow-sm rounded-pill').css({
            'height': '40px',
            'border-color': '#e2e8f0'
        });
	})

	$('#new_member').click(function() {
		uni_modal("<i class='fas fa-plus-circle me-2'></i>New Membership Plan", "manage_membership.php", '')
	})

	$('#send_message').click(function() {
		window.location.href = 'admin-send_expire_msg';
	})

	$('#send_message_only').click(function() {
		window.location.href = 'admin-send_msg_only';
	})

	$('#validity-table').on('click', '.view_member', function(){
		uni_modal("<i class='fas fa-id-card me-2'></i>Plan Details","view_pdetails.php?id="+$(this).attr('data-id'),'large')
	})

	$('#validity-table').on('click', '.view__member', function(){
		uni_modal("<i class='fas fa-receipt me-2'></i>Membership Receipt","view_member.php?id="+$(this).attr('data-id'),'modal-a4')
	})

    $('#validity-table').on('click', '.view_img', function(){
        viewer_modal($(this).attr('data-src'))
    })
</script>