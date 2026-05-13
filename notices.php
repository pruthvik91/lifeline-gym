<?php include('db_connect.php'); ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-800 text-slate-800 mb-0">Gym Notices</h2>
        <button class="btn btn-primary rounded-pill px-4" id="new_notice">
            <i class="fa fa-plus me-2"></i> Create New Notice
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-uppercase small fw-800 text-slate-500">Date</th>
                        <th class="py-3 text-uppercase small fw-800 text-slate-500">Title</th>
                        <th class="py-3 text-uppercase small fw-800 text-slate-500">Content</th>
                        <th class="py-3 text-uppercase small fw-800 text-slate-500 text-center">Color</th>
                        <th class="px-4 py-3 text-uppercase small fw-800 text-slate-500 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $qry = $conn->query("SELECT * FROM gym_notices ORDER BY id DESC");
                    while($row = $qry->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="px-4 fw-600 text-slate-500"><?php echo date('M d, Y', strtotime($row['date_created'])) ?></td>
                        <td><span class="fw-800 text-slate-800"><?php echo $row['title'] ?></span></td>
                        <td><div class="text-truncate" style="max-width: 300px;"><?php echo $row['content'] ?></div></td>
                        <td class="text-center">
                            <div class="mx-auto rounded-circle" style="width:20px;height:20px;background:<?php echo $row['border_color'] ?>;border:2px solid white;box-shadow:0 0 5px rgba(0,0,0,0.1)"></div>
                        </td>
                        <td class="px-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-light btn-sm edit_notice" data-id="<?php echo $row['id'] ?>"><i class="fa fa-edit text-primary"></i></button>
                                <button class="btn btn-light btn-sm delete_notice" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash text-danger"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $('#new_notice').click(function(){
        uni_modal("<i class='fa fa-plus me-2'></i>New Notice","manage_notice.php")
    })
    $('.edit_notice').click(function(){
        uni_modal("<i class='fa fa-edit me-2'></i>Edit Notice","manage_notice.php?id="+$(this).attr('data-id'))
    })
    $('.delete_notice').click(function(){
        _conf("Are you sure to delete this notice?","delete_notice",[$(this).attr('data-id')])
    })

    function delete_notice($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_notice',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp==1){
                    alert_toast("Data successfully deleted",'success')
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
            }
        })
    }
</script>
