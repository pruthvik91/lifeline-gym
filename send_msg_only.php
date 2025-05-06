<?php
include 'db_connect.php';
require 'header.php';
$wa_token = '';
global $pdoconn;

// Fetch WhatsApp token and contact number
$wa_result = $pdoconn->prepare("
    SELECT wa_token, contact_number 
    FROM whatsapp_token 
    WHERE user_id = :user_id AND status = 1
");
$wa_result->execute(array(':user_id' => $_SESSION['login_id']));
$wa_rows = $wa_result->fetchAll(PDO::FETCH_OBJ);

if (count($wa_rows) > 0) {
    foreach ($wa_rows as $wa_row) {
        $wa_token = $wa_row->wa_token;
    }
}

// Fetch members' contacts
$member_query = $conn->query("
    SELECT m.contact 
    FROM registration_info r 
    INNER JOIN members m ON m.id = r.member_id 
    INNER JOIN plans p ON p.id = r.plan_id 
    INNER JOIN packages pp ON pp.id = r.package_id 
    WHERE r.status = 1 
    ORDER BY r.id DESC 
");

$contacts = [];
while ($row = $member_query->fetch_assoc()) {
    $contacts[] = $row['contact'];
}
?>

<script src="assets/js/socket.io.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="col-lg-12">
    <div class="row mb-4 mt-4">
        <div class="col-md-12">
            <!-- Message Form -->
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Message">Message</label>
                            <textarea name="bulk_message" id="bulk_message" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <button type="button" id="whatsapp_send" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#whatsapp_send').on('click', function() {
            const message = $('#bulk_message').val().trim();
            if (!message) {
                alert('Please enter a message to send.');
                return;
            }

            const socket = io('http://localhost:3000');
            let connectionAttempts = 0;
            const maxRetries = 3;

            const wa_token = '<?php echo $wa_token; ?>';
            var contacts = <?php echo json_encode($contacts); ?>;
            socket.on('connect', function() {
                console.log('Connected to the Socket server.');
                
                contacts.forEach(contact => {
                    contact = createWhatsappPhone(contact);
                    socket.emit('send-message', {
                        wa_token: wa_token,
                        number: contact,
                        message: message
                    });
                });
            });
            socket.on('connect_error', function(error) {
                connectionAttempts++;
                if (connectionAttempts >= maxRetries) {
                    console.error('Max connection attempts reached. Stopping Socket.');
                    alert('Server disconnected. Please try again later.');
                    socket.disconnect();
                }
            });

            socket.on('userLogout', function(userLogout) {
                if (userLogout.code === 401) {
                    console.error('WhatsApp session expired.');
                    authenticateWhatsappSession(userLogout.wa_token);
                } else {
                    console.error(userLogout.message);
                }
            });

            function authenticateWhatsappSession(token) {
                $.ajax({
                    type: "POST",
                    url: "ajaxcall.php",
                    dataType: 'json',
                    data: {
                        action: 'authenticateWhatsappSession',
                        user_id: <?php echo $_SESSION["login_id"]; ?>,
                        wa_token: token,
                        status: 0
                    },
                    success: function(response) {
                        if (response.status === 'OK') {
                            alert('Please log in to WhatsApp again.');
                        } else {
                            console.error('Failed to authenticate WhatsApp session.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error during authentication:', error);
                    }
                });
            }
        });
    });
    function createWhatsappPhone(number) {
  if (typeof number !== 'string') {
    number = String(number); // Ensure the input is a string
  }

  // Remove unwanted characters
  number = number.replace(/[+/\s-]/g, "");

  // Validate the number contains only digits
  if (!/^\d+$/.test(number)) {
    return false; // Invalid if non-numeric characters remain
  }

  // Ensure the number has a valid length
  if (number.length < 10) {
    return false; // Too short to be valid
  }

  // Handle specific formats
  if (number.startsWith("91") && number.length === 12) {
    return number; // Already in correct format
  } else if (number.startsWith("0") && number.length === 11) {
    return "91" + number.substring(1); // Convert "0XXXXXXXXX" to "91XXXXXXXXXX"
  } else if (number.length === 10) {
    return "91" + number; // Assume it's a local number without prefix
  }

  // Return the number as-is if it's valid but doesn't match specific cases
  return number.length >= 12 ? number : false;
}

</script>
