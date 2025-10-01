<?php
include 'db_connect.php';
$wa_token = '';
global $pdoconn;
$wa_result = $pdoconn->prepare("SELECT wa_token,contact_number FROM whatsapp_token WHERE user_id =:user_id AND status=1");
$wa_result->execute(array(':user_id' => $_SESSION['login_id']));
$wa_rows = $wa_result->fetchAll(PDO::FETCH_OBJ);
if (count($wa_rows) > 0) {
    $wa_token = $wa_rows[0]->wa_token;
}
?>

<div class="col-lg-12">
    <div class="row mb-4 mt-4">
        <div class="col-md-12">
            <!-- Date Filter Form -->
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <label>Start Date:</label>
                        <input type="text" name="start_date" class='start_date_picker form-control'
                            value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label>End Date:</label>
                        <input type="text" name="end_date" class='end_date_picker form-control'
                            value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d'); ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Search</button>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <b>Send Message</b>
                <button class="btn btn-primary btn-sm float-right" type="button" id="send_message">
                    Send To Selected
                </button>
            </div>
            <div class="card-body">
                <table class="msg-table table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select_all"></th>
                            <th>Member ID</th>
                            <th>Name</th>
                            <th>Plan</th>
                            <th>Package</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $start = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d');
                        $end = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');
                        $members = $conn->query("
                            SELECT r.*, p.plan, pp.package, CONCAT(m.lastname,' ',m.firstname,' ',m.middlename) AS name, m.contact AS mobile_number
                            FROM registration_info r
                            INNER JOIN members m ON m.id = r.member_id
                            INNER JOIN plans p ON p.id = r.plan_id
                            INNER JOIN packages pp ON pp.id = r.package_id
                            WHERE r.status=1 AND DATE(r.end_date) BETWEEN '$start' AND '$end'
                            ORDER BY r.end_date ASC
                        ");
                        while ($row = $members->fetch_assoc()):
                        ?>
                            <tr>
                                <td><input type="checkbox" class="member_checkbox" data-mobile="<?php echo $row['mobile_number'] ?>" data-id="<?php echo $row['member_id'] ?>"></td>
                                <td><?php echo $row['member_id'] ?></td>
                                <td><?php echo ucwords($row['name']) ?></td>
                                <td><?php echo $row['plan'] . ' Months' ?></td>
                                <td><?php echo $row['package'] ?></td>
                                <td><?php echo date('d-M-Y', strtotime($row['end_date'])) ?></td>
                                <td>
                                    <?php
                                    $days_remaining = ceil((strtotime($row['end_date']) - time()) / (60 * 60 * 24));
                                    echo $days_remaining > 0 ? "<span class='badge badge-success'>$days_remaining days remaining</span>" : "<span class='badge badge-danger'>Expired</span>";
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<!-- <script src="assets/vendor/jquery/jquery.min.js"></script> -->
<!-- <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="assets/js/socket.io.min.js"></script>
<!-- <script src="assets/js/sweetalert2.js"></script> -->

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
    $(document).ready(function() {
        $("#select_all").click(function() {
            $('.member_checkbox').prop('checked', this.checked);
        });
    });

    // Utility: format phone number
    function createWhatsappPhone(number) {
        number = String(number).replace(/\D/g, '');
        if (number.length == 10) return "91" + number;
        if (number.length == 11 && number.startsWith('0')) return "91" + number.substring(1);
        return number;
    }
    const socket = io('http://localhost:3000');

    $("#testclick").click(async function() {
        let selected = $('.member_checkbox:checked');
        if (selected.length == 0) {
            alert('No members selected');
            return;
        }

        $(this).html('Sending...').prop('disabled', true);

        // collect numbers in array
        let numbers = [];
        for (let i = 0; i < selected.length; i++) {
            let mobile_number = "919638567558";
            numbers.push(mobile_number);
        }

        // emit bulk once
        socket.emit('send-bulk', {
            wa_token: '<?php echo $wa_token ?>',
            user_id: <?php echo $_SESSION['login_id']; ?>,
            numbers: numbers,
            message: `Dear member, your membership has expired. Renew today!`
        });

        // listen for progress
        socket.on("bulk-progress", function(data) {
            let row = $('.member_checkbox[data-mobile="' + data.number + '"]').closest('tr');
            if (data.status == "sent") {
                row.find('td:last').html('<span class="badge badge-success">Sent</span>');
            } else {
                row.find('td:last').html('<span class="badge badge-danger">Failed</span>');
            }
        });

        socket.once("bulk-done", function(data) {
            $("#send_message").html('Send To Selected').prop('disabled', false);
            alert("All " + data.total + " messages processed!");
        });
    });


    $("#send_message").click(async function () {
    let selected = $('.member_checkbox:checked');
    if (selected.length == 0) {
        alert('No members selected');
        return;
    }

    $(this).html('Sending...').prop('disabled', true);

    let numbers = [];
    let base64DataArray = [];

    // hidden container (off-screen, not visible to user)
    let tempContainer = document.getElementById('temp-canvas-container');
    if (!tempContainer) {
        tempContainer = document.createElement('div');
        tempContainer.id = 'temp-canvas-container';
        tempContainer.style.position = 'absolute';
        tempContainer.style.top = '-99999px';  // push far off-screen
        tempContainer.style.left = '-99999px';
        document.body.appendChild(tempContainer);
    }

    for (let i = 0; i < selected.length; i++) {
        let member_id = $(selected[i]).data('id');
        let mobile_number = createWhatsappPhone($(selected[i]).data('mobile'));
        numbers.push(mobile_number);

        const htmlContent = await $.get('send_receipt.php', { id: member_id, wp: 'send' });

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = htmlContent;
        tempContainer.appendChild(tempDiv);

        const canvas = await html2canvas(tempDiv, { useCORS: true });
        const base64Data = canvas.toDataURL('image/jpeg');

        base64DataArray.push(base64Data);

        // cleanup
        tempContainer.removeChild(tempDiv);
    }

    // emit bulk media
    socket.emit('send-bulk-media', {
        wa_token: '<?php echo $wa_token ?>',
        numbers,
        message: `Dear member, your membership has expired. Renew today!`,
        mimeType: 'image/jpeg',
        filename: 'receipt.jpg',
        base64DataArray
    });

    $(this).html('Send To Selected').prop('disabled', false);
});

    socket.off("bulk-media-progress"); // remove any old listener
    socket.on("bulk-media-progress", function(data) {
        console.log("Progress event:", data);

        let row = $('.member_checkbox').filter(function() {
            return createWhatsappPhone($(this).data('mobile')) === data.number;
        }).closest('tr');

        if (row.length) {
            if (data.status == "sent") {
                row.find('td:last').html('<span class="badge badge-success">Sent</span>');
            } else {
                row.find('td:last').html('<span class="badge badge-danger">Failed</span>');
            }
        }
    });

    // all done listener
    socket.off("bulk-media-done");
    socket.on("bulk-media-done", function(data) {
        $("#send_message").html('Send To Selected').prop('disabled', false);
        Toast.fire({
            icon: 'success',
            title: "All " + data.total + " messages processed!"
        });
    });
</script>