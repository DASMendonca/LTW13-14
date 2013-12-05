<?php

include '../classes.php';

session_start();
header('Content-type: application/json');

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$invoices=array();

try {
	if(!isset($_GET["InvoiceNo"])) throw new GeneralException(new Err_MissingParameter("InvoiceNo"));
	if(!isset($_SESSION["customer"])) throw new GeneralException(new Err_Autentication());
	$params=array(
			array("InvoiceNo",array($_GET["InvoiceNo"]),"equal")

	);
	
	

	
	
	
	

	$invoices=Invoice::getInstancesByFields($db, $params);
	if(count($invoices)==0)throw new GeneralException(new Err_Not_Found("invoices"));
	
	
	
	$logedInUser=$_SESSION["customer"];
	$InvoiceOwner=$invoices[0]->getCustomerId();
	
	if(($logedInUser->Permission==0 || $logedInUser->Permission==1) && $logedInUser->CustomerID!=$InvoiceOwner) throw  new GeneralException(new Err_PermissionDenied());
	
	
	
	
	echo json_encode($invoices[0]);

} catch (GeneralException  $e) {
	echo json_encode($e);
}



?>