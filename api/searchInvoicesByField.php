<?php
include '../classes.php';
session_start();
header('Content-type: application/json');

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);






$invoices=array();



try {
	
	if(!isset($_SESSION["customer"]))throw new GeneralException(new Err_Autentication());
	
	if(!isset($_GET["field"])) throw new GeneralException(new Err_MissingParameter("field"));
	if(!isset($_GET["value"])) throw new GeneralException(new Err_MissingParameter("value"));
	if(!isset($_GET["op"])) throw new GeneralException(new Err_MissingParameter("op"));
	
	$params=array(
			array($_GET["field"],$_GET["value"],$_GET["op"])
	
	);
	if($_SESSION["customer"]->Permission<3){//if it doesnt have permission to see other's Invoices
		$params[1]=array("CustomerID",array($_SESSION["customer"]->CustomerID),"equal");//Add another Constraint
	}
	
	$invoices=Invoice::getInstancesByFields($db, $params);
	
	
	
	
	
	
	echo json_encode($invoices);
	
} catch (GeneralException  $e) {
	echo json_encode($e);
	
}catch (PDOException $e) {
	
	$exception=new GeneralException(new Err_DBProblem($e));
	echo json_encode($exception);
}

?>