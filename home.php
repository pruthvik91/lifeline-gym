<?php include 'db_connect.php' ?>
<style>
body:not(.has-navbar) main#view-panel {
  margin-top: 5rem;
  padding-top: 2rem;
}   
   span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    color: #ffffff96;
}
.imgs{
		margin: .5em;
		max-width: calc(100%);
		max-height: calc(100%);
	}
	.imgs img{
		max-width: calc(100%);
		max-height: calc(100%);
		cursor: pointer;
	}
	#imagesCarousel,#imagesCarousel .carousel-inner,#imagesCarousel .carousel-item{
		height: 60vh !important;background: black;
	}
	#imagesCarousel .carousel-item.active{
		display: flex !important;
	}
	#imagesCarousel .carousel-item-next{
		display: flex !important;
	}
	#imagesCarousel .carousel-item img{
		margin: auto;
	}
	#imagesCarousel img{
		width: auto!important;
		height: auto!important;
		max-height: calc(100%)!important;
		max-width: calc(100%)!important;
      
	}
</style>

<div class="container-fluid">
	<div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Welcome back, <?php echo $_SESSION['login_name']."!"  ?>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-4  mb-4">
                            <div class="card bg-gradient-primary text-white h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h3 class="mb-0 fw-bold">
                                                <?php echo $conn->query("SELECT * FROM registration_info where status = 1")->num_rows; ?>
                                            </h3>
                                            <p class="mb-0 opacity-75">Active Members</p>
                                        </div>
                                        <div class="summary_icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <small class="opacity-75">Currently active memberships</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4  mb-4">
                            <div class="card bg-gradient-info text-white h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h3 class="mb-0 fw-bold">
                                                <?php echo $conn->query("SELECT * FROM plans")->num_rows; ?>
                                            </h3>
                                            <p class="mb-0 opacity-75">Membership Plans</p>
                                        </div>
                                        <div class="summary_icon">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <small class="opacity-75">Available membership options</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4  mb-4">
                            <div class="card bg-gradient-warning text-white h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h3 class="mb-0 fw-bold">
                                                <?php echo $conn->query("SELECT * FROM packages")->num_rows; ?>
                                            </h3>
                                            <p class="mb-0 opacity-75">Total Packages</p>
                                        </div>
                                        <div class="summary_icon">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <small class="opacity-75">Service packages available</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4  mb-4">
                            <div class="card bg-gradient-danger text-white h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h3 class="mb-0 fw-bold">
                                                <?php echo $conn->query("SELECT batch FROM members where batch = 'Morning'")->num_rows; ?>
                                            </h3>
                                            <p class="mb-0 opacity-75">Morning Batch</p>
                                        </div>
                                        <div class="summary_icon">
                                            <i class="fas fa-sun"></i>
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <small class="opacity-75">Morning session members</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4  mb-4">
                            <div class="card bg-gradient-success text-white h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h3 class="mb-0 fw-bold">
                                                <?php echo $conn->query("SELECT batch FROM members where batch = 'Evening'")->num_rows; ?>
                                            </h3>
                                            <p class="mb-0 opacity-75">Evening Batch</p>
                                        </div>
                                        <div class="summary_icon">
                                            <i class="fas fa-moon"></i>
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <small class="opacity-75">Evening session members</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4  mb-4">
                            <div class="card bg-gradient-primary text-white h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h3 class="mb-0 fw-bold">
                                                <?php echo $conn->query("SELECT * FROM members ")->num_rows; ?>
                                            </h3>
                                            <p class="mb-0 opacity-75">Total Members</p>
                                        </div>
                                        <div class="summary_icon">
                                            <i class="fas fa-user-friends"></i>
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <small class="opacity-75">All registered members</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>	

                </div>
             
            </div>      			
        </div>
    </div>
    <center><img src="./assets/img/logo.png" width="500px" style="margin-top: 50px;" alt=""></center>
</div>
<script>
	$('#manage-records').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                resp=JSON.parse(resp)
                if(resp.status==1){
                    alert_toast("Data successfully saved",'success')
                    setTimeout(function(){
                        location.reload()
                    },800)

                }
                
            }
        })
    })
    $('#tracking_id').on('keypress',function(e){
        if(e.which == 13){
            get_person()
        }
    })
    $('#check').on('click',function(e){
            get_person()
    })
    function get_person(){
            start_load()
        $.ajax({
                url:'ajax.php?action=get_pdetails',
                method:"POST",
                data:{tracking_id : $('#tracking_id').val()},
                success:function(resp){
                    if(resp){
                        resp = JSON.parse(resp)
                        if(resp.status == 1){
                            $('#name').html(resp.name)
                            $('#address').html(resp.address)
                            $('[name="person_id"]').val(resp.id)
                            $('#details').show()
                            end_load()

                        }else if(resp.status == 2){
                            alert_toast("Unknow tracking id.",'danger');
                            end_load();
                        }
                    }
                }
            })
    }
</script>