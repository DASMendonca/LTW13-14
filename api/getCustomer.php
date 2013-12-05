<?php

include '../classes.php';
session_start();
header('Content-type: application/json');

session_start();

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$costumers=array();

try {
	if(!isset($_GET["CustomerID"])) throw new GeneralException(new Err_MissingParameter("CustomerID"));
	if(!isset($_SESSION["customer"])) throw new GeneralException(new Err_Autentication());
	
	$params=array(
			array("CustomerID",array($_GET["CustomerID"]),"equal")

	);
	
	$logedInUser=$_SESSION["customer"];
	$queriedID=$_GET["CustomerID"];
	
	if(($logedInUser->Permission==0 || $logedInUser->Permission==1) && $logedInUser->CustomerID!=$queriedID) throw  new GeneralException(new Err_PermissionDenied());
	

	$customers=Customer::getInstancesByFields($db, $params);
	if(count($customers)==0)throw new GeneralException(new Err_Not_Found("customers"));
	echo json_encode($customers[0]);

} catch (GeneralException  $e) {
	echo json_encode($e);
}

?>