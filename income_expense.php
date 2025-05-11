<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income & Expense Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> -->

    <style>
        .card-body {
            padding: 20px;
        }

        .card-header {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .summary-box {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .group-card {
            margin-bottom: 15px;
            cursor: pointer;
        }

        .record-details {
            display: none;
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .group-summary {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .group-records {
            margin-top: 15px;
        }

        .record-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .record-item:last-child {
            border-bottom: none;
        }

        .table th,
        .table td {
            text-align: center;
        }
    </style>
</head>

<body class="container p-4">

    <!-- Summary Box -->
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

    <!-- Entry Form -->
    <div class="card p-4 mb-4">
        <div class="card-header bg-primary text-white">
            Add Entry
        </div>
        <div class="card-body">
            <form id="entryForm">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="monthSelect">Select Month</label>
                        <input type="month" id="monthSelect" class="form-control" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" required>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="amount">Amount (₹)</label>
                        <input type="number" class="form-control" id="amount" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Entry</button>
            </form>
        </div>
    </div>

    <!-- Group Summary Cards -->
    <div id="groupSummaryCards">
        <!-- Group Cards will be added here dynamically -->
    </div>

    <!-- Transaction Table -->
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            Monthly Income & Expense Report
        </div>
        <div class="card-body">
            <table id="recordsTable" class="table table-striped table-bordered">
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
    </div>

    <script>
        let table;
        let data = [];

        $(document).ready(function() {
            table = $('#recordsTable').DataTable();
            const today = new Date();
            const currentMonth = today.toISOString().slice(0, 7); // "YYYY-MM"
            $('#monthSelect').val(currentMonth);
            fetchData(); // Load data for current month on page load

            $('#entryForm').on('submit', function(e) {
                e.preventDefault();
                const type = $('#type').val();
                const amount = $('#amount').val();
                const description = $('#description').val();
                const selectedMonth = $('#monthSelect').val();
                if (!selectedMonth) return;

                const [year, month] = selectedMonth.split('-');
                const start = `${year}-${month}-01`;
                const end = new Date(year, month, 0).toISOString().split('T')[0]; // last day of month

                $.ajax({
                    url: 'ajaxcall.php',
                    type: 'POST',
                    data: {
                        action: 'add',
                        type,
                        amount,
                        description,
                        start,
                        end
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
        });

        function fetchData() {
            const selectedMonth = $('#monthSelect').val(); // Get the selected month
            if (!selectedMonth) return;

            const [year, month] = selectedMonth.split('-');
            const start = `${year}-${month}-01`;
            const end = new Date(year, month, 0).toISOString().split('T')[0]; // last day of month

            $.ajax({
                url: 'ajaxcall.php',
                type: 'POST',
                data: {
                    action: 'get',
                    start,
                    end
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

                    // Group by description
                    const groups = {};
                    data.forEach(entry => {
                        const key = entry.description.trim().toLowerCase();
                        if (!groups[key]) groups[key] = [];
                        groups[key].push(entry);
                    });

                    // Generate group summary cards
                    const $groupSummaryCards = $('#groupSummaryCards').empty();
                    Object.entries(groups).forEach(([desc, entries], idx) => {
                        let groupTotal = 0;
                        entries.forEach(entry => groupTotal += parseFloat(entry.amount));

                        const groupCard = `
                            <div class="card group-card" data-group-id="${idx}">
                                <div class="card-header">
                                <span class="group-summary">${desc} (₹${groupTotal.toLocaleString()})</span>
                                </div>
                                <div class="card-body">
                                <button class="btn btn-link show-records-btn" data-bs-toggle="collapse" data-bs-target="#group${idx}Records">
                                    View Records
                                </button>
                                <div id="group${idx}Records" class="collapse record-details">
                                    <div class="group-records">
                                    ${entries.map(entry => `
                                        <div class="record-item">
                                        <strong>${entry.type.charAt(0).toUpperCase() + entry.type.slice(1)}:</strong>
                                        ₹${parseFloat(entry.amount).toLocaleString()} - ${new Date(entry.created_at).toLocaleDateString()}
                                        <br> Description: ${entry.description}
                                        </div>
                                    `).join('')}
                                    </div>
                                </div>
                                </div>
                            </div>
                            `;

                        $groupSummaryCards.append(groupCard);
                    });

                    // Add data to the table
                    data.forEach(entry => {
                        const amt = parseFloat(entry.amount);
                        if (entry.type === 'income') income += amt;
                        else expense += amt;

                        table.row.add([
                            entry.type.charAt(0).toUpperCase() + entry.type.slice(1),
                            `₹${amt.toLocaleString()}`,
                            entry.description,
                            new Date(entry.created_at).toLocaleDateString(),
                            `
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${entry.id}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${entry.id}">Delete</button>
                            `
                        ]).draw();
                    });

                    // Update totals
                    $('#totalIncome').text(income.toLocaleString());
                    $('#totalExpense').text(expense.toLocaleString());
                    $('#balance').text((income - expense).toLocaleString());
                }
            });
        }
    </script>

</body>

</html>