<?php

include '../classes.php';

session_start();//resume session

header('Content-type: application/json');
$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

try {
	
	if(!isset($_SESSION["customer"])) throw new GeneralException(new Err_Autentication());
	
	
	
	if(!isset($_POST["parameters"]) || count($_POST["parameters"])==0 ) throw new GeneralException(new Err_MissingParameter("parameters"));
	
	$parameters=$_POST["parameters"];
	
	
	
	$id=$parameters[0][1];
	if($id!="") {//update
		$product =Product::updateInDB($db, $parameters);
		
		echo json_encode($product);
	}
	else{
		
		if($_SESSION["customer"]->Permission<2) throw new GeneralException(new Err_PermissionDenied());
		$product=Product::instatiate($db,$parameters);
		echo json_encode($product);
	}
	

	
	
	
} catch (GeneralException $e) {
	echo json_encode($e);
}




?>