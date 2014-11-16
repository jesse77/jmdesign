<div class="modal fade" id="add-tag-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">New Tag</h4>
            </div>
            <form action="<?= site_url('admin/add_tag') ?>"
                  method="post" enctype="multipart/form-data">
                
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tag Name</label>
                            <input type="text" name="name" class="form-control"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">Close</button>
                        <input class="btn btn-primary" type="submit" value="Save" name="submit" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-tag-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">New Tag</h4>
            </div>
            <form action="<?= site_url('admin/edit_tag') ?>"
                  method="post" enctype="multipart/form-data">
                
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tag Name</label>
                            <input type="hidden" name="id" class="id-field"/>
                            <input type="text" name="name" class="form-control name-field"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">Close</button>
                        <input class="btn btn-primary" type="submit" value="Save" name="submit" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<h1>Tags
    <button type="button" class="btn btn-success btn-lg pull-right" id="add-tag">
        <b>add</b>
    </button>
</h1>

<table class="table table-striped table-condensed">
    <?php foreach( $tags as $key => $tag ): ?>
    <tr>
        <td><?= $tag->id ?></td>
        <td><?= $tag->name ?></td>
        <td class="col-xs-2">
            <form method="post" action="<?= site_url('admin/delete_tag') ?>" class="pull-right">
                <input type="hidden" name="id" value="<?= $tag->id ?>" />
                <input type="submit" data-id="<?= $tag->id ?>" value="Confirm Delete" 
                       class="btn btn-danger btn-sm invisible confirm-delete" />
            <input type="button" data-id="<?= $tag->id ?>"
                   class="btn btn-default btn-sm start-delete" value="Delete" />
            </form>
        </td>
        <td class="col-xs-1">
            <input type="button" class="btn btn-warning btn-sm edit-tag-btn" value="Edit"
                   data-id="<?= $tag->id ?>" data-name="<?= $tag->name ?>" />
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<script type="text/javascript">
    $('#add-tag').on('click', function() {
        $('#add-tag-modal').modal();
    } )

    $('.edit-tag-btn').on('click', function() {
        console.log("editing-tag " + $(this).attr('data-id') );
        $('#edit-tag-modal .id-field').val( $(this).attr('data-id') );
        $('#edit-tag-modal .name-field').val( $(this).attr('data-name') );
        $('#edit-tag-modal').modal();
    } )

    $('.start-delete').on('click', function() {
        var id			= $(this).attr('data-id');
        $('.confirm-delete[data-id="'+id+'"]').toggleClass('invisible');
    } )

</script>
