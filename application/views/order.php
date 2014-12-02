<div class="content">
    <div class="container">
        <p class="alert alert-info hidden" id="no-photo-alert">
            There are no photos in your cart! Check out the
            <b class="large">
                <a href="<?= site_url('gallery') ?>">Gallery </a>
            </b> to add them.
        </p>
        <?php if ( isset( $validation_errors ) ): ?>
        <p class="alert alert-info hidden" id="no-photo-alert">
            There are no photos in your cart! Check out the
            <b class="large">
                <a href="<?= site_url('gallery') ?>">Gallery</a>
            </b> to add them.
        </p>
        <?php endif; ?>
        <div class="col-md-12 hidden order-body">
            <table class="table table-striped table-align-middle">
                <thead>
                    <tr>
                        <th></th>
                        <th class="col-md-2"></th>
                        <th class="col-md-4">Format</th>
                        <th class="col-md-4">Name</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody class="order-container">
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right bold">Subtotal</td>
                        <td class="subtotal-cell">0</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right bold">Shipping</td>
                        <td class="shipping-cell">0</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right bold">Total</td>
                        <td class="total-cell">0</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                        </td>
                        <td>
                            <div class="btn btn-primary stripe-btn">
                                Checkout
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="divider">
</div>

<script src="https://checkout.stripe.com/checkout.js"></script>
<script type="text/javascript">
    $(document).ready( function() {

        function build_cart( cart )
        {
            if( ! cart.items ) {
                $('#no-photo-alert').removeClass('hidden')
                return false;
                console.log('Empty cart.');
            }

            $.each( cart.items, function(index, item) {
                table.append(
                    $('<tr/>').append([
                        $('<td/>').addClass('text-center')
                            .append(
                                $('<button/>')
                                    .addClass( 'btn btn-danger btn-sm' )
                                    .html( 'x' )
                                    .click( function() {
                                        Cart.remove(index);
                                        location.reload();
                                    } )
                            ),
                        $('<td/>')
                            .append( $( '<img />' )
                                     .attr( 'src', base_url + 'img/uploaded/' + item.photo.id + '/xsmall.jpg' )
                                     .addClass( 'img-thumbnail' ) ),
                        $('<td/>').html( item.medium.name ),
                        $('<td/>').html( item.photo.title ),
                        $('<td/>').html( '$' + item.medium.price / 100 + '.00' ),
                    ] )
                )
            } );

            $('.shipping-cell').html( '$' + cart.shipping / 100 + '.00' );
            $('.subtotal-cell').html( '$' + cart.subtotal / 100 + '.00' );
            $('.total-cell').html( '$' + cart.total / 100 + '.00' );
            
            $('.order-body').removeClass('hidden');
            
            // Configure custom stripe button.
            var handler		= StripeCheckout.configure( {
                key: 'pk_test_qWBwWNmkru63noM9zXZssZvC',
                image: '/img/tiny-logo.jpg',
                name: "Credit Card Information",
                address: true,
                phone: true,
                token: function( form ) {
                    console.log( [ "Stripe form: ", form ] );
                    Cart.charge( form );
                }
            } );

            $( '.stripe-btn' ).click( function() {
                handler.open( {
                    amount: cart.total,
                    description: Cart.items.length === 1
                        ? Cart.items.length + " Photo"
                        : Cart.items.length + " Photos",
                } );
            } );
            
        }

        var table		= $('.order-container');

        Cart.fetch();

        if( ! Cart.items ) {
            $('#no-photo-alert').removeClass( 'hidden' );
        }
        else {
            setTimeout( function() {

                var interval		= setInterval(  function() {
                    if( Cart.fetched === null )
                        return;
                    build_cart( Cart.fetched );
                    
                    cancel_interval();
                }, 100 );
                
                var cancel_interval	= function() {
                    clearInterval( interval );
                }
            }, 300 );
        }
    } )
    
</script>
