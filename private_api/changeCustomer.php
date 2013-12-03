<?php

include 'classes.php';

header('Content-type: application/json');


try {
	
	
	if(!isset($POST["parameters"])) throw new GeneralException(new Err_MissingParameter("parameters"));
	
	$parameters=$POST["parameters"];
	
	
	for($i=0;count($parameters);$i++){
		
		$columnName=$parameters[$i][0];
		if($columnName!=)
		
	}
	
	
	
	
} catch (GeneralException $e) {
	echo json_encode($e);
}




?>