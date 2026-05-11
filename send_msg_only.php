<?php
include 'db_connect.php';
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
$contacts = array();
// $member_query = $conn->query("
//     SELECT m.contact 
//     FROM registration_info r 
//     INNER JOIN members m ON m.id = r.member_id 
//     WHERE r.status = 1 AND contact != ''
//     ORDER BY r.id DESC 
// ");

// if($member_query){
//     while ($row = $member_query->fetch_assoc()) {
//         $contacts[] = $row['contact'];
//     }
// }
$contacts = ["9638567558"];
// ,9313933076,7990251613,7284002450
?>
<div class="container-fluid py-4">
    <!-- Premium Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 px-2">
        <div>
            <h2 class="fw-800 text-slate-900 mb-1">Broadcast Center</h2>
            <p class="text-slate-500 fw-500 mb-0">Send mass announcements to all active members</p>
        </div>
        <div class="connection-status-pill <?php echo !empty($wa_token) ? 'status-online' : 'status-offline' ?>">
            <i class="fas fa-circle me-2 small"></i>
            <span><?php echo !empty($wa_token) ? 'WhatsApp Connected' : 'WhatsApp Disconnected' ?></span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Composer -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-800 text-slate-800"><i class="fas fa-paper-plane me-2 text-indigo-600"></i>Message Composer</h5>
                </div>
                <div class="card-body p-4">
                    <form id="broadcast-form">
                        <!-- Message Type Selector -->
                        <div class="mb-4">
                            <label class="form-label fw-700 text-slate-600 mb-3">Announcement Type</label>
                            <div class="d-flex gap-3">
                                <div class="type-option flex-grow-1">
                                    <input type="radio" name="message_type" id="text_only" value="text" checked class="btn-check">
                                    <label class="type-card" for="text_only">
                                        <i class="fas fa-align-left mb-2 fs-4"></i>
                                        <span class="fw-700">Text Only</span>
                                        <small class="text-slate-400 d-block">Plain text message</small>
                                    </label>
                                </div>
                                <div class="type-option flex-grow-1">
                                    <input type="radio" name="message_type" id="media_with_text" value="media" class="btn-check">
                                    <label class="type-card" for="media_with_text">
                                        <i class="fas fa-images mb-2 fs-4"></i>
                                        <span class="fw-700">Media + Text</span>
                                        <small class="text-slate-400 d-block">Image/PDF with caption</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Media Upload Section -->
                        <div id="media_section" class="mb-4 animate__animated animate__fadeIn" style="display: none;">
                            <label class="form-label fw-700 text-slate-600">Attachment</label>
                            <div class="upload-zone border-2 border-dashed rounded-4 p-4 text-center bg-slate-50 transition-all">
                                <input type="file" id="media_file" class="d-none" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx">
                                <div class="upload-prompt pointer" onclick="document.getElementById('media_file').click()">
                                    <div class="icon-circle mb-3 mx-auto bg-indigo-100 text-indigo-600">
                                        <i class="fas fa-cloud-upload-alt fs-4"></i>
                                    </div>
                                    <h6 class="fw-800 text-slate-700 mb-1" id="file-name-display">Choose file or drag & drop</h6>
                                    <p class="small text-slate-400 mb-0">PDF, Images, or Documents (Max 16MB)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Message Body -->
                        <div class="mb-4">
                            <label for="bulk_message" class="form-label fw-700 text-slate-600">Message Content</label>
                            <textarea name="bulk_message" id="bulk_message" class="form-control border-2 rounded-4 p-3" rows="6" placeholder="Type your announcement here... Use variables like {name} if supported."></textarea>
                        </div>

                        <!-- Progress Section -->
                        <div id="progress-container" class="mb-4" style="display: none;">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-700 text-slate-600" id="progress-text">Sending... 0/0</span>
                                <span class="fw-800 text-indigo-600" id="progress-percent">0%</span>
                            </div>
                            <div class="progress rounded-pill bg-slate-100" style="height: 10px;">
                                <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-indigo-600 rounded-pill" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="button" id="whatsapp_send" class="btn btn-indigo-premium py-3 rounded-pill fw-800 text-uppercase letter-spacing-1">
                                <i class="fas fa-paper-plane me-2"></i> Launch Broadcast
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-premium rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-indigo-600 py-3 border-0">
                    <h6 class="mb-0 text-white fw-700">Distribution Stats</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="stat-icon-indigo me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="text-slate-400 small fw-700 text-uppercase letter-spacing-1">Active Members</div>
                            <div class="fs-4 fw-800 text-slate-900"><?php echo count($contacts); ?></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="stat-icon-indigo me-3 bg-emerald-100 text-emerald-600">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div>
                            <div class="text-slate-400 small fw-700 text-uppercase letter-spacing-1">Reachable via WA</div>
                            <div class="fs-4 fw-800 text-slate-900"><?php echo count($contacts); ?></div>
                        </div>
                    </div>
                    <hr class="border-slate-100 my-4">
                    <div class="alert alert-indigo border-0 rounded-4 p-3 mb-0">
                        <div class="d-flex gap-3">
                            <i class="fas fa-info-circle mt-1"></i>
                            <div class="small fw-600">
                                This message will be sent to all active members listed in your directory who have a valid contact number.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Templates -->
            <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 text-slate-800 fw-800">Quick Templates</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="list-group list-group-flush">
                        <button type="button" class="list-group-item list-group-item-action px-0 border-0 template-btn" data-msg="Hello Team! Wishing you a high-energy workout session today. Don't forget to stay hydrated! 💧💪">
                            <div class="fw-700 text-indigo-600 mb-1 small">Daily Motivation</div>
                            <div class="text-slate-400 small text-truncate">Wishing you a high-energy workout...</div>
                        </button>
                        <button type="button" class="list-group-item list-group-item-action px-0 border-0 template-btn" data-msg="URGENT: The gym will remain closed tomorrow for monthly maintenance. We apologize for the inconvenience. Regular hours resume the day after!">
                            <div class="fw-700 text-indigo-600 mb-1 small">Maintenance Alert</div>
                            <div class="text-slate-400 small text-truncate">The gym will remain closed tomorrow...</div>
                        </button>
                        <button type="button" class="list-group-item list-group-item-action px-0 border-0 template-btn" data-msg="Flash Sale! Renew your membership today and get a 10% discount on supplements. Valid for 24 hours only! ⚡">
                            <div class="fw-700 text-indigo-600 mb-1 small">Special Offer</div>
                            <div class="text-slate-400 small text-truncate">Flash Sale! Renew your membership...</div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    
    :root {
        --indigo-600: #4f46e5;
        --indigo-700: #4338ca;
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-400: #94a3b8;
        --slate-500: #64748b;
        --slate-600: #475569;
        --slate-800: #1e293b;
        --slate-900: #0f172a;
    }

    body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .letter-spacing-1 { letter-spacing: 0.5px; }
    
    .shadow-premium { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    
    .connection-status-pill {
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
    }
    .status-online { background: #dcfce7; color: #166534; }
    .status-offline { background: #fee2e2; color: #991b1b; }

    .type-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1.5rem;
        background: white;
        border: 2px solid #f1f5f9;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .btn-check:checked + .type-card {
        border-color: var(--indigo-600);
        background: #f5f3ff;
        color: var(--indigo-600);
    }

    .type-card:hover { transform: translateY(-3px); border-color: #e2e8f0; }

    .upload-zone { border: 2px dashed #e2e8f0; transition: all 0.2s; cursor: pointer; }
    .upload-zone:hover { border-color: var(--indigo-600); background: #f5f3ff !important; }

    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon-indigo {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #eef2ff;
        color: var(--indigo-600);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .btn-indigo-premium {
        background: linear-gradient(135deg, var(--indigo-600) 0%, var(--indigo-700) 100%);
        color: white;
        border: none;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        transition: all 0.3s ease;
    }

    .btn-indigo-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 20px -3px rgba(79, 70, 229, 0.4);
        filter: brightness(1.1);
        color: white;
    }

    .alert-indigo { background: #eef2ff; color: var(--indigo-600); }
    .template-btn { transition: all 0.2s; }
    .template-btn:hover { background-color: var(--slate-50); padding-left: 5px !important; }

    .form-control:focus {
        border-color: var(--indigo-600);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }
</style>

<script src="assets/js/socket.io.min.js"></script>
<script src="assets/js/sweetalert2.js"></script>

<script>
    $(document).ready(function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        var contacts = <?php echo json_encode($contacts); ?>;
        
        // Handle Template Buttons
        $('.template-btn').click(function() {
            $('#bulk_message').val($(this).data('msg'));
            Toast.fire({ icon: 'info', title: 'Template applied!' });
        });

        // File selection display
        $('#media_file').on('change', function() {
            const fileName = $(this).val().split('\\').pop();
            if(fileName) {
                $('#file-name-display').text(fileName).addClass('text-indigo-600');
            }
        });

        // Toggle media section
        $('input[name="message_type"]').on('change', function() {
            if ($(this).val() === 'media') {
                $('#media_section').slideDown();
            } else {
                $('#media_section').slideUp();
            }
        });

        function fileToBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }

        function getMimeType(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            const mimeTypes = {
                'jpg': 'image/jpeg', 'jpeg': 'image/jpeg', 'png': 'image/png',
                'gif': 'image/gif', 'webp': 'image/webp', 'mp4': 'video/mp4',
                'pdf': 'application/pdf', 'docx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            };
            return mimeTypes[ext] || 'application/octet-stream';
        }

        let isSending = false;
        let messagesSent = 0;
        let totalContacts = contacts.length;

        $('#whatsapp_send').on('click', async function() {
            if (isSending) return;

            const message = $('#bulk_message').val().trim();
            const messageType = $('input[name="message_type"]:checked').val();
            const mediaFile = $('#media_file')[0].files[0];

            if (!message) return Toast.fire({ icon: 'error', title: 'Please enter a message' });
            if (messageType === 'media' && !mediaFile) return Toast.fire({ icon: 'error', title: 'Please select a media file' });

            const result = await Swal.fire({
                title: 'Start Broadcast?',
                text: `You are about to send messages to ${totalContacts} members. Proceed?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'Yes, Launch!'
            });

            if (!result.isConfirmed) return;

            isSending = true;
            messagesSent = 0;
            
            $('#progress-container').fadeIn();
            $('#whatsapp_send').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Broadcast in Progress...');
            
            const socket = io('https://utility.lifelinefitnessstudio.com/');
            const wa_token = '<?php echo $wa_token; ?>';
            let numbers = contacts.map(contact => createWhatsappPhone(contact));

            // Clean previous listeners
            socket.off('bulk-progress');
            socket.off('bulk-complete');
            socket.off('bulk-done');

            const updateProgress = (data) => {
                messagesSent++;
                const percent = Math.round((messagesSent / totalContacts) * 100);
                $('#progress-bar').css('width', percent + '%');
                $('#progress-percent').text(percent + '%');
                $('#progress-text').text(`Sending... ${messagesSent}/${totalContacts}`);
                
                if (data.status === 'sent') {
                    console.log(`Successfully sent to ${data.number}`);
                } else {
                    console.error(`Failed to send to ${data.number}`);
                }
            };

            const onComplete = () => {
                isSending = false;
                $('#progress-bar').removeClass('progress-bar-animated').addClass('bg-success');
                $('#whatsapp_send').prop('disabled', false)
                                  .removeClass('btn-indigo-premium')
                                  .addClass('btn-success')
                                  .html('<i class="fas fa-check-double me-2"></i> Broadcast Completed');
                
                Swal.fire({
                    title: 'Broadcast Complete!',
                    text: `Successfully processed ${messagesSent} messages.`,
                    icon: 'success',
                    confirmButtonColor: '#10b981'
                });

                // Reset button after 5 seconds
                setTimeout(() => {
                    $('#whatsapp_send').removeClass('btn-success')
                                      .addClass('btn-indigo-premium')
                                      .html('<i class="fas fa-paper-plane me-2"></i> Launch Broadcast');
                    $('#progress-container').fadeOut();
                    $('#progress-bar').css('width', '0%').removeClass('bg-success').addClass('progress-bar-animated');
                }, 5000);
            };

            socket.on('bulk-progress', updateProgress);
            socket.once('bulk-complete', onComplete);
            socket.once('bulk-done', onComplete);

            if (messageType === 'media') {
                const base64DataArray = await fileToBase64(mediaFile);
                const mimeType = getMimeType(mediaFile.name);
                const filename = mediaFile.name;

                socket.emit('send-bulk-media', {
                    wa_token, user_id: <?php echo $_SESSION["login_id"]; ?>,
                    numbers, message, base64DataArray, mimeType, filename
                });

            } else {
                socket.emit('send-bulk', {
                    wa_token, user_id: <?php echo $_SESSION["login_id"]; ?>,
                    numbers, message
                });
            }
        });
    });

    function createWhatsappPhone(number) {
        number = String(number.trim()).replace(/\D/g, '');
        if (number.length == 10) return "91" + number;
        if (number.length == 11 && number.startsWith('0')) return "91" + number.substring(1);
        return number;
    }
</script>