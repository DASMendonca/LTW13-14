<?php

include 'classes.php';
header('Content-type: application/json');

try {
	$db = new PDO('sqlite:./database.sqlite');
	
	$customer=Customer::fromDB_Email_Pw($db, $_GET['email'], $_GET['password']);
	echo json_encode($customer);
} catch (Exception $e) {
	echo '{}';//send empty json
}



?>