<div class="modal fade" id="feature-photo-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Feature Photo</h4>
            </div>
            <form action="<?= site_url('admin/feature_photo') ?>"
                  method="post" enctype="multipart/form-data">
                
                <div class="modal-body">
                    <div class="modal-body row">
                        <div class="text-center">
                            <img id="feature-photo-example" class="img-thumbnail"
                                 style="height: 90px;
                                        float: left;
                                        margin-top: 13px;" />
                            <br />
                            <div class="form-group col-md-8 col-md-offset-1">
                                <input type="hidden" name="photo_id" />
                                <select name="medium_id" class="form-control" >
                                    <option disabled selected>
                                        Select Format
                                    </option>
                                    <?php foreach( $mediums as $medium ): ?>
                                    <option value="<?= $medium->id ?>" >
                                        <?= $medium->name ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-8 col-md-offset-1">
                                <label class="col-lg-2">Price</label>
                                <div class="input-group col-lg-10">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" name="price" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer row">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">Close</button>
                        <input class="btn btn-primary" type="submit" value="Upload" name="submit" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
    <button type="button" class="btn featured btn-lg break-left pull-right" id="pick-featured">
        <b>pick featured</b>
    </button>
    <button type="button" class="btn btn-success btn-lg pull-right" id="add-photo">
        <b>add</b>
    </button>
</h1>
<table class="table table-striped table-align-middle" id="photos-table">
    <tr>
        <th>id</th>
        <th style="width: 80px;"></th>
        <th style="width: 80px"></th>
        <th></th>
        <th>Title</th>
        <th>Tags</th>
        <th>Comment</th>
        <th>Date Added</th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach( $photos as $key => $ph ): ?>
    <tr data-row-id="<?= $ph->id ?>" >
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
        
        <td class="<?= $ph->id == $featured->photo->id ? 'featured' :'' ?>">
            <?php if($ph->id == $featured->photo->id): ?>
            <span class="featured-details">
     		(<?= $featured->medium->name ?>/ $<?= currency($featured->price/100) ?>)
            </span>
            <?php endif; ?>
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
        if( window.featured_select_mode )
            return;

        $('#add-photo-modal').modal();
    } )

    $('.toggle-active-photo').on('click', function() {
        if( window.featured_select_mode )
            return;

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

    $('.start-delete').on('click', function() {
        if( window.featured_select_mode )
            return;

        var id			= $(this).attr('data-id');
        $('.confirm-delete[data-id="'+id+'"]').toggleClass('invisible');
    } );

    $( 'table#photos-table tr:has(td)' ).on( 'click', function() {
        if( ! window.featured_select_mode )
            return;

        $( '#feature-photo-modal' ).modal();
        var id			= $(this).attr('data-row-id');
        var url			= "<?= PHOTO_URL ?>/" + id +'/small.jpg'
        $( '#feature-photo-example' ).attr('src', url);
        $( '#feature-photo-modal [name = "photo_id"]' ).val( id );
    } );
    
    $( '#pick-featured' ).on( 'click', function() {
        window.featured_select_mode	= ! window.featured_select_mode;
        var featured_mode	= window.featured_select_mode;
        var text		= featured_mode
            ? 'cancel' : "pick featured" ;
        
        $( 'table#photos-table' ).toggleClass( 'featured-select', featured_mode );

        console.log('mode', featured_mode );
        console.log('text', text);
        $( this ).html( text );
    } );
</script>
