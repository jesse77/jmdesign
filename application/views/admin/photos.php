<?php $this->load->view('modals/feature-photo') ?>
<?php $this->load->view('modals/add-photo') ?>
<?php $this->load->view('modals/edit-photo') ?>

<div class="<?= $featured ? 'col-md-9' : 'col-md-12' ?>" style="border-right: solid black 1px">
    <h1>
        PHOTOS
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
            <th>Title</th>
            <th>Tags</th>
            <th>Comment</th>
            <th>Date Added</th>
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
            
            <td><?= $ph->title ?></td>
            <td><?= implode( $ph->tags, ', ' ) ?></td>
            <td><?= substr( $ph->comment, 0, 50) ?>...</td>
            <td><?= $ph->timestamp ?></td>
            <td class="col-xs-3">
                <input type="button" class="btn btn-warning btn-sm edit-photo-btn pull-right"
                       value="Edit" data-id="<?= $ph->id ?>" style="margin-left:5px"/>
                <form method="post" action="<?= site_url('admin/delete_photo') ?>" class="pull-right">
                    <input type="hidden" name="id" value="<?= $ph->id ?>" />
                    <input type="submit" data-id="<?= $ph->id ?>" value="R U Sure?" 
                           class="btn btn-danger btn-sm invisible confirm-delete" />
                    <input type="button" data-id="<?= $ph->id ?>"
                           class="btn btn-default btn-sm start-delete" value="Delete" />
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php if( $featured ): ?>
<div class="col-md-3">
    <h1>
        Featured
        <button type="button" class="btn btn-danger btn-lg pull-right" id="unfeature-btn">
            <b>remove</b>
        </button>
    </h1>
    
    <img src="<?= PHOTO_URL.$featured->photo->id ?>/small.jpg"
         class="img-thumbnail col-md-6" />
    <div class="col-md-6">
        <h4>Title</h4>
        <?= $featured->photo->title ?>
        <h4>Medium</h4>
        <?= $featured->medium->name ?>
        <h4>Price</h4>
        $<?= currency( $featured->price /100 )?>
    </div>
</div>
<?php endif; ?>
<script type="text/javascript">
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

    $( '#unfeature-btn' ).on( 'click', function() {
        $.ajax( {
            url: "<?= site_url('admin/cancel_featured') ?>/",
            type: 'POST',
            success: function( data ) {
                location.reload();
            },
            error: function() {
                console.log( ['Did not work...', arguments ]);
            }
        } )
    })

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
</script>
