<?php

header('Content-type: application/json');
include '../classes.php';

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$params=array(
	array($_GET["field"],$_GET["value"],$_GET["op"])
);


$customers=array();	
try {
	$customers=Customer::getInstancesByFields($db, $params);
	echo json_encode($customers);
} catch (ApiException $e) {
	echo json_encode($e);
}





?>