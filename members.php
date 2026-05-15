<?php include('db_connect.php'); ?>

<div class="container-fluid py-2">
    <!-- Header Section -->
    <div class="d-flex align-items-end justify-content-between mb-4 px-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="admin-home" class="text-decoration-none text-slate-400 fw-600 small">Dashboard</a></li>
                    <li class="breadcrumb-item active text-slate-500 fw-600 small" aria-current="page">Members</li>
                </ol>
            </nav>
            <h2 class="fw-800 text-slate-900 mb-0">Members Directory</h2>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success shadow-soft px-4 d-none" type="button" id="export_active">
                <i class="fas fa-user-check me-2"></i> <span>Export Active</span>
            </button>
            <button class="btn btn-outline-primary shadow-soft px-4 d-none" type="button" id="export_members">
                <i class="fas fa-file-export me-2"></i> <span>Export All</span>
            </button>
            <button class="btn btn-primary shadow-premium px-4" type="button" id="new_member">
                <i class="fas fa-user-plus me-2"></i> <span>Add Member</span>
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
        <div class="p-3 p-md-4 border-bottom bg-white d-flex flex-wrap gap-3 align-items-center justify-content-between">
            <h6 class="mb-0 fw-800 text-slate-800"><i class="fas fa-users me-2 text-primary"></i> All Registered Members</h6>
            <div id="table-search-container" class="flex-grow-1" style="max-width: 400px;"></div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle" id="member-table">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 80px;">No.</th>
                        <th>Member</th>
                        <th>Contact</th>
                        <th>Session</th>
                        <th class="text-end pe-4" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via Ajax for high performance -->
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
        margin-right: 12px;
        font-size: 0.9rem;
    }

    .badge-custom {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
    }

    .badge-morning { background: #fffbeb; color: #d97706; }
    .badge-evening { background: #eef2ff; color: #4f46e5; }


    .athlete-row:hover {
        background-color: #fafafa !important;
    }

    .athlete-row:hover {
        background-color: #fafafa !important;
    }
</style>

<script>
    $(document).ready(function() {
        var table = $('#member-table').DataTable({
            "processing": true,
            "serverSide": false,
            "autoWidth": false,
            "ajax": "ajax.php?action=get_members",
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
                        return '<div class="d-flex align-items-center">' +
                                    '<div class="member-avatar">' + initial + '</div>' +
                                    '<div>' +
                                        '<div class="fw-700 text-slate-900">' + data + '</div>' +
                                        '<div class="small text-slate-400 fw-600">#' + row.member_id + '</div>' +
                                    '</div>' +
                                '</div>';
                    }
                },
                { 
                    "data": "contact",
                    "render": function (data) {
                        return '<span class="text-slate-600 fw-600 small"><i class="fas fa-phone-alt me-2 text-slate-200"></i>' + data + '</span>';
                    }
                },
                { 
                    "data": "batch",
                    "render": function (data) {
                        if (data == 'Morning') {
                            return '<span class="badge-custom badge-morning"><i class="fas fa-sun me-1"></i> Morning</span>';
                        } else {
                            return '<span class="badge-custom badge-evening"><i class="fas fa-moon me-1"></i> Evening</span>';
                        }
                    }
                },
                { 
                    "data": "id",
                    "className": "text-end pe-4",
                    "render": function (data) {
                        return '<div class="d-flex align-items-center justify-content-end gap-2">' +
                                    '<button class="icon-btn-premium icon-btn-view view__member" title="View" data-id="' + data + '"><i class="fas fa-eye"></i></button>' +
                                    '<button class="icon-btn-premium icon-btn-edit edit_member" title="Edit" data-id="' + data + '"><i class="fas fa-edit"></i></button>' +
                                    '<button class="icon-btn-premium icon-btn-delete delete_member" title="Delete" data-id="' + data + '"><i class="fas fa-trash-alt"></i></button>' +
                                 '</div>';
                    }
                }
            ],
            "pageLength": 25,
            "dom": 'f rtip',
            "language": {
                "search": "",
                "searchPlaceholder": "Search by name, ID or contact...",
                "processing": '<i class="fas fa-spinner fa-spin me-2"></i> Syncing records...'
            },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).addClass('athlete-row');
            }
        });
        
        // Move search box to custom container
        $('.dataTables_filter').appendTo('#table-search-container');
        $('.dataTables_filter').addClass('w-100');
        $('.dataTables_filter input').addClass('form-control border bg-white px-3 fw-600 text-slate-600 rounded-pill shadow-sm').css({
            'height': '40px',
            'border-color': '#e2e8f0'
        });

        // Re-attach event listeners after Ajax load
        $('#member-table').on('click', '.view__member', function() {
            uni_modal("<i class='fas fa-id-card me-2'></i>Member Profile", "view_member_detail.php?id=" + $(this).attr('data-id'), 'large')
        });

        $('#member-table').on('click', '.edit_member', function() {
            uni_modal("<i class='fas fa-edit me-2'></i>Update Information", "manage_member.php?id=" + $(this).attr('data-id'), 'mid-large')
        });

        $('#member-table').on('click', '.delete_member', function() {
            _conf("Are you sure you want to delete this member? This action is permanent.", "delete_member", [$(this).attr('data-id')])
        });
    });

    $('#new_member').click(function() {
        uni_modal("<i class='fas fa-user-plus me-2'></i>Add New Member", "manage_member.php", 'mid-large')
    })

    $('#export_active').click(function() {
        start_load();
        $.ajax({
            url: 'ajax.php?action=get_registered_members',
            method: 'GET',
            success: function(resp) {
                var data = JSON.parse(resp).data;
                var csvContent = "data:text/csv;charset=utf-8,";
                csvContent += "Name,Member ID,Mobile Number,Login Link,Plan,Package\n";
                
                data.forEach(function(row) {
                    var login_url = "https://lifelinefitnessstudio.com/login.php?mid=" + row.member_id + "&phn=" + row.contact;
                    var name = row.name.replace(/,/g, "");
                    csvContent += name + "," + row.member_id + "," + row.contact + "," + login_url + "," + row.plan + "," + row.package + "\n";
                });
                
                var encodedUri = encodeURI(csvContent);
                var link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "lifeline_active_members.csv");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                end_load();
                alert_toast("Active members list exported successfully", "success");
            }
        });
    });

    $('#export_members').click(function() {
        start_load();
        $.ajax({
            url: 'ajax.php?action=get_members',
            method: 'GET',
            success: function(resp) {
                var data = JSON.parse(resp).data;
                var csvContent = "data:text/csv;charset=utf-8,";
                csvContent += "Name,Member ID,Mobile Number,Login Link\n";
                
                data.forEach(function(row) {
                    var login_url = "https://lifelinefitnessstudio.com/login.php?mid=" + row.member_id + "&phn=" + row.contact;
                    var name = row.name.replace(/,/g, ""); // Remove commas to avoid CSV breakage
                    csvContent += name + "," + row.member_id + "," + row.contact + "," + login_url + "\n";
                });
                
                var encodedUri = encodeURI(csvContent);
                var link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "lifeline_members_list.csv");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                end_load();
                alert_toast("Members list exported successfully", "success");
            }
        });
    });

    function delete_member($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_member',
            method: 'POST',
            data: { id: $id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Member removed successfully", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1000)
                }
            }
        })
    }
</script>
