<?php include('db_connect.php'); ?>

<div class="container-fluid">

    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-4">
                <form action="" id="manage-batch">
                    <div class="card">
                        <div class="card-header">
                            batch form
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="id">
                            <div class="form-group">    
                                <label class="control-label">batches</label>
                                <input type="text" class="form-control text-right" min="1" name="batch">
                            </div>


                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
                                    <button class="btn btn-sm btn-default col-sm-3" type="button" onclick="_reset()">
                                        Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <b>Batch List</b>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <colgroup>
                                <col width="5%">
                                <col width="55%">

                                <col width="20%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Batch</th>

                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
								$i = 1;
								$batch = $conn->query("SELECT * FROM batch order by id asc");
								while ($row = $batch->fetch_assoc()) :
								?>
                                <tr>
                                    <td class="text-center"><?php echo $i++ ?></td>
                                    <td class="">
                                        <p><b><?php echo $row['batch'] ?></b> </p>
                                    </td>

                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary edit_batch" type="button"
                                            data-id="<?php echo $row['batch'] ?>">Edit</button>
                                        <button class="btn btn-sm btn-danger delete_batch" type="button"
                                            data-id="<?php echo $row['id'] ?>">Delete</button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>

</div>
<style>
td {
    vertical-align: middle !important;
}
</style>
<script>
function _reset() {
    $('#manage-batch').get(0).reset()
    $('#manage-batch input,#manage-batch textarea').val('')
}
$('#manage-batch').submit(function(e) {
    e.preventDefault()
    start_load()
    $.ajax({
        url: 'ajax.php?action=save_batch',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function(resp) {
            if (resp == 1) {
                alert_toast("Data successfully added", 'success')
                setTimeout(function() {
                    location.reload()
                }, 1500)

            } else if (resp == 2) {
                alert_toast("Data successfully updated", 'success')
                setTimeout(function() {
                    location.reload()
                }, 1500)

            }
        }
    })
})
$('.edit_batch').click(function() {
    start_load()
    var cat = $('#manage-batch')
    cat.get(0).reset()
    cat.find("[name='id']").val($(this).attr('data-id'))
    cat.find("[name='batch']").val($(this).attr('data-batch'))

    end_load()
})
$('.delete_batch').click(function() {
    _conf("Are you sure to delete this batch?", "delete_batch", [$(this).attr('data-id')])
})

function delete_batch($id) {
    start_load()
    $.ajax({
        url: 'ajax.php?action=delete_batch',
        method: 'POST',
        data: {
            id: $id
        },
        success: function(resp) {
            if (resp == 1) {
                alert_toast("Data successfully deleted", 'success')
                setTimeout(function() {
                    location.reload()
                }, 1500)

            }
        }
    })
}
$('table').dataTable()
</script>