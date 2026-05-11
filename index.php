<!DOCTYPE html>
<html lang="en">
	
<?php session_start(); ?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Lifeline Fitness</title>
 	

<?php
  if(!isset($_SESSION['login_id']))
    header('location:login.php');
 include('./header.php'); 
 // include('./auth.php'); 
 ?>

</head>
<body>
	<?php include 'topbar.php' ?>
	<?php include 'navbar.php' ?>
  <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body text-white">
    </div>
  </div>
  <main id="view-panel" >
      <?php $page = isset($_GET['page']) ? $_GET['page'] :'home'; ?>
  	<?php include $page.'.php' ?>
  	

  </main>

  <div id="preloader"></div>
  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


  <!-- Premium Modal Structure -->
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content border-0 shadow-premium rounded-4 overflow-hidden">
        <div class="modal-header border-bottom border-slate-100 bg-slate-50 p-4">
          <h5 class="modal-title fw-800 text-slate-900"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
        </div>
        <div class="modal-footer border-top-0 p-4 pt-0">
          <button type="button" class="btn btn-primary shadow-sm" id='submit' onclick="$('#uni_modal form').submit()">Confirm Action</button>
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content border-0 shadow-premium rounded-4">
        <div class="modal-header border-bottom border-slate-100 bg-slate-50 p-4">
          <h5 class="modal-title fw-800 text-slate-900">System Confirmation</h5>
        </div>
        <div class="modal-body p-4 text-center py-5">
          <div class="mb-3">
             <i class="fas fa-exclamation-circle text-warning fs-1"></i>
          </div>
          <div id="delete_content" class="fw-600 text-slate-600"></div>
        </div>
        <div class="modal-footer border-top-0 p-4 justify-content-center">
          <button type="button" class="btn btn-danger px-4" id='confirm' onclick="">Continue</button>
          <button type="button" class="btn btn-light px-4" data-dismiss="modal">Go Back</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content bg-transparent border-0 shadow-none">
          <button type="button" class="btn-close-viewer" data-dismiss="modal"><i class="fa fa-times"></i></button>
          <div class="viewer-container">
              <!-- Content injected via JS -->
          </div>
      </div>
    </div>
  </div>

  <!-- Mobile Bottom Navigation -->
  <div class="mobile-bottom-nav">
      <a href="index.php?page=home" class="mobile-nav-item <?php echo $page == 'home' ? 'active' : '' ?>">
          <i class="fas fa-th-large"></i>
          <span>Home</span>
      </a>
      <a href="index.php?page=members" class="mobile-nav-item <?php echo $page == 'members' ? 'active' : '' ?>">
          <i class="fas fa-users"></i>
          <span>Members</span>
      </a>
      <a href="index.php?page=registered_members" class="mobile-nav-item <?php echo $page == 'registered_members' ? 'active' : '' ?>">
          <i class="fas fa-calendar-check"></i>
          <span>Validity</span>
      </a>
      <a href="javascript:void(0)" class="mobile-nav-item" onclick="$('#sidebar').toggleClass('mobile-open'); $('body').toggleClass('sidebar-open-lock');">
          <i class="fas fa-bars"></i>
          <span>Menu</span>
      </a>
  </div>

</body>
<script>
	 window.start_load = function(){
    $('body').prepend('<di id="preloader2"></di>')
  }
  window.end_load = function(){
    $('#preloader2').fadeOut('fast', function() {
        $(this).remove();
      })
  }
 window.viewer_modal = function($src = ''){
    start_load()
    var t = $src.split('.')
    t = t[t.length - 1].toLowerCase();
    if(t =='mp4'){
      var view = $("<video src='"+$src+"' controls autoplay class='img-fluid rounded-4'></video>")
    }else{
      var view = $("<img src='"+$src+"' class='img-fluid rounded-4' />")
    }
    $('#viewer_modal .viewer-container').html(view)
    $('#viewer_modal').modal({
            show:true,
            backdrop:'static',
            keyboard:false,
            focus:true
          })
          end_load()  
}
  window.uni_modal = function($title = '' , $url='',$size=""){
    start_load()
    $.ajax({
        url:$url,
        error:err=>{
            console.log()
            alert("An error occured")
        },
        success:function(resp){
            if(resp){
                $('#uni_modal .modal-title').html($title)
                $('#uni_modal .modal-body').html(resp)
                if($size != ''){
                    $('#uni_modal .modal-dialog').attr("class", "modal-dialog modal-dialog-centered " + $size)
                }else{
                    $('#uni_modal .modal-dialog').attr("class", "modal-dialog modal-dialog-centered modal-md")
                }
                $('#uni_modal').modal({
                  show:true,
                  backdrop:'static',
                  keyboard:false,
                  focus:true
                })
                end_load()
            }
        }
    })
}
window._conf = function($msg='',$func='',$params = []){
     $('#confirm_modal #confirm').attr('onclick',$func+"("+$params.join(',')+")")
     $('#confirm_modal .modal-body').html($msg)
     $('#confirm_modal').modal('show')
  }
   window.alert_toast= function($msg = 'TEST',$bg = 'success'){
      $('#alert_toast').removeClass('bg-success')
      $('#alert_toast').removeClass('bg-danger')
      $('#alert_toast').removeClass('bg-info')
      $('#alert_toast').removeClass('bg-warning')

    if($bg == 'success')
      $('#alert_toast').addClass('bg-success')
    if($bg == 'danger')
      $('#alert_toast').addClass('bg-danger')
    if($bg == 'info')
      $('#alert_toast').addClass('bg-info')
    if($bg == 'warning')
      $('#alert_toast').addClass('bg-warning')
    $('#alert_toast .toast-body').html($msg)
    $('#alert_toast').toast({delay:3000}).toast('show');
  }
  $(document).ready(function(){
    $('#preloader').fadeOut('fast', function() {
        $(this).remove();
    });

    // Close mobile sidebar when clicking outside
    $(document).on('mousedown touchstart', function(e) {
        var sidebar = $('#sidebar');
        var menuBtn = $('.mobile-nav-item i.fa-bars').parent(); // The menu toggle item
        
        if (sidebar.hasClass('mobile-open')) {
            if (!sidebar.is(e.target) && sidebar.has(e.target).length === 0 && !menuBtn.is(e.target) && menuBtn.has(e.target).length === 0) {
                sidebar.removeClass('mobile-open');
                $('body').removeClass('sidebar-open-lock');
            }
        }
    });
  })
  $('.datetimepicker').datetimepicker({
      format:'Y/m/d H:i',
      startDate: '+3d'
  })
  $('.select2').select2({
    placeholder:"Please select here",
    width: "100%"
  })
</script>	
</html>