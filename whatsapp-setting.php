<?php 
$wa_token = '';
$check_status = false;
include 'db_connect.php';
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
<style>
	body:not(.has-navbar) main#view-panel {
    margin-top: 8rem;
    padding-top: 2rem;
}

/* Premium Segmented Toggle for Auth Methods */
.auth-methods-premium {
    display: flex;
    background: #f1f5f9;
    padding: 6px;
    border-radius: 16px;
    gap: 4px;
}
.auth-methods-premium .method-option {
    flex: 1;
}
.auth-methods-premium .method-option input {
    display: none;
}
.auth-methods-premium .method-option label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 12px 10px;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    color: #64748b;
    font-weight: 800;
    font-size: 0.65rem;
    letter-spacing: 0.5px;
    gap: 6px;
    margin-bottom: 0;
}
.auth-methods-premium .method-option label i {
    font-size: 1.25rem;
}
.auth-methods-premium .method-option input:checked + label {
    background: white;
    color: #4f46e5;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.not-auth-icon-wrapper {
    width: 70px;
    height: 70px;
    background: #f8fafc;
    border-radius: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 1.75rem;
    color: #cbd5e1;
}

@media (max-width: 768px) {
    .w-100-mobile {
        width: 100% !important;
    }
}
	</style>

<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 px-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="index.php?page=home" class="text-decoration-none text-slate-400 fw-600 small">Dashboard</a></li>
                    <li class="breadcrumb-item active text-slate-500 fw-600 small" aria-current="page">WhatsApp</li>
                </ol>
            </nav>
            <h2 class="fw-800 text-slate-900 mb-0">Messaging Configuration</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-800 text-slate-800"><i class="fab fa-whatsapp me-2 text-success"></i> WhatsApp Authentication</h5>
                </div>
                <div class="card-body p-5 text-center">
                    <!-- Authentication Method Toggle -->
                    <div class="auth-methods-premium mb-5 mx-auto" style="max-width: 320px;">
                        <div class="method-option">
                            <input type="radio" name="auth_method" id="method_qr" value="qr" checked>
                            <label for="method_qr">
                                <i class="fas fa-qrcode"></i>
                                <span>QR CODE</span>
                            </label>
                        </div>
                        <div class="method-option">
                            <input type="radio" name="auth_method" id="method_pairing" value="pairing">
                            <label for="method_pairing">
                                <i class="fas fa-keyboard"></i>
                                <span>PAIRING</span>
                            </label>
                        </div>
                    </div>

                    <!-- QR Code Display -->
                    <div id="qrCode" class="mb-4" style="display: none;">
                        <div class="bg-white p-3 rounded-4 border border-slate-100 shadow-sm d-inline-block">
                            <img id="qrImage" src="" alt="QR Code" class="img-fluid" style="max-width: 250px;"/>
                        </div>
                        <p class="text-slate-500 fw-600 small mt-3">Scan this QR from your phone's WhatsApp</p>
                    </div>

                    <!-- Pairing Code Form -->
                    <div id="pairing_input_wrapper" class="mb-4 px-md-5" style="display: none;">
                        <div class="bg-slate-50 p-4 rounded-4 border border-slate-100">
                            <label class="extra-small fw-800 text-slate-400 text-uppercase mb-2 d-block text-start ms-2">WhatsApp Number</label>
                            <div class="input-group shadow-sm rounded-pill overflow-hidden border border-slate-200 bg-white mb-3">
                                <span class="input-group-text bg-white border-0 ps-3 text-slate-400"><i class="fas fa-phone-alt"></i></span>
                                <input type="text" id="pairing_number" class="form-control border-0 py-3 ps-1" placeholder="919876543210" style="outline: none; box-shadow: none; font-weight: 600;">
                            </div>
                            <p class="extra-small text-slate-400 fw-600 mb-0 text-start ms-2">Include country code without '+' (e.g. 91)</p>
                        </div>
                    </div>

                    <!-- Pairing Code Display -->
                    <div id="pairingCodeDisplay" class="mb-4" style="display: none;">
                        <div class="bg-primary-soft p-4 rounded-4 border border-primary border-opacity-10">
                            <div class="extra-small fw-800 text-primary text-uppercase mb-3">Authentication Code</div>
                            <div class="h1 fw-900 text-primary mb-0" id="pairingCodeValue" style="letter-spacing: 12px; font-family: 'Outfit', sans-serif;">--------</div>
                        </div>
                        <p class="text-slate-500 fw-600 small mt-3">Enter this code in WhatsApp > Linked Devices</p>
                    </div>

                    <div id="whatsapp_qr_btn">
                        <?php if (!empty($wa_token)) { ?>
                            <div class="bg-success-light p-4 rounded-4 mb-4">
                                <div class="stat-icon-square-minimal bg-white text-success mx-auto mb-3" style="width: 50px; height: 50px; border-radius: 15px;">
                                    <i class="fas fa-check-circle fs-4"></i>
                                </div>
                                <h6 class="fw-800 text-success mb-1">System Connected</h6>
                                <p class="extra-small fw-700 text-success opacity-75 mb-0"><?php echo $contact_number; ?></p>
                            </div>
                            <button type="button" class="btn btn-danger-premium rounded-pill px-5 fw-800 py-2 shadow-sm" id="whatsapp_logout">
                                <i class="fas fa-unlink me-2"></i> Disconnect Session
                            </button>
                        <?php } else { ?>
                            <div class="py-2" id="not_auth_view">
                                <div class="not-auth-icon-wrapper mb-4">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <h5 class="fw-800 text-slate-900 mb-2">Not Authenticated</h5>
                                <p class="text-slate-400 fw-600 small mb-4 px-md-4">Enable automated gym alerts by connecting below.</p>
                                <button class="btn btn-primary-premium rounded-pill px-5 fw-800 py-3 shadow-premium w-100-mobile" id="get_whatsapp_qr">
                                    <span id="btn_text"><i class="fas fa-plug me-2"></i> Start Connection</span>
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <div class="mt-5 text-center px-4">
                <div class="extra-small fw-800 text-slate-400 tracking-widest text-uppercase mb-3">Setup Instructions</div>
                <div class="row g-3">
                    <div class="col-md-4 col-12">
                        <div class="bg-white p-3 rounded-4 shadow-sm border border-slate-50 h-100">
                            <div class="fw-900 text-slate-900 h4 mb-1">01</div>
                            <div class="extra-small fw-700 text-slate-400">Open WhatsApp on Phone</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="bg-white p-3 rounded-4 shadow-sm border border-slate-50 h-100">
                            <div class="fw-900 text-slate-900 h4 mb-1">02</div>
                            <div class="extra-small fw-700 text-slate-400">Select Linked Devices</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="bg-white p-3 rounded-4 shadow-sm border border-slate-50 h-100">
                            <div class="fw-900 text-slate-900 h4 mb-1">03</div>
                            <div class="extra-small fw-700 text-slate-400">Scan QR Code Displayed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="wa_token" value="<?php echo $wa_token ?>">
</div>
<script src="assets/js/socket.io.min.js"></script>
<script src="assets/js/sweetalert2.js"></script>
    <script>
        var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    showClass: {
      popup: 'animate__animated animate__slideInRight'
    },
    hideClass: {
      popup: 'animate__animated animate__slideOutRight'
    },
    timerProgressBar: true,
  });
        function errorMessage(text,title = "Error",html='',icon='warning') {
            Swal.fire({
                icon: icon,
                title: ''+title+'',
                text: text,
                html: html,
                allowEnterKey: false,
                allowEscapeKey: false
            });
        }
            $(document).ready(function() {
                const socket = io('https://utility.lifelinefitnessstudio.com/');
                console.log(socket);
                let connectionAttempts = 0;
                const maxRetries = 10;
                socket.on('connect', function() {
                    console.log('Connected to the Socket');
                });
                socket.on('connect_error', function(error) {
                    connectionAttempts++;
                    if (connectionAttempts >= maxRetries) {
                        console.log('Max connection attempts reached. Stopping Socket.');
                        socket.disconnect();
                        // Ajax Log Entry Rermaining....
                    }
                });
                // Handle Method Toggle
                $('input[name="auth_method"]').on('change', function() {
                    $('#pairingCodeDisplay').hide();
                    $('#qrCode').hide();
                    if (this.value == 'pairing') {
                        $('#pairing_input_wrapper').slideDown();
                        $('#btn_text').html('<i class="fas fa-keyboard me-2"></i> Get Pairing Code');
                    } else {
                        $('#pairing_input_wrapper').slideUp();
                        $('#btn_text').html('<i class="fas fa-plug me-2"></i> Start Connection');
                    }
                });

                $(document).on('click','#get_whatsapp_qr',function() {
                    var authMethod = $('input[name="auth_method"]:checked').val();
                    var pairingNumber = $('#pairing_number').val().trim();

                    if (authMethod == 'pairing' && pairingNumber == '') {
                        errorMessage('Please enter your WhatsApp phone number with country code.', 'Input Required');
                        return;
                    }

                    $("body").addClass("is-loading");
                    var wa_btn = $(this);
                    var data = {
                        action: 'getWhatsappSession',
                        user_id: <?php echo $_SESSION["login_id"]; ?>
                    }	
                    $.ajax({  
                        type: "POST",  
                        url: "ajaxcall.php",
                        ContentType : 'application/json',
                        dataType: 'json',
                        data: data,
                        success: function(data){  
                            if(data.status == 'OK')
                            { 
                                $('#wa_token').val(data.wa_token);
                                var sessionData = {
                                    token: data.wa_token,
                                    from_number: "919909568777"
                                };

                                if (authMethod == 'pairing') {
                                    sessionData.pairing_code = true;
                                    sessionData.pairing_number = pairingNumber;
                                }

                                socket.emit('create-session', sessionData);
                            } 
                            else if (data.status == 'ERROR')
                            { 
                                $("body").removeClass("is-loading");
                                errorMessage(data.msg,data.title,'','warning');
                            } else{
                                $("body").removeClass("is-loading");
                                errorMessage('Please try again!','Something went wrong','','error');
                            }
                        },
                        error: function(data){
                            $("body").removeClass("is-loading");
                        }
                    }); 
                });

                socket.on('pairingCode', function(data) {
                    $("body").removeClass("is-loading");
                    if (data.code) {
                        $('#pairingCodeValue').text(data.code);
                        $('#pairingCodeDisplay').slideDown();
                        $('#pairing_input_wrapper').slideUp();
                        $('#not_auth_view').hide();
                    }
                });

                $(document).on('click','#whatsapp_logout',function() {
                    $("body").addClass("is-loading");
                    var wa_token = $('#wa_token').val();
                    socket.emit('logout', wa_token);
                    $("body").removeClass("is-loading");
                });

                socket.on('qrCode', function(data) {
                    if (data.qr) {
                        $('#qrImage').attr('src', `data:image/png;base64,${data.qr}`);
                        $('#qrCode').slideDown();
                        $('#not_auth_view').hide();
                        setTimeout(function() {
                            $('#qrCode').slideUp();
                            $('#not_auth_view').show();
                        }, 60000); // 60 seconds for QR
                    } else if (data.msg) {
                        errorMessage(data.msg,'Error','','error');
                    }
                    $("body").removeClass("is-loading");
                });

                socket.on('userConnected', function(userConnected) { 
                    if(userConnected.code != 200){
                        errorMessage('Please Contact Support Team!','Something went wrong','','error');
                    }
                });

                socket.on('userLogout', function(userLogout) {
                    if(userLogout.code == 401){
                        var data = {
                            action: 'authenticateWhatsappSession',
                            user_id: <?php echo $_SESSION["login_id"]; ?>,
                            wa_token: userLogout.wa_token,
                            status:0
                        }
                        $.ajax({  
                            type: "POST",  
                            url: "ajaxcall.php",
                            data: data,
                            success: function(data){  
                                location.reload(); // Reload to reset UI
                            }
                        }); 

                    }else{
                        errorMessage(userLogout.message,'Something went wrong','','error');
                    }
                });
                
                socket.on('error', function(error) {
                    $("body").removeClass("is-loading");
                    if (error.message && error.message.includes('User already exists and is active')) {
                        // This means the session is actually alive on the server
                        // We should reload to let the UI reflect the active status
                        location.reload();
                    } else {
                        errorMessage(error.message,'Something Went Wrong','','error');
                    }
                });

                socket.on('sessionStatus', function(authenticated) {
                    if(authenticated.code == 200){
                        var data = {
                            action: 'authenticateWhatsappSession',
                            user_id: <?php echo $_SESSION["login_id"]; ?>,
                            wa_token: authenticated.wa_token,
                            contact_number: authenticated.number,
                            status:1
                        }
                        $.ajax({  
                            type: "POST",  
                            url: "ajaxcall.php",
                            dataType: 'json',
                            data: data,
                            success: function(data){  
                                if(data.status == 'OK')
                                { 
                                    location.reload();
                                } 
                                else{
                                    errorMessage('Please try again!','Something went wrong','','error');
                                }
                            }
                        }); 
                    }else{
                        errorMessage('Please try again!','Something went wrong','','error');
                    }
                });
            });

        </script>	