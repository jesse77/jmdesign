<div class="modal fade" id="add-image-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add Photo</h4>
            </div>
            <form action="<?= site_url('admin/upload') ?>"
                  method="post" enctype="multipart/form-data">
                
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="form-group no-border">
                            <label>Select a file</label>
                            <input type="file" name="file"/>
                        </div>

                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control"/>
                        </div>
                        
                        <div class="form-group">
                            <label>Comment</label>
                            <textarea name="comment" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Tags</label>
                            <?php foreach( $tags as $tag ): ?>
                            <div>
                                <input type="checkbox" name="tags[]" value="<?= $tag->id ?>"/>
                                <?= $tag->id ?>>
                                <span><?= $tag->name ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">Close</button>
                        <input type="submit" value="Upload Image" name="submit" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<h1>PHOTOS
    <button type="button" class="btn btn-success btn-lg pull-right" id="add-image">
        <b>add</b>
    </button>
</h1>

<table class="table table-striped">
    <tr>
        <th>id</th>
        <th>Status</th>
        <th>Title</th>
        <th>Tags</th>
        <th>Comment</th>
        <th>Date Added</th>
    </tr>
    <?php foreach( $photos as $key => $ph ): ?>
    <tr>
        <td><?= $ph->id ?></td>
        <td>
            <button type="button" class="btn btn-success btn-xs">
                <b>active</b>
            </button>
        </td>
        <td><?= $ph->title ?></td>
        <td><?= implode( $ph->tags, ', ' ) ?></td>
        <td><?= substr( $ph->comment, 0, 50) ?>...</td>
        <td><?= $ph->timestamp ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<script type="text/javascript">
    $('#add-image').on('click', function() {
        $('#add-image-modal').modal();
    } )

    $('#add-image').on('click', function() {
        $('#add-image-modal').modal();
    } )
</script>
