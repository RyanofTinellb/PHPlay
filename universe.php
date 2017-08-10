<head>
<style>
h1 {text-align: center; padding-top: 10%;}
</style>
</head>
<h1>
<?php
	echo 'Universe ';
	echo chr(rand(65, 64+26));
	echo '-';
	echo rand(1,5) == 1 ? rand(1,9999) : rand(1,999);
	echo '&#';
	echo rand(945, 969);
	echo ';<br>';
	echo '<br>';
	echo 'Stardate ';
	$j = rand(1, 999999) / 10;
	echo $j;
	echo $j == intval($j) ? '.0' : '';
	echo '<br>';
	echo 'Heading ' . rand(1, 360) . ' mark ' . rand(1,180) . ', warp ' . rand(1,9)
?>
</h1>
