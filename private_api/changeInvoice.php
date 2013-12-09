<?php


include '../classes.php';

session_start();//resume session

header('Content-type: application/json');
$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

try {

	if(!isset($_REQUEST["invoiceNo"]) || !isset($_REQUEST["state"])) throw new GeneralException(new Err_MissingParameter("parameters"));
	
	$queryArr= array(array("InvoiceNo", array($_REQUEST["invoiceNo"]), "equal"));

	$invoices= Invoice::getInstancesByFields($db, $queryArr);
	$invoice = $invoices[0];
	
	$toUpdate= array(array("InvoiceNo", $_REQUEST["invoiceNo"]), array("Status", $_REQUEST["state"]));
	
	$invoice->updateInDB($db, $toUpdate);
		
	
	
	
	} catch (GeneralException $e) {
		echo json_encode($e);
	}
	
	
	
	
	?>