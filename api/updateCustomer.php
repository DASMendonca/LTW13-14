<?php

include '../classes.php';

session_start();//resume session

header('Content-type: application/json');
$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);




try {
	if(!isset($_POST["customer"]))throw new GeneralException(new Err_Autentication());
	
	
	

	
	
	
} catch (GeneralException $e) {
	echo json_encode($e);
}



?>