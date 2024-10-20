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
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h2>WhatsApp Login</h2>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="card col-lg-12">
            <div class="card-body">
                <div id="qrCode" style="display: none;">
                    <img id="qrImage" src="" alt="QR Code"/>
                    <input type="hidden" id="wa_token" <?php echo (isset($wa_token) && !empty($wa_token))?'value="'.$wa_token.'"':'' ?> />
                    <p class="note">Scan the QR from your phone WhatsApp to authenticate</p>
                </div>
                <div class="text-center">
                    <button class="btn btn-primary" id="get_whatsapp_qr">Login to WhatsApp</button>
                </div>
            </div>
        </div>
    </div>
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
                const socket = io('localhost:3000');
                console.log(socket);
                let connectionAttempts = 0;
                const maxRetries = 3;
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
                <?php if($check_status){ ?>
                    wa_check_token = $('#wa_token').val();
                    if(wa_check_token != ''){
                        socket.emit('check-session', wa_check_token);
                    }
                <?php } ?>
                $(document).on('click','#get_whatsapp_qr',function() {
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
                                wa_btn.closest('#whatsapp_qr_btn').find('#get_whatsapp_qr').remove();
                                $('#get_whatsapp_qr').remove();	
                                $('#wa_token').val(data.wa_token);
                                socket.emit('create-session', data.wa_token);
                            } 
                            else if (data.status == 'ERROR')
                            { 
                                errorMessage(data.msg,data.title,'','warning');
                            } else{
                                errorMessage('Please try again!','Something went wrong','','error');
                            }
                        },
                        error: function(data){
                        },
                        complete: function(data){
                        }
                    
                    }); 

                    
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
                        setTimeout(function() {
                            $('#qrCode').slideUp();
                            if ($('#whatsapp_logout').length === 0) {
                                $('#whatsapp_qr_btn').append('<button type="button" class="btn btn-primary get_whatsapp_qr" id="get_whatsapp_qr" >Connect WhatsApp</button> ');
                            }
                        }, 10000);
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
                            ContentType : 'application/json',
                            dataType: 'json',
                            data: data,
                            success: function(data){  
                                if(data.status == 'OK')
                                { 
                                    errorMessage('','WhatsApp logged out successfully.','','success');
                                    $('#whatsapp_qr_btn').find('.get_whatsapp_qr').remove();
                                    $('#whatsapp_qr_btn').find('.note').remove();
                                $('#whatsapp_qr_btn').append('<button type="button" class="btn btn-primary get_whatsapp_qr" id="get_whatsapp_qr" >Connect WhatsApp</button> ');
                                } 
                                else{
                                    errorMessage('Please try again!','Something went wrong','','error');
                                }
                            },
                            error: function(data){
                            },
                            complete: function(data){
                            }
                        
                        }); 

                    }else{
                        errorMessage(userLogout.message,'Something went wrong','','error');
                    }
                });
                
                socket.on('error', function(error) {
                    errorMessage(error.message,'Something Went Wrong','','error');
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
                            ContentType : 'application/json',
                            dataType: 'json',
                            data: data,
                            success: function(data){  
                                if(data.status == 'OK')
                                { 
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'WhatsApp Login Successfully'
                                    });
                                    $('#qrCode').slideUp();
                                    $('#wa_token').val(authenticated.wa_token);
                                    $('#whatsapp_qr_btn').find('.get_whatsapp_qr').remove();
                                    $('#get_whatsapp_qr').remove();
                                    $('#whatsapp_qr_btn').find('.note').remove();
                                    $('#whatsapp_qr_btn').append("<button type='button' class='btn btn-primary get_whatsapp_qr' id='whatsapp_logout'> Disconnect</button><p class='note'>Connected with "+data.contact_number+"</p>");
                                } 
                                else{
                                    errorMessage('Please try again!','Something went wrong','','error');
                                }
                            },
                            error: function(data){
                            },
                            complete: function(data){
                            }
                        
                        }); 
                    }else{
                        errorMessage('Please try again!','Something went wrong','','error');
                    }
                });
            });

        </script>	