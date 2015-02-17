<div class="modal fade" id="add-photo-modal">
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
                                <span><?= $tag->name ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">Close</button>
                        <input class="btn btn-primary" type="submit" value="Upload Photo" name="submit" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function() {
        $('#add-photo').on('click', function() {
            if( window.featured_select_mode )
                return;

            $('#add-photo-modal').modal();
        } )
    })
</script>
