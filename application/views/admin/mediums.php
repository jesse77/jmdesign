<div class="modal fade" id="add-medium-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">New Medium</h4>
            </div>
            <form action="<?= site_url('admin/add_medium') ?>"
                  method="post" enctype="multipart/form-data">
                
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-lg-2">Name</label>
                            <div class="input-group col-lg-6">
                                <input type="text" name="name" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2">Price</label>
                            <div class="input-group col-lg-6">
                                <span class="input-group-addon">$</span>
                                <input type="text" name="price" class="form-control"/>
                                <span class="input-group-addon">.00</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2">Shipping</label>
                            <div class="input-group col-lg-6">
                                <span class="input-group-addon">$</span>
                                <input type="text" name="shipping" class="form-control"/>
                                <span class="input-group-addon">.00</span>
                            </div>
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

<div class="modal fade" id="edit-medium-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">New Medium</h4>
            </div>
            <form action="<?= site_url('admin/edit_medium') ?>"
                  method="post" enctype="multipart/form-data">
                
                <div class="modal-body">
                    <div class="modal-body">
                        <input type="hidden" name="id" class="id-field" />
                        <div class="form-group">
                            <label>Medium Name</label>
                            <input type="text" name="name" class="form-control name-field"/>
                        </div>
                        <div class="form-group">
                            <label>Medium Price</label>
                            <input type="text" name="price" class="form-control price-field"/>
                        </div>
                        <div class="form-group">
                            <label>Medium Shipping</label>
                            <input type="text" name="shipping" class="form-control shipping-field"/>
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

<div class="col-md-12">
    <h1>Mediums
        <button type="button" class="btn btn-success btn-lg pull-right" id="add-medium">
            <b>add</b>
        </button>
    </h1>
    
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Price</th>
                <th>Shipping</th>
            </tr>
        </thead>
        <?php foreach( $mediums as $key => $medium ): ?>
        <tr>
            <td><?= $medium->id ?></td>
            <td><?= $medium->name ?></td>
            <td>$<?= $medium->price / 100 ?>.00</td>
            <td>$<?= $medium->shipping / 100 ?>.00</td>
            <td class="col-xs-2">
                <form method="post" action="<?= site_url('admin/delete_medium') ?>" class="pull-right">
                    <input type="hidden" name="id" value="<?= $medium->id ?>" />
                    <input type="submit" data-id="<?= $medium->id ?>" value="Confirm Delete" 
                           class="btn btn-danger btn-sm invisible confirm-delete" />
                    <input type="button" data-id="<?= $medium->id ?>"
                           class="btn btn-default btn-sm start-delete" value="Delete" />
                </form>
            </td>
            <td class="col-xs-1">
                <input type="button" class="btn btn-warning btn-sm edit-medium-btn" value="Edit"
                       data-id="<?= $medium->id ?>" data-name="<?= $medium->name ?>"
                       data-shipping="<?= $medium->shipping / 100 ?>" data-price="<?= $medium->price / 100 ?>" />
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<script type="text/javascript">
    $('#add-medium').on('click', function() {
        $('#add-medium-modal').modal();
    } )

    $('.edit-medium-btn').on('click', function() {
        console.log("editing-medium " + $(this).attr('data-id') );
        $('#edit-medium-modal .id-field').val( $(this).attr('data-id') );
        $('#edit-medium-modal .name-field').val( $(this).attr('data-name') );
        $('#edit-medium-modal .price-field').val( $(this).attr('data-price') );
        $('#edit-medium-modal .shipping-field').val( $(this).attr('data-shipping') );
        $('#edit-medium-modal').modal();
    } )

    $('.start-delete').on('click', function() {
        var id			= $(this).attr('data-id');
        $('.confirm-delete[data-id="'+id+'"]').toggleClass('invisible');
    } )

</script>
