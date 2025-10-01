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
    WHERE r.status = 1  AND contact != ''
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
        var contacts = <?php echo json_encode($contacts); ?>;
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

        $('#whatsapp_send').on('click', async function() {
            if (isSending) return alert('Messages are already being sent.');

            const message = $('#bulk_message').val().trim();
            const messageType = $('input[name="message_type"]:checked').val();
            const mediaFile = $('#media_file')[0].files[0];

            if (!message) return alert('Please enter a message.');
            if (messageType === 'media' && !mediaFile) return alert('Please select a media file.');

            isSending = true;
            messagesSent = 0;
            totalContacts = contacts.length;
            $('#whatsapp_send').prop('disabled', true).text('Sending...');
            const socket = io('http://localhost:3000');
            const wa_token = '<?php echo $wa_token; ?>';
            const batchId = 'bulk_' + Date.now();
            let numbers = contacts.map(contact => createWhatsappPhone(contact));
            if (messageType === 'media') {
                // Convert media only once
                const base64Data = await fileToBase64(mediaFile);
                const mimeType = getMimeType(mediaFile.name);
                const filename = mediaFile.name;

                let base64DataArray = Array(numbers.length).fill(base64Data);
                let mimeTypeArray = Array(numbers.length).fill(mimeType);
                let filenameArray = Array(numbers.length).fill(filename);

                // Emit bulk media
                socket.emit('send-bulk-media', {
                    wa_token,
                    user_id: <?php echo $_SESSION["login_id"]; ?>,
                    numbers,
                    message,
                    base64DataArray,
                    mimeTypeArray,
                    filenameArray
                });

            } else {
                // Emit bulk text message
                socket.emit('send-bulk', {
                    wa_token,
                    user_id: <?php echo $_SESSION["login_id"]; ?>,
                    numbers,
                    message
                });
            }

            // Handle per-number progress (for both media and text)
            socket.on('bulk-progress', function(data) {
                $('#whatsapp_send').text(`Sending... (${messagesSent}/${totalContacts})`);
            });
            socket.on('bulk-media-progress', function(data) {
                $('#whatsapp_send').text(`Sending... (${messagesSent}/${totalContacts})`);
            });

            socket.once('bulk-done', function() {
                isSending = false;
                $('#whatsapp_send').prop('disabled', false).text('Send');
                alert('All messages processed!');
            });
            socket.once('bulk-media-done', function() {
                isSending = false;
                $('#whatsapp_send').prop('disabled', false).text('Send');
                alert('All messages processed!');
            });
        });


    });

    function createWhatsappPhone(number) {
        number = String(number.trim()).replace(/\D/g, '');
        if (number.length == 10) return "91" + number;
        if (number.length == 11 && number.startsWith('0')) return "91" + number.substring(1);
        return number;
    }
</script>