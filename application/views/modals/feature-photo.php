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
                            <img id="feature-photo-example" class="img-thumbnail col-md-3" />
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
<script type="text/javascript">
    $(document).ready( function() {
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
    } )
</script>
