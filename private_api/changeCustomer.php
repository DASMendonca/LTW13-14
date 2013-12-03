<?php

include 'classes.php';

header('Content-type: application/json');


try {
	
	
	if(!isset($POST["parameters"]) || count($POST["parameters"])==0 ) throw new GeneralException(new Err_MissingParameter("parameters"));
	
	$parameters=$POST["parameters"];
	
	
	
	$id=$parameters[1][0];
	if($id="") 
	
	
	
	
} catch (GeneralException $e) {
	echo json_encode($e);
}




?>