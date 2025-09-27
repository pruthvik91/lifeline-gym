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
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Message Type</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="message_type" id="text_only" value="text" checked>
                                <label class="form-check-label" for="text_only">
                                    Text Message Only
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="message_type" id="media_with_text" value="media">
                                <label class="form-check-label" for="media_with_text">
                                    Media with Text
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Message">Message</label>
                            <textarea name="bulk_message" id="bulk_message" class="form-control" placeholder="Enter your message here..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="row" id="media_section" style="display: none;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="media_file">Select Media File</label>
                            <input type="file" class="form-control-file" id="media_file" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                            <small class="form-text text-muted">Supported formats: Images, Videos, Audio, PDF, Documents</small>
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
        // Toggle media section based on message type selection
        $('input[name="message_type"]').on('change', function() {
            if ($(this).val() === 'media') {
                $('#media_section').show();
                $('#media_file').prop('required', true);
            } else {
                $('#media_section').hide();
                $('#media_file').prop('required', false);
            }
        });

        // Function to convert file to base64
        function fileToBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }

        // Function to get MIME type
        function getMimeType(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            const mimeTypes = {
                'jpg': 'image/jpeg',
                'jpeg': 'image/jpeg',
                'png': 'image/png',
                'gif': 'image/gif',
                'webp': 'image/webp',
                'mp4': 'video/mp4',
                'avi': 'video/avi',
                'mov': 'video/quicktime',
                'wmv': 'video/x-ms-wmv',
                'mp3': 'audio/mpeg',
                'wav': 'audio/wav',
                'ogg': 'audio/ogg',
                'pdf': 'application/pdf',
                'doc': 'application/msword',
                'docx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls': 'application/vnd.ms-excel',
                'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'ppt': 'application/vnd.ms-powerpoint',
                'pptx': 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
            };
            return mimeTypes[ext] || 'application/octet-stream';
        }

        let isSending = false;
        let messagesSent = 0;
        let totalContacts = 0;

        $('#whatsapp_send').on('click', function() {
            if (isSending) {
                alert('Messages are already being sent. Please wait...');
                return;
            }

            const message = $('#bulk_message').val().trim();
            const messageType = $('input[name="message_type"]:checked').val();
            const mediaFile = $('#media_file')[0].files[0];

            // Validation
            if (!message) {
                alert('Please enter a message to send.');
                return;
            }

            if (messageType === 'media' && !mediaFile) {
                alert('Please select a media file.');
                return;
            }

            // Reset counters and set sending state
            isSending = true;
            messagesSent = 0;
            totalContacts = 0;

            // Disable the send button
            $('#whatsapp_send').prop('disabled', true).text('Sending...');

            const socket = io('http://localhost:3000');
            let connectionAttempts = 0;
            const maxRetries = 3;

            const wa_token = '<?php echo $wa_token; ?>';
            var contacts = <?php echo json_encode($contacts); ?>;
            console.log(contacts);

            // Generate unique batch ID to prevent duplicates
            const batchId = 'bulk_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

            // Set a timeout to prevent hanging
            const sendTimeout = setTimeout(() => {
                if (isSending) {
                    console.log('Send timeout reached, resetting state');
                    isSending = false;
                    socket.disconnect();
                    alert('Send operation timed out. Please try again.');
                    // Refresh the page after timeout
                    window.location.reload();
                }
            }, 60000); // 60 seconds timeout

            socket.on('connect', async function() {
                console.log('Connected to the Socket server.');
                totalContacts = contacts.length;
                
                for (const contact of contacts) {
                    const formattedContact = createWhatsappPhone(contact);
                    if (!formattedContact) {
                        console.error('Invalid contact number:', contact);
                        continue;
                    }

                    try {
                        if (messageType === 'media' && mediaFile) {
                            // Send media with message
                            const base64Data = await fileToBase64(mediaFile);
                            const mimeType = getMimeType(mediaFile.name);
                            
                            socket.emit('send-media', {
                                wa_token: wa_token,
                                user_id: <?php echo $_SESSION["login_id"]; ?>,
                                inv_id: batchId + '_' + formattedContact,
                                from_number: 'bulk_sender',
                                number: formattedContact,
                                message: message,
                                base64Data: base64Data,
                                mimeType: mimeType,
                                filename: mediaFile.name
                            });
                        } else {
                            // Send text message only
                            socket.emit('send-message', {
                                wa_token: wa_token,
                                user_id: <?php echo $_SESSION["login_id"]; ?>,
                                inv_id: batchId + '_' + formattedContact,
                                from_number: 'bulk_sender',
                                number: formattedContact,
                                message: message
                            });
                        }
                    } catch (error) {
                        console.error('Error processing contact:', contact, error);
                    }
                }
            });
            socket.on('connect_error', function(error) {
                connectionAttempts++;
                if (connectionAttempts >= maxRetries) {
                    console.error('Max connection attempts reached. Stopping Socket.');
                    clearTimeout(sendTimeout);
                    isSending = false;
                    socket.disconnect();
                    alert('Server disconnected. Please try again later.');
                    // Refresh the page after connection error
                    window.location.reload();
                }
            });

            socket.on('messageStatus', function(response) {
                console.log('Message sent successfully:', response);
                messagesSent++;
                
                // Update UI with progress
                $('#whatsapp_send').text(`Sending... (${messagesSent}/${totalContacts})`);
                
                // Check if all messages are sent
                if (messagesSent >= totalContacts) {
                    clearTimeout(sendTimeout);
                    isSending = false;
                    socket.disconnect();
                    alert('All messages sent successfully!');
                    // Refresh the page after successful send
                    window.location.reload();
                }
            });

            socket.on('error', function(error) {
                console.error('Error sending message:', error);
                clearTimeout(sendTimeout);
                isSending = false;
                socket.disconnect();
                alert('Error: ' + error.message);
                // Refresh the page after error
                window.location.reload();
            });

            socket.on('userLogout', function(userLogout) {
                if (userLogout.code === 401) {
                    console.error('WhatsApp session expired.');
                    clearTimeout(sendTimeout);
                    isSending = false;
                    socket.disconnect();
                    alert('WhatsApp session expired. Please log in again.');
                    // Refresh the page after logout
                    window.location.reload();
                } else {
                    console.error(userLogout.message);
                    clearTimeout(sendTimeout);
                    isSending = false;
                    socket.disconnect();
                    alert('Error: ' + userLogout.message);
                    // Refresh the page after error
                    window.location.reload();
                }
            });

            // Handle page unload to prevent duplicate sends
            $(window).on('beforeunload', function() {
                if (isSending) {
                    socket.disconnect();
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
