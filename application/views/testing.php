<h1 class="title">Unit Testing</h1>

<h3>Models</h3>

<ul class="inline cols-5 disc">
    <?php
     	foreach( $models as $m ) {
     	printf( '<li>%s</li>', $m );
 	}
     	 ?>
</ul>

<table class="table table-condensed table-striped">

    <thead>
        <tr>
            <th></th>
            <th>Expected</th>
            <th>Result</th>
        </tr>
    </thead>

    <?php
     	foreach( $categories as $c ):
     	 ?>
    <tbody>

        <tr class="left-align border-top border-purple">
            <th colspan="3">
                <?php
     		    printf( '<h2 class="no-head-space"><span class="%s">%s:</span> %s Tests <small>in %s Methods</small></h2>',
			    $c->color, $c->name, $c->test_count, $c->method_count );
		?>
            </th>
        </tr>

        <?php
	    foreach( $c->methods as $method => $tests ):
	?>

        <tr class="left-align">
            <th colspan="3">
                <span class="gap"></span>
                <?php
     		    printf( '%s', $method );
		     ?>
            </th>
        </tr>

        <?php
	    foreach( $tests as $test ):
	     ?>

        <tr>
            <td>
                <span class="gap"></span>
                <span class="gap"></span>
                <?php
     		    printf( '<span class="%s">%s:</span> %s %s',
			    $c->color, $c->name, $test['Test Name'],
			    $test['note'] );
		?>
            </td>
            <td>
                <?php echo $test['Expected Datatype'] ?>
            </td>
            <td>
                <?php echo $test['Test Datatype'] ?>
            </td>
        </tr>

        <?php
	    endforeach;
	    endforeach;
	     ?>

    </tbody>

    <?php
    	endforeach;
	 ?>

</table>
