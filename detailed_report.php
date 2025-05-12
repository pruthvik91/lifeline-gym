<?php
// detailed_report.php
$conn = new mysqli("localhost", "root", "root", "gym_db");

// Use POST instead of GET
$start = $_POST['start'] ?? date('Y-m-01');
$end = $_POST['end'] ?? date('Y-m-t');
$selectedGroups = $_POST['groups'] ?? [];
$searchKeyword = strtolower(trim($_POST['search'] ?? ''));

// Fetch unique groups for dropdown
$groupQuery = "
    SELECT DISTINCT TRIM(LOWER(description)) COLLATE utf8mb4_general_ci AS group_key FROM transactions
    UNION
    SELECT DISTINCT TRIM(LOWER(remarks)) COLLATE utf8mb4_general_ci AS group_key FROM payments
";
$groupResult = $conn->query($groupQuery);
$groupOptions = [];
while ($row = $groupResult->fetch_assoc()) {
    $groupOptions[] = $row['group_key'];
}

$grouped = [];
$totalIncome = 0;
$totalExpense = 0;
$conditions = "created_at BETWEEN '$start' AND '$end'";

$allEntries = [];

// Fetch transactions
$sql1 = "SELECT id, 'expense' as type, amount, TRIM(LOWER(description)) AS group_key, description, created_at
         FROM transactions
         WHERE $conditions";
$result1 = $conn->query($sql1);
while ($row = $result1->fetch_assoc()) {
    if (!empty($selectedGroups) && !in_array($row['group_key'], $selectedGroups)) continue;
    if ($searchKeyword && strpos(strtolower($row['description']), $searchKeyword) === false) continue;

    $grouped['expense'][$row['group_key']]['items'][] = $row;
    $grouped['expense'][$row['group_key']]['total'] = ($grouped['expense'][$row['group_key']]['total'] ?? 0) + $row['amount'];
    $totalExpense += $row['amount'];

    $allEntries[] = $row;
}

$sql2 = "SELECT id, 'income' as type, amount, TRIM(LOWER(remarks)) AS group_key, remarks as description, date_created AS created_at
         FROM payments
         WHERE date_created BETWEEN '$start' AND '$end'";
$result2 = $conn->query($sql2);
while ($row = $result2->fetch_assoc()) {
    if (!empty($selectedGroups) && !in_array($row['group_key'], $selectedGroups)) continue;
    if ($searchKeyword && strpos(strtolower($row['description']), $searchKeyword) === false) continue;

    $grouped['income'][$row['group_key']]['items'][] = $row;
    $grouped['income'][$row['group_key']]['total'] = ($grouped['income'][$row['group_key']]['total'] ?? 0) + $row['amount'];
    $totalIncome += $row['amount'];

    $allEntries[] = $row;
}

usort($allEntries, function ($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>

<!DOCTYPE html>
<html>

<head>
    <title>Detailed Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
    .sidebar-list{
        margin-top:10% !important;
    }
    a{
        text-decoration:none;
    }
</style>
</head>

<body class="container py-4">
    <h3>Detailed Income & Expense Report</h3>

    <form method="post" action="" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>From Date:</label>
            <input type="date" name="start" class="form-control" value="<?= $start ?>">
        </div>
        <div class="col-md-3">
            <label>To Date:</label>
            <input type="date" name="end" class="form-control" value="<?= $end ?>">
        </div>
        <div class="col-md-3">
            <label>Select Group(s):</label>
            <select name="groups[]" class="form-control" multiple>
                <?php foreach ($groupOptions as $option): ?>
                    <option value="<?= $option ?>" <?= in_array($option, $selectedGroups) ? 'selected' : '' ?>>
                        <?= ucfirst($option) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label>Search Description:</label>
            <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($searchKeyword) ?>">
        </div>
        <div class="col-md-12 d-flex justify-content-end">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <div class="alert alert-info">
        <strong>Total Income:</strong> ₹<?= number_format($totalIncome, 2) ?> &nbsp;
        <strong>Total Expense:</strong> ₹<?= number_format($totalExpense, 2) ?> &nbsp;
        <strong>Net:</strong> ₹<?= number_format($totalIncome - $totalExpense, 2) ?>
    </div>

    <?php foreach (["income", "expense"] as $type): ?>
        <?php if (!empty($grouped[$type])): ?>
            <h5 class="mt-4 text-<?= $type === 'income' ? 'success' : 'danger' ?>"><?= ucfirst($type) ?> Groups</h5>
            <div class="accordion mb-4" id="<?= $type ?>Accordion">
                <?php $i = 0;
                foreach ($grouped[$type] as $key => $data): $accordionId = $type . $i++; ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= $accordionId ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $accordionId ?>" aria-expanded="false" aria-controls="collapse<?= $accordionId ?>">
                                <?= ucfirst($key) ?> — ₹<?= number_format($data['total'], 2) ?>
                            </button>
                        </h2>
                        <div id="collapse<?= $accordionId ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $accordionId ?>" data-bs-parent="#<?= $type ?>Accordion">
                            <div class="accordion-body p-0">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Description</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['items'] as $item): ?>
                                            <tr>
                                                <td><?= ucfirst($item['type']) ?></td>
                                                <td>₹<?= number_format($item['amount'], 2) ?></td>
                                                <td><?= $item['description'] ?></td>
                                                <td><?= date('d M Y', strtotime($item['created_at'])) ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

</body>

</html>