<html>
<body>
<h1>There was an order placed for your pictures!</h1>
<?php if(ENVIRONMENT !== "production"): ?>
<h2>This was just a test</h2>
<?php endif; ?>
<div>
    <h3>Order</h3>
    <table>
        <tr>
            <th></th>
            <th></th>
            <th>Name</th>
            <th>Medium</th>
            <th>Price</th>
            <th>Shipping</th>
        </tr>
        <?php foreach( $cart['items'] as $item ): ?>
        <tr>
            <td><?= $item['photo']->id ?></td>
            <td><?= $item['featured_price'] ? 'FEATURED': '' ?></td>
            <td><?= $item['photo']->title ?></td>
            <td><?= $item['medium']->name ?></td>
            <td>$<?= currency( $item['featured_price'] ? $item['featured_price']/100 : $item['medium']->price/100 ) ?></td>
            <td>$<?= currency( $item['medium']->shipping/100 )?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4"></td>
            <td>Subtotal</td>
            <td>$<?= currency( $cart['subtotal']/100 ) ?></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>Shipping</td>
            <td>$<?= currency( $cart['shipping']/100 )?></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>Total</td>
            <td>$<?= currency( $cart['total']/100 ) ?></td>
        </tr>
    </table>

    <h3>Customer</h3>
    <div>
        Email:		<?= $order['email'] ?>
    </div>
    <div>
        Name:		<?= $order['card']['name'] ?>
    </div>
    
    <h3>Address</h3>
    <div>
        Country: <?= $order['card']['address_country'] ?> (<?= $order['card']['country'] ?>)
    </div>
    <div>
        Region:	<?= $order['card']['address_state'] ?>
    </div>
    <div>
        Address Line 1: <?= $order['card']['address_line1'] ?>
    </div>
    <div>
        Address Line 2: <?= $order['card']['address_line2'] ?>
    </div>
    <div>
        City: <?= $order['card']['address_city'] ?>
    </div>
    <div>
        ZIP Code: <?= $order['card']['address_zip'] ?>
    </div>
    <br /><br />

    <h4>Check Stripe for more information!</h4>
    <div>Order ID: <?= $order['id'] ?></div>
</div>
</body>
</html>
