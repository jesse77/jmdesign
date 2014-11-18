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

<h1>PHOTOS
    <button type="button" class="btn btn-success btn-lg pull-right" id="add-photo">
        <b>add</b>
    </button>
</h1>

<table class="table table-striped table-align-middle">
    <tr>
        <th>id</th>
        <th></th>
        <th>Status</th>
        <th>Title</th>
        <th>Tags</th>
        <th>Comment</th>
        <th>Date Added</th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach( $photos as $key => $ph ): ?>
    <tr data-row-id="<?= $ph->id ?>">
        <td><?= $ph->id ?></td>
        <td>
            <img src="<?= PHOTO_URL.$ph->id ?>/xsmall.jpg" style="height: 50px" alt="" />
        </td>
        <td>
            <button type="button" data-id="<?= $ph->id ?>"
                    class="btn btn-success btn-xs toggle-active-photo
                           <?= $ph->active ? '' : 'hidden' ?>" >
                <b>active</b>
            </button>
            <button type="button" data-id="<?= $ph->id ?>"
                    class="btn btn-default btn-xs toggle-active-photo
                           <?= $ph->active ? 'hidden' : '' ?>" >
                <b>inactive</b>
            </button>
        </td>
        <td><?= $ph->title ?></td>
        <td><?= implode( $ph->tags, ', ' ) ?></td>
        <td><?= substr( $ph->comment, 0, 50) ?>...</td>
        <td><?= $ph->timestamp ?></td>
        <td class="col-xs-2">
            <form method="post" action="<?= site_url('admin/delete_photo') ?>" class="pull-right">
                <input type="hidden" name="id" value="<?= $ph->id ?>" />
                <input type="submit" data-id="<?= $ph->id ?>" value="Confirm Delete" 
                       class="btn btn-danger btn-sm invisible confirm-delete" />
                <input type="button" data-id="<?= $ph->id ?>"
                       class="btn btn-default btn-sm start-delete" value="Delete" />
            </form>
        </td>
        <td class="col-xs-1">
            <input type="button" class="btn btn-warning btn-sm edit-photo-btn" value="Edit"
                   data-id="<?= $ph->id ?>" />
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<script type="text/javascript">
    $('#add-photo').on('click', function() {
        $('#add-photo-modal').modal();
    } )

    $('.toggle-active-photo').on('click', function() {
        var id			= $(this).attr('data-id');
        $.ajax( {
            url: "<?= site_url('admin/toggle_active_photo') ?>/" + id,
            type: 'POST',
            success: function() {
                console.log( 'Toggled active' );
                var buttons	= $('[data-row-id = ' + id + '] .toggle-active-photo');
                $.each( buttons, function(i, button) {
                    if ( $(button).is(':visible') )
                        $(button).addClass('hidden');
                    else
                        $(button).removeClass('hidden');
                } )
            },
            error: function() {
                console.log( ['Did not work...', arguments ]);
            }
        } )
    } )

    function populate_form_with_json( form, data ) {
        $.each( data, function(name, val) {
            var $el			= $(form).find( '[name="'+name+'"]' )

            // For the photos tags.
            if( $.isArray( val ) ) {
                console.log( val );
                $.each( val, function( n, v ) {
                    console.log([n,v] );
                    var checkbox	= $(form).find( '[name="'+name+'[]"][data-name="'+v+'"]' )
                        .attr('checked', 'checked');
                } );
            }
            var type			= $el.attr('type');
            
            switch(type) {
            case 'checkbox':
                $el.attr( 'checked', 'checked' );
                break;
            case 'radio':
                $el.filter( '[value="' + val + '"]' ).attr( 'checked', 'checked' );
                break;
            default:
                $el.val(val);
            }
        });
    }

    $('.edit-photo-btn').on('click', function() {
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

    $('.start-delete').on('click', function() {
        var id			= $(this).attr('data-id');
        $('.confirm-delete[data-id="'+id+'"]').toggleClass('invisible');
    } )
</script>