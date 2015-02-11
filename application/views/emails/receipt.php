
<html>
<body>
<h1>Thank you for your purchase from jessemartineau.com!</h1>
<div>
    <h3>Order</h3>
    <table>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Medium</th>
            <th>Price</th>
            <th>Shipping</th>
        </tr>
        <?php foreach( $cart['items'] as $item ): ?>
        <tr>
            <td><?= $item['photo']->id ?></td>
            <td><?= $item['photo']->title ?></td>
            <td><?= $item['medium']->name ?></td>
            <td>$<?= $item['medium']->price/100 ?>.00</td>
            <td>$<?= $item['medium']->shipping/100 ?>.00</td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4"></td>
            <td>Subtotal</td>
            <td>$<?= $cart['subtotal']/100 ?>.00</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>Shipping</td>
            <td>$<?= $cart['shipping']/100 ?>.00</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>Total</td>
            <td>$<?= $cart['total']/100 ?>.00</td>
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
</div>
</body>
</html>