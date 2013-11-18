<?php

include 'classes.php';
header('Content-type: application/json');

$db = new PDO('sqlite:./database.sqlite');
	
	$customers=Customer::getInstancesByFields($db, $_GET['params']);
	
	if(count($customers)==1){
		echo json_encode($customers[0]);
	}
	else echo '{}';




?>