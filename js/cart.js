var Cart			= new function() {

    this.items		= Modernizr.localstorage
        ? JSON.parse( localStorage.cart )
        : $.cookie( 'cart' );

    this.fetched	= null;
    this.charged	= null;

    this.fetch		= function() {
        var $this	= this;
        $.ajax( {
            url: base_url + 'api/get_cart',
            data: {
                cart: JSON.stringify( this.items )
            },
            type: 'POST',
            success: function( json ) {
                var cart			= JSON.parse( json );
                console.log( [ "Got results!", cart ] );
                console.log( "Total: " + cart.total );
                $this.fetched			= cart;
                return true;
            },
            error: function() {
                console.log( ['Getting cart did not work...', arguments ]);
                $this.fetched			= {};
                return false;
            }
        } );
    };
    
    this.charge		= function( form_data ) {
        var $this	= this;
        $.ajax( {
            url: base_url + 'order/confirm_order',
            data: {
                stripe: form_data,
                cart: JSON.stringify( $this.items )
            },
            type: 'POST',
            success: function( json ) {
                console.log( ['Charge successful', arguments ]);
                $this.empty();
                $this.charged	= true;
                return true;
            },
            error: function() {
                console.log( ['Charging your card did not work...', arguments ]);
                $this.charged	= false;
                return false;
            }
        } );
    };
    
    this.empty			= function() {
        this.items = [];
        this.synchronize();
    };
    
    this.add			= function( item, type ) {

        if( typeof item !== "number" )
            console.log( "Non-numeric item id given." );

        if( typeof type !== "number" )
            console.log( "Non-numeric medium id given." );

        console.log( ["Adding item to cart: ", item, type ] );
        this.items.push( {
            "medium_id": type,
            "photo_id": item
        } );
        this.synchronize();
    };

    this.synchronize		= function() {
        if( Modernizr.localstorage ) {
            console.log(["Synchronizing this.items to localStorage."]);
            localStorage['cart']	= this.items
                ? JSON.stringify( this.items )
                : '';
        } else {
            console.log(["Synchronizing this.cart to cart cookie."]);
            $.cookie( 'cart', this.items );
        }
    };

    this.remove		= function( index ) {
        this.items.splice( index, 1 );
        this.synchronize();
    };
};
