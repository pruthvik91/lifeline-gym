<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Income & Expense Manager</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
         .sidebar-list{
        margin-top:13% !important;
    }
        .expense-body {
            padding: 20px;
            background-color: #f9f9f9;
        }

        .summary-box {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .summary-box h4 {
            margin: 0;
        }

        .form-section {
            margin-bottom: 30px;
        }
    </style>
</head>

<body class="expense-body">


    <div class="container">
        <div class="summary-box row text-center">
            <div class="col-md-4">
                <h4>Total Income: ₹<span id="totalIncome">0</span></h4>
            </div>
            <div class="col-md-4">
                <h4>Total Expense: ₹<span id="totalExpense">0</span></h4>
            </div>
            <div class="col-md-4">
                <h4>Balance: ₹<span id="balance">0</span></h4>
            </div>
        </div>


        <div class="form-section card p-4">
            <form id="entryForm">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Date</label>
                        <input type="date" class="form-control" id="created_at" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Type</label>
                        <select class="form-control" id="type" required>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Amount (₹)</label>
                        <input type="number" class="form-control" id="amount" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Description</label>
                        <input type="text" class="form-control" id="description" required>
                    </div>
                </div>
                <div class="d-flex">
                <button type="submit" class="btn btn-primary">Add Entry</button>
                <div class="text-end mx-2"><a href="index.php?page=detailed_report" class="btn btn-secondary">View Detailed Report</a></div>
                </div>
            </form>
        </div>

        <table id="recordsTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let table;
        let data = [];

        $(document).ready(function() {
            $('#created_at').val(new Date().toISOString().slice(0, 10));

            table = $('#recordsTable').DataTable();
            fetchData();
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));

            $('#entryForm').on('submit', function(e) {
                e.preventDefault();
                const type = $('#type').val();
                const amount = $('#amount').val();
                const description = $('#description').val();
                const created_at = $('#created_at').val();

                $.ajax({
                    url: 'ajaxcall.php',
                    type: 'POST',
                    data: {
                        action: 'add',
                        type,
                        amount,
                        description,
                        created_at
                    },
                    success: function(res) {
                        res = JSON.parse(res);
                        if (res.status === 'OK') {
                            $('#entryForm')[0].reset();
                            fetchData();
                        } else {
                            alert("Error adding transaction");
                        }
                    }
                });
            });

            $('#recordsTable tbody').on('click', '.delete-btn', function() {
                const id = $(this).data('id');

                $.ajax({
                    url: 'ajaxcall.php',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        id
                    },
                    success: function(res) {
                        res = JSON.parse(res);
                        if (res.status === 'OK') {
                            fetchData();
                        } else {
                            alert("Error deleting transaction");
                        }
                    }
                });
            });

            $('#recordsTable tbody').on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const entry = data.find(x => x.id == id);

                $('#editId').val(entry.id);
                $('#editAmount').val(entry.amount);
                $('#editDescription').val(entry.description);
                editModal.show();
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                const id = $('#editId').val();
                const amount = $('#editAmount').val();
                const description = $('#editDescription').val();
                const created_at = $('#created_at').val();

                $.ajax({
                    url: 'ajaxcall.php',
                    type: 'POST',
                    data: {
                        action: 'update',
                        id,
                        amount,
                        description,
                        created_at
                    },
                    success: function(res) {
                        res = JSON.parse(res);
                        if (res.status === 'OK') {
                            editModal.hide();
                            fetchData();
                        } else {
                            alert("Error updating transaction");
                        }
                    }
                });
            });
        });


        function fetchData() {
            $.ajax({
                url: 'ajaxcall.php',
                type: 'POST',
                data: {
                    action: 'get'
                },
                success: function(res) {
                    res = JSON.parse(res);
                    if (res.status !== 'OK') {
                        alert("Error fetching data");
                        return;
                    }

                    data = res.data;
                    table.clear().draw();

                    let income = 0,
                        expense = 0;

                    data.forEach(entry => {
                        const amt = parseFloat(entry.amount);
                        if (entry.type === 'income') income += amt;
                        else expense += amt;

                        table.row.add([
                            entry.type,
                            `₹${amt}`,
                            entry.description,
                            entry.created_at,
                            `
                        <button class="btn btn-warning btn-sm edit-btn" data-id="${entry.id}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${entry.id}">Delete</button>
                        `
                        ]).draw();
                    });

                    $('#totalIncome').text(income);
                    $('#totalExpense').text(expense);
                    $('#balance').text(income - expense);
                }
            });
        }
    </script>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Transaction</h5>

                </div>
                <div class="modal-body">
                    <input type="hidden" id="editId">
                    <div class="mb-3">
                        <label for="editAmount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="editAmount" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <input type="text" class="form-control" id="editDescription" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>