<?php
	
include '../classes.php';
header('Content-type: application/json');


try{
	if(!isset($_POST["customer"])){throw new GeneralException(new Err_MissingParameter("customer"));}
	
	$customer=$_POST["customer"];
	if($customer->CustomerID=""){//TODO check if should check for null
		
	}
	
	
}
catch(GeneralException $e){
	echo json_encode($e);
	
}


?>