var Cart			= new function() {

    this.items		= Modernizr.localstorage
        ? JSON.parse( localStorage.cart || '[]' )
        : $.cookie( 'cart' );
    
    this.get			= function() {
        $.ajax( {
            url: base_url + 'api/get_cart',
            data: {
                cart: JSON.stringify( this.items )
            },
            type: 'POST',
            success: function( json ) {
                var cart		= JSON.parse( json );
                console.log( [ "Got results!", cart ] );
                return cart;
            },
            error: function() {
                console.log( ['Getting cart did not work...', arguments ]);
            }
        } );
    };
    
    this.empty			= function() {
        this.items = {};
        this.synchronize();
    };
    
    this.add			= function( item, type ) {
        console.log( ["Adding item to cart: ", item, type ] );
        this.items.push( {
            "type": type,
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
        delete this.items[ index ];
        this.synchronize();
    };
};
