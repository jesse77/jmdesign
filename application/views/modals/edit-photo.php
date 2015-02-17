<div class="modal fade" id="edit-photo-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Edit Photo</h4>
            </div>
            <form action="<?= site_url('admin/edit_photo') ?>"
                  method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" />
                <div class="modal-body">
                    <div class="modal-body">
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
                                <input type="checkbox" name="tags[]" data-name="<?= $tag->name ?>" value="<?= $tag->id ?>"/>
                                <span><?= $tag->name ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">Close</button>
                        <input class="btn btn-primary" type="submit" value="Update Photo" name="submit" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function() {
        $('.edit-photo-btn').on( 'click', function() {
            if( window.featured_select_mode )
                return;
            
            var id			= $(this).attr('data-id');
            $.ajax( {
                url: "<?= site_url('admin/photo_json') ?>/" + id,
                type: 'POST',
                data: 'json',
                success: function( data ) {
                    console.log( 'Got photo json' );
                    console.log(data)
                    var json	= JSON.parse( data );
                    populate_form_with_json( $( '#edit-photo-modal form' ), json );
                    $('#edit-photo-modal').modal();
                },
                error: function() {
                    console.log( ['Did not work...', arguments ]);
                }
            } )
        } );
    } )
</script>
