<?php include('db_connect.php'); ?>

<div class="container-fluid py-2">
    <!-- Header Section -->
    <div class="d-flex align-items-end justify-content-between mb-5">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-slate-400 fw-600 small">Dashboard</a></li>
                    <li class="breadcrumb-item active text-slate-900 fw-600 small" aria-current="page">Income & Expenses</li>
                </ol>
            </nav>
            <h2 class="fw-800 text-slate-900 mb-0" style="letter-spacing: -0.5px;">Financial Manager</h2>
        </div>
        <a href="admin-detailed_report" class="btn btn-primary shadow-premium px-4 py-2">
            <i class="fas fa-file-invoice-dollar me-2"></i> Detailed Report
        </a>
    </div>

    <!-- Summary Widgets -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="bg-white rounded-4 shadow-soft p-4 border border-slate-100 h-100 border-start border-4 border-success">
                <h6 class="text-slate-500 fw-700 small text-uppercase mb-1">Total Income</h6>
                <div class="h3 fw-800 text-slate-900 mb-0">₹<span id="totalIncome">0</span></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white rounded-4 shadow-soft p-4 border border-slate-100 h-100 border-start border-4 border-danger">
                <h6 class="text-slate-500 fw-700 small text-uppercase mb-1">Total Expense</h6>
                <div class="h3 fw-800 text-slate-900 mb-0">₹<span id="totalExpense">0</span></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white rounded-4 shadow-soft p-4 border border-slate-100 h-100 border-start border-4 border-primary">
                <h6 class="text-slate-500 fw-700 small text-uppercase mb-1">Net Balance</h6>
                <div class="h3 fw-800 text-slate-900 mb-0">₹<span id="balance">0</span></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Entry Form -->
        <div class="col-lg-4">
            <div class="bg-white rounded-4 shadow-soft p-4 border border-slate-100">
                <h6 class="fw-800 text-slate-900 mb-4 pb-2 border-bottom">New Transaction</h6>
                <form id="entryForm">
                    <div class="mb-3">
                        <label class="form-label small fw-700 text-slate-600">Date</label>
                        <input type="date" class="form-control" id="created_at" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-700 text-slate-600">Transaction Type</label>
                        <select class="form-control" id="type" required>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-700 text-slate-600">Amount (₹)</label>
                        <input type="number" class="form-control" id="amount" placeholder="0.00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-700 text-slate-600">Description</label>
                        <textarea class="form-control" id="description" rows="2" placeholder="What was this for?" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 mt-2">
                        <i class="fas fa-plus-circle me-2"></i> Add Transaction
                    </button>
                </form>
            </div>
        </div>

        <!-- Records Table -->
        <div class="col-lg-8">
            <div class="bg-white rounded-4 shadow-soft overflow-hidden border border-slate-100">
                <div class="p-3 border-bottom border-slate-100 bg-slate-50">
                    <h6 class="mb-0 fw-700 text-slate-700">Recent Transactions</h6>
                </div>
                <div class="table-responsive">
                    <table id="recordsTable" class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Type</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .badge-income { background: #dcfce7; color: #166534; }
    .badge-expense { background: #fee2e2; color: #991b1b; }
    
    .icon-btn-sm {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }
</style>

<script>
    let table;
    let data = [];

    $(document).ready(function() {
        $('#created_at').val(new Date().toISOString().slice(0, 10));

        table = $('#recordsTable').DataTable({
            "dom": 'rtip',
            "pageLength": 10,
            "autoWidth": false,
            "language": {
                "emptyTable": "No transactions found"
            }
        });
        
        fetchData();

        $('#entryForm').on('submit', function(e) {
            e.preventDefault();
            const type = $('#type').val();
            const amount = $('#amount').val();
            const description = $('#description').val();
            const created_at = $('#created_at').val();

            $.ajax({
                url: 'ajaxcall.php',
                type: 'POST',
                data: { action: 'add', type, amount, description, created_at },
                success: function(res) {
                    res = JSON.parse(res);
                    if (res.status === 'OK') {
                        $('#entryForm')[0].reset();
                        $('#created_at').val(new Date().toISOString().slice(0, 10));
                        alert_toast("Transaction added successfully", 'success');
                        fetchData();
                    }
                }
            });
        });

        $('#recordsTable tbody').on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            _conf("Are you sure to delete this transaction?", "delete_transaction", [id]);
        });

        $('#recordsTable tbody').on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            const entry = data.find(x => x.id == id);
            
            // Re-using the logic from the old file but with uni_modal pattern if possible
            // For now, I'll keep the existing logic but style the modal in a premium way
            $('#editId').val(entry.id);
            $('#editAmount').val(entry.amount);
            $('#editDescription').val(entry.description);
            $('#editModal').modal('show');
        });
    });

    function delete_transaction(id) {
        $.ajax({
            url: 'ajaxcall.php',
            type: 'POST',
            data: { action: 'delete', id },
            success: function(res) {
                res = JSON.parse(res);
                if (res.status === 'OK') {
                    alert_toast("Transaction deleted", 'success');
                    fetchData();
                }
            }
        });
    }

    function fetchData() {
        $.ajax({
            url: 'ajaxcall.php',
            type: 'POST',
            data: { action: 'get' },
            success: function(res) {
                res = JSON.parse(res);
                if (res.status !== 'OK') return;

                data = res.data;
                table.clear();

                let income = 0, expense = 0;

                data.forEach(entry => {
                    const amt = parseFloat(entry.amount);
                    if (entry.type === 'income') income += amt;
                    else expense += amt;

                    const typeBadge = entry.type === 'income' ? 
                        '<span class="badge badge-income text-uppercase">Income</span>' : 
                        '<span class="badge badge-expense text-uppercase">Expense</span>';

                    table.row.add([
                        typeBadge,
                        '<span class="fw-800 text-slate-900">₹' + amt.toLocaleString() + '</span>',
                        '<span class="text-slate-500 small fw-600">' + entry.description + '</span>',
                        '<span class="text-slate-400 small fw-700">' + moment(entry.created_at).format('DD MMM, YYYY') + '</span>',
                        '<div class="text-end pe-3 d-flex justify-content-end gap-2">' +
                            '<button class="icon-btn-premium icon-btn-edit edit-btn" data-id="' + entry.id + '"><i class="fas fa-edit"></i></button>' +
                            '<button class="icon-btn-premium icon-btn-delete delete-btn" data-id="' + entry.id + '"><i class="fas fa-trash"></i></button>' +
                        '</div>'
                    ]);
                });
                table.draw();

                $('#totalIncome').text(income.toLocaleString());
                $('#totalExpense').text(expense.toLocaleString());
                $('#balance').text((income - expense).toLocaleString());
            }
        });
    }
</script>

<!-- Premium Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="editForm" class="modal-content border-0 shadow-premium rounded-4">
            <div class="modal-header border-bottom border-slate-100 bg-slate-50 p-4">
                <h5 class="modal-title fw-800 text-slate-900">Edit Transaction</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="editId">
                <div class="mb-3">
                    <label class="form-label small fw-700 text-slate-600">Amount (₹)</label>
                    <input type="number" class="form-control" id="editAmount" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-700 text-slate-600">Description</label>
                    <textarea class="form-control" id="editDescription" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer border-top-0 p-4">
                <button type="button" class="btn btn-light px-4" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4 shadow-sm">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#editId').val();
        const amount = $('#editAmount').val();
        const description = $('#editDescription').val();
        const created_at = $('#created_at').val();

        $.ajax({
            url: 'ajaxcall.php',
            type: 'POST',
            data: { action: 'update', id, amount, description, created_at },
            success: function(res) {
                res = JSON.parse(res);
                if (res.status === 'OK') {
                    $('#editModal').modal('hide');
                    alert_toast("Transaction updated", 'success');
                    fetchData();
                }
            }
        });
    });
</script>