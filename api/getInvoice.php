<?php

include '../classes.php';
header('Content-type: application/json');

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$invoices=array();

try {
	if(!isset($_GET["InvoiceNo"])) throw new GeneralException(new Err_MissingParameter("InvoiceNo"));
	
	$params=array(
			array("InvoiceNO",array($_GET["InvoiceNo"]),"equal")

	);

	$invoices=Invoice::getInstancesByFields($db, $params);
	if(count($invoices)==0)throw new GeneralException(new Err_Not_Found("invoices"));
	echo json_encode($invoices[0]);

} catch (GeneralException  $e) {
	echo json_encode($e);
}



?>