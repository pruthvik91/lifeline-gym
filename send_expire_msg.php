<?php
include 'db_connect.php';
$wa_token = '';
global $pdoconn;
$wa_result = $pdoconn->prepare("SELECT wa_token,contact_number FROM whatsapp_token where user_id =:user_id AND status=1");
$wa_result->execute(array(':user_id' => $_SESSION['login_id']));
$wa_rows = $wa_result->fetchAll(PDO::FETCH_OBJ);
$wa_rowcount = count($wa_rows);
if ($wa_rowcount > 0) {
    foreach ($wa_rows as $wa_row) {
        $wa_token = $wa_row->wa_token;
        $contact_number = $wa_row->contact_number;
    }
}
?>
<div class="col-lg-12">
    <div class="row mb-4 mt-4">
        <div class="col-md-12">
            <!-- Date Filter Form -->
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="text" id="start_date" name="start_date" placeholder="Select Start Date" class='start_date_picker form-control'
                                value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="end_date">End Date:</label>
                        <input type="text" id="end_date" name="end_date" placeholder="Select End Date" class='end_date_picker form-control'
                            value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d'); ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
        </div>

    </form>
    </div>
</div>
<div class="row">
    <!-- FORM Panel -->

    <!-- Table Panel -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <b>Send Message</b>
                <span class="">
                    <button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="send_message">
                        <svg style="
    width: 20px;
    height: 20px;
    margin-bottom: 6px;
     filter: brightness(0%) invert(100%);
" xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 510 512.459">
                            <path fill="#111B21" d="M435.689 74.468C387.754 26.471 324 .025 256.071 0 116.098 0 2.18 113.906 2.131 253.916c-.024 44.758 11.677 88.445 33.898 126.946L0 512.459l134.617-35.311c37.087 20.238 78.85 30.891 121.345 30.903h.109c139.949 0 253.88-113.917 253.928-253.928.024-67.855-26.361-131.645-74.31-179.643v-.012zm-179.618 390.7h-.085c-37.868-.011-75.016-10.192-107.428-29.417l-7.707-4.577-79.886 20.953 21.32-77.889-5.017-7.987c-21.125-33.605-32.29-72.447-32.266-112.322.049-116.366 94.729-211.046 211.155-211.046 56.373.025 109.364 22.003 149.214 61.903 39.853 39.888 61.781 92.927 61.757 149.313-.05 116.377-94.728 211.058-211.057 211.058v.011zm115.768-158.067c-6.344-3.178-37.537-18.52-43.358-20.639-5.82-2.119-10.044-3.177-14.27 3.178-4.225 6.357-16.388 20.651-20.09 24.875-3.702 4.238-7.403 4.762-13.747 1.583-6.343-3.178-26.787-9.874-51.029-31.487-18.86-16.827-31.597-37.598-35.297-43.955-3.702-6.355-.39-9.789 2.775-12.943 2.849-2.848 6.344-7.414 9.522-11.116s4.225-6.355 6.343-10.581c2.12-4.238 1.06-7.937-.522-11.117-1.584-3.177-14.271-34.409-19.568-47.108-5.151-12.37-10.385-10.69-14.269-10.897-3.703-.183-7.927-.219-12.164-.219s-11.105 1.582-16.925 7.939c-5.82 6.354-22.209 21.709-22.209 52.927 0 31.22 22.733 61.405 25.911 65.642 3.177 4.237 44.745 68.318 108.389 95.812 15.135 6.538 26.957 10.446 36.175 13.368 15.196 4.834 29.027 4.153 39.96 2.52 12.19-1.825 37.54-15.353 42.824-30.172 5.283-14.818 5.283-27.529 3.701-30.172-1.582-2.641-5.819-4.237-12.163-7.414l.011-.024z" />
                        </svg> Send To Selected</button>
                </span>
            </div>
            <div class="card-body">

                <table class="msg-table table table-bordered table-condensed table-hover">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center"><input type="checkbox" id="select_all" style="transform: scale(1.5); /* Adjust the scale as needed */
                                                 margin: 5px;"></th> <!-- Checkbox to select all -->
                            <th class="">Member ID</th>
                            <th class="">Name</th>
                            <th class="">Plan</th>
                            <th class="">Package</th>
                            <th class="">End date</th>
                            <th class="">Status</th>
                            <th class="">Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $filter_date_end = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');
                        $filter_date_start = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d');
                        
                        // Adjust the query to use both start and end dates
                        $member =  $conn->query("SELECT r.*, p.plan, pp.package, CONCAT(m.lastname, ' ', m.firstname, ' ', m.middlename) AS name, r.member_id, m.contact as mobile_number 
                            FROM registration_info r 
                            INNER JOIN members m ON m.id = r.member_id 
                            INNER JOIN plans p ON p.id = r.plan_id 
                            INNER JOIN packages pp ON pp.id = r.package_id 
                            WHERE r.status = 1 AND DATE_FORMAT(r.end_date, '%Y-%m-%d') BETWEEN '$filter_date_start' AND '$filter_date_end' 
                            ORDER BY r.end_date ASC");

                        while ($row = $member->fetch_assoc()) :
                        ?>
                            <tr class="main-tr">
                                <td class="text-center">
                                    <input type="checkbox" class="member_checkbox" value="<?php echo $row['member_id']; ?>">
                                </td>
                                <td class="">
                                    <p><b><?php echo $row['member_id'] ?></b></p>
                                </td>
                                <td class="">
                                    <p><b><?php echo ucwords($row['name']) ?></b></p>
                                </td>
                                <td class="">
                                    <p><b><?php echo $row['plan'] . ' Months' ?></b></p>
                                </td>
                                <td class="">
                                    <p><b><?php echo $row['package'] ?></b></p>
                                </td>
                                <td class="">
                                    <p><?php echo date("d-M-Y", strtotime($row['end_date'])) ?></p>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $end_date = $row['end_date'];
                                    $days_remaining = ceil((strtotime($end_date) - time()) / (60 * 60 * 24));
                                    if ($days_remaining > 0) {
                                        echo "<span class='badge badge-success'>$days_remaining days remaining</span>";
                                    } else {
                                        echo "<span class='badge badge-danger'>Expired</span>";
                                    }
                                    ?>
                                </td>
                                <td class="iframe_<?php echo $row['member_id']; ?>">

                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Table Panel -->
</div>
</div>
<script>
    $(".end_date_picker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-m-dd',
        onSelect: function(dateText) {
            $(this).val(dateText);
        }
    });
    $(".end_date_picker").on("focus", function() {
        $(this).datepicker("show");
    });
    $(".start_date_picker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-m-dd',
        onSelect: function(dateText) {
            $(this).val(dateText);
        }
    });
    $(".start_date_picker").on("focus", function() {
        $(this).datepicker("show");
    });
    document.getElementById('select_all').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('.member_checkbox');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });

    function removeIframe(iframeId, status) {
        const iframe = document.getElementById(iframeId);
        $('.main-tr').each(function() {
            if (status == 'success') {
                $(this).find(`.${iframeId}`).html('<span class="badge badge-success">sent</span>');
            } else {
                $(this).find(`.${iframeId}`).html('<span class="badge badge-danger">Failed</span>');
            }

        });
        if (iframe) {
            iframe.parentNode.removeChild(iframe);
        }
        $('#send_message').html('Send To Selected').prop('disabled', false);
    }
    document.getElementById('send_message').addEventListener('click', function() {
        $(this).html('Sending...').prop('disabled', true);
        var selectedNumbers = [];
        var checkboxes = document.querySelectorAll('.member_checkbox:checked');
        for (var checkbox of checkboxes) {
            selectedNumbers.push(checkbox.value);
        }
        if (selectedNumbers.length > 0) {
            selectedNumbers.forEach(function(member_id) {
                var url = `send_receipt.php?id=${member_id}&wp=send`;
                var iframe = document.createElement('iframe');

                // Set iframe style to position it off-screen
                iframe.style.position = 'absolute';
                iframe.style.width = '100%';
                iframe.style.height = '100%';
                iframe.style.border = 'none';
                iframe.style.left = '-9999px';
                iframe.id = `iframe_${member_id}`;
                iframe.src = url;
                document.body.appendChild(iframe);
            });
        } else {
            alert('No members selected.');
        }
    });
</script>