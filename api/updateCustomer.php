<?php

include '../classes.php';

session_start();//resume session

header('Content-type: application/json');
$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);




try {
	
	
	if(!isset($_POST["customer"]))throw new GeneralException(new Err_MissingParameter("parameter"));
	
	$authenticationResult=authenticate($_REQUEST["customer"], $_POST["customer"], $_REQUEST["salted_request"]);
	
	if($authenticationResult==FALSE)throw new GeneralException(new Err_Autentication());
	
	

	
	
	
} catch (GeneralException $e) {
	echo json_encode($e);
}




function authenticate($customer,$requesObject,$recievedResult){
	
	$requestObjectJson=json_encode($requesObject);
	$pw=$customer->Password;
	$result=sha1($requestObjectJson.$pw);
	if(strcmp($result, $recievedResult)==0)return TRUE;
	return FALSE;
	
}


?>