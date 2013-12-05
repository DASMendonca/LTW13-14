<?php

header('Content-type: application/json');
include '../classes.php';

session_start();

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);






$customers=array();	
try {
	if(!isset($_SESSION["customer"]))throw new GeneralException(new Err_Autentication());
	else if($_SESSION["customer"]->Permission<3)throw new GeneralException(new Err_PermissionDenied());
	
	if(!isset($_GET["field"])) throw new GeneralException(new Err_MissingParameter("field"));
	if(!isset($_GET["value"])) throw new GeneralException(new Err_MissingParameter("value"));
	if(!isset($_GET["op"])) throw new GeneralException(new Err_MissingParameter("op"));
	
	
	$params=array(
		array($_GET["field"],$_GET["value"],$_GET["op"])
	);
	
	
	$customers=Customer::getInstancesByFields($db, $params);
	echo json_encode($customers);
} catch (GeneralException $e) {
	echo json_encode($e);
}





?>