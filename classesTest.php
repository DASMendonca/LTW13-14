
<?php
include 'classes.php';

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$parameters=array(
	array("TaxID",array(0,3),"range"),
	array("TaxValue",array(13),"equal"),
);

$result=Tax::getInstancesByFields($db, $parameters);
?>