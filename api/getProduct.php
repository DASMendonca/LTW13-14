<?php

include '../classes.php';
header('Content-type: application/json');
session_start();

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$products=array();

try {
	
	if(!isset($_SESSION["customer"]))throw new GeneralException(new Err_Autentication());
	
	if(!isset($_GET["ProductCode"])) throw new GeneralException(new Err_MissingParameter("ProductCode"));
	
	$params=array(
			array("ProductCode",array($_GET["ProductCode"]),"equal")

	);

	$products=Product::getInstancesByFields($db, $params);
	if(count($products)==0)throw new GeneralException(new Err_Not_Found("products"));
	echo json_encode($products[0]);

} catch (GeneralException  $e) {
	echo json_encode($e);
}catch (PDOException $e) {
	
	$exception=new GeneralException(new Err_DBProblem($e));
	echo json_encode($exception);
}



?>