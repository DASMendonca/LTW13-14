<?php

include '../classes.php';

session_start();//resume session

header('Content-type: application/json');
$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

try {
	
	
	if(!isset($_POST["customer"]))throw new GeneralException(new Err_MissingParameter("parameter"));
	
	$customer=json_decode($_POST["customer"],true);
	
	if(issetr)
	

	
	
	
	if(isset($_POST["reloadSession"]) && $_POST["reloadSession"]==TRUE){//if the customer we are editing is the one stored in session than update it
		$_SESSION["customer"]=$customer;//change sessionCustomer
	}
	
	
	
} catch (GeneralException $e) {
	echo json_encode($e);
}




?>