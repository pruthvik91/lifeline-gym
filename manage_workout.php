<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM member_workouts where id = ".$_GET['id'])->fetch_array();
    if($qry){
        foreach($qry as $k => $v){
            $$k = $v;
        }
    }
}
?>
<div class="container-fluid py-2">
    <form action="" id="manage-workout">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        
        <label class="upload-zone" id="drop-zone" for="workout_file">
            <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div class="upload-text">
                <h5 class="fw-800 mb-1">Click to upload file</h5>
                <p class="text-slate-400 small mb-0">Supports JPG, PNG or PDF (Max 5MB)</p>
            </div>
            <input type="file" id="workout_file" name="img" accept="image/*,application/pdf" style="display:none" onchange="displayImg(this,$(this))">
        </label>

        <div id="file-preview-container" class="mt-4 <?php echo isset($file_path) ? '' : 'd-none' ?>">
            <label class="fw-800 text-slate-500 small text-uppercase mb-3 d-block">Current / Selected Plan</label>
            <div class="preview-card p-3 rounded-4 bg-slate-50 border border-slate-100 text-center">
                <?php 
                $is_pdf = isset($file_path) && pathinfo($file_path, PATHINFO_EXTENSION) == 'pdf';
                ?>
                <div id="pdf-icon" class="<?php echo $is_pdf ? '' : 'd-none' ?>" style="width: 100%; height: 350px;">
                    <iframe src="<?php echo $is_pdf ? 'assets/uploads/'.$file_path : '' ?>" id="pdf-preview" width="100%" height="100%" frameborder="0" class="<?php echo $is_pdf ? '' : 'd-none' ?>"></iframe>
                </div>
                <img src="<?php echo isset($file_path) && !$is_pdf ? 'assets/uploads/'.$file_path :'' ?>" alt="" id="cimg" class="img-fluid rounded-3 shadow-sm <?php echo $is_pdf || !isset($file_path) ? 'd-none' : '' ?>" style="max-height: 300px;">
            </div>
        </div>
    </form>
</div>

<style>
    .upload-zone {
        border: 2px dashed #e2e8f0;
        border-radius: 1.5rem;
        padding: 2.5rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8fafc;
    }
    .upload-zone:hover {
        border-color: var(--primary);
        background: #f0f4ff;
    }
    .upload-icon {
        width: 60px;
        height: 60px;
        background: white;
        border-radius: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.2rem;
        color: var(--primary);
        font-size: 1.5rem;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .preview-card {
        min-height: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
</style>

<script>
    function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var file = input.files[0];
            
            $('#file-preview-container').removeClass('d-none');
            
            if (file.type === "application/pdf") {
                $('#pdf-icon, #pdf-preview').removeClass('d-none');
                $('#cimg').addClass('d-none');
                
                var fileURL = URL.createObjectURL(file);
                $('#pdf-preview').attr('src', fileURL);
            } else {
                $('#pdf-icon, #pdf-preview').addClass('d-none');
                $('#cimg').removeClass('d-none');
                reader.onload = function (e) {
                    $('#cimg').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
            
            $('.upload-text h5').text(file.name);
            $('.upload-zone').css('border-color', 'var(--primary)');
        }
    }

    $('#manage-workout').submit(function(e){
        e.preventDefault()
        
        // Validation: Check if a file is selected (only if no file exists yet)
        var hasExistingFile = <?php echo (isset($file_path) && !empty($file_path)) ? 'true' : 'false' ?>;
        var hasNewFile = $('#workout_file')[0].files.length > 0;

        if(!hasExistingFile && !hasNewFile){
            alert_toast("Please select a workout plan file to upload before assigning.","danger");
            return false;
        }

        start_load()
        $.ajax({
            url:'ajax.php?action=assign_workout',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp == 1){
                    alert_toast("Workout plan updated successfully","success")
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
            }
        })
    })
</script>
