<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM gym_notices where id= ".$_GET['id']);
    foreach($qry->fetch_array() as $k => $val){
        $$k=$val;
    }
}
?>
<div class="container-fluid">
    <form action="" id="manage-notice">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="mb-3">
            <label class="control-label fw-800 text-slate-700 small mb-1">Notice Title</label>
            <input type="text" name="title" class="form-control rounded-3" value="<?php echo isset($title) ? $title : '' ?>" required>
        </div>
        <div class="mb-3">
            <label class="control-label fw-800 text-slate-700 small mb-1">Content</label>
            <textarea name="content" cols="30" rows="4" class="form-control rounded-4" required><?php echo isset($content) ? $content : '' ?></textarea>
        </div>
        <div class="mb-3">
            <label class="control-label fw-800 text-slate-700 small mb-1">Theme Color</label>
            <select name="border_color" class="form-select rounded-pill">
                <option value="#4f46e5" <?php echo isset($border_color) && $border_color == '#4f46e5' ? 'selected' : '' ?>>Indigo (Standard)</option>
                <option value="#f97316" <?php echo isset($border_color) && $border_color == '#f97316' ? 'selected' : '' ?>>Orange (Warning)</option>
                <option value="#10b981" <?php echo isset($border_color) && $border_color == '#10b981' ? 'selected' : '' ?>>Green (Special)</option>
                <option value="#ef4444" <?php echo isset($border_color) && $border_color == '#ef4444' ? 'selected' : '' ?>>Red (Emergency)</option>
            </select>
        </div>
    </form>
</div>

<script>
    $('#manage-notice').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_notice',
            method:'POST',
            data:$(this).serialize(),
            success:function(resp){
                if(resp == 1){
                    alert_toast("Data successfully saved","success")
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
            }
        })
    })
</script>
