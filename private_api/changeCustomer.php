<?php

include 'classes.php';

header('Content-type: application/json');
$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

try {
	
	
	
	if(!isset($POST["parameters"]) || count($POST["parameters"])==0 ) throw new GeneralException(new Err_MissingParameter("parameters"));
	
	$parameters=$POST["parameters"];
	
	
	
	$id=$parameters[1][0];
	if($id="") json_encode(Customer::updateInDB($db, $parameters));
	
	
	
	
} catch (GeneralException $e) {
	echo json_encode($e);
}




?>