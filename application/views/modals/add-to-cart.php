<div class="modal fade" id="add-to-cart" style="z-index: 100000">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">Add Photo</h4>
            </div>
            <form action="<?= site_url('admin/upload') ?>"
                  method="post" enctype="multipart/form-data">
                
                <div class="modal-body">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Select Medium</label>
                            <select name="type">
                                <option value="Print - 60">
                                <option value="Print - 30">
                                <option value="Rights">
                                <option value="Commercial Use">
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="button" class="btn btn-default"
                                data-dismiss="modal" value="Close" class="close"/>
                        <input class="btn btn-default" type="submit"
                               value="Upload Photo" name="submit" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    /*----------------------------------------------------*/
    /*	Add To Cart
    /*----------------------------------------------------*/
    $( document ).ready( function () {
        $( '[data-action="add-to-cart"]' ).on( 'click', function() {
            $( '#add-to-cart' ).modal();
        } );
        
        $( '' ).on( 'click', function() {
            $(this).parent('.modal').modal();
        } );
    } )        
</script>
