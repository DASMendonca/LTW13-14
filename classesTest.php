
<?php
include 'classes.php';

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$parameters=array(
	array("ProductCode",array(2),"equal"),
);

$result=Product::getInstancesByFields($db, $parameters);
?>