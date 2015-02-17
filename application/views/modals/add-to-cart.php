<div class="modal fade" id="add-to-cart" style="z-index: 100000;">
    <div class="modal-dialog" style="width: 50%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">Add to cart!</h4>
            </div>
            <div class="modal-body">
                <div class="modal-body row">
                    <div class="col-md-3 col-xs-12">
                        <img src="" class="img-thumbnail preview" alt="" />
                    </div>
                    
                    <div class="form-group col-md-9 col-xs-12">
                        <input type="hidden" name="photo_id" />
                        <h3>Select Format</h3>
                        <input type="hidden" name="medium" >
                        <table class="table select-table">
                            <tr>
                                <th>Format</th>
                                <th class="text-right">Price</th>
                            </tr>
                            <?php foreach( $mediums as $medium ): ?>
                            <tr data-value="<?= $medium->id ?>">
                                <td>
                                    <?= $medium->name ?>
                                </td>
                                <td class="text-right">
     				    $<?= currency( $medium->price/100 ) ?> CAD
                                </td>
                            </tr>
                            
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
                <div class="modal-footer row">
                    <input type="button" class="btn btn-default"
                           data-dismiss="modal" value="Close"/>
                    <input class="btn btn-default" id="add-to-cart-btn" type="button"
                           value="Add Photo" />
                </div>
            </div>
        </div>
    </div>
</div>
    <script type="text/javascript">
    jQuery(document).ready(function () {
        var atc_photo_id	= $('#add-to-cart.modal input[name="photo_id"]');
        var atc_medium		= $('#add-to-cart.modal input[name="medium"]');
        var preview_image	= $('#add-to-cart.modal img.preview');

        $( '.select-table tr' ).on( 'click', function() {
            v = $(this);
            $(this).parent()
                .children( 'tr' ).removeClass( 'selected' );
            $(this).addClass('selected');
            atc_medium.val( $( this ).attr( 'data-value' ) );
        } );

        $( '.add-to-cart-open-modal' ).on( 'click', function() {
            var img_src		= '<?= PHOTO_URL ?>/' + $( this ).attr('data-photo-id') + '/small.jpg'
            preview_image.attr( 'src', img_src );
            atc_photo_id.val( $( this ).attr('data-photo-id') );
            console.log( atc_photo_id.val() );
        } );

        $('#add-to-cart-btn').on( 'click', function() {
            var id		= atc_photo_id.val();
            var medium		= atc_medium.val()
            if( ! medium ) {
                console.log( medium );
                atc_medium.css('border-color', 'red');
            }
            else {
                $('#add-to-cart.modal').modal( 'hide' );
                atc_medium.css('border-color', '#cccccc');
                Cart.add( id, medium );
                update_cart_count();
                $( this ).parent('.modal').modal();
            }
        } );
    })

    
</script>
