<?php
include 'classes.php';

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$fields=array(
	array("CustomerID",array(4),"equal")
		
		
);
try {
	$Invoices= Invoice::getInstancesByFields($db, $fields);
} catch (Exception $e) {
	echo $e;
}

?>
