<?php

include '../classes.php';
header('Content-type: application/json');

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$invoices=array();

try {
	if(!isset($_GET["InvoiceNo"])) throw new GeneralException(new Err_MissingParameter("field"));
	
	$params=array(
			array("InvoiceNO",$_GET["InvoiceNo"],"equal")

	);

	$invoices=Invoice::getInstancesByFields($db, $params);
	echo json_encode($invoices);

} catch (GeneralException  $e) {
	echo json_encode($e);
}



?>