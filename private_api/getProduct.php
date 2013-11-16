<?php

include '../classes.php';

header('Content-type: application/json');

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$parameters=array(
	array("City",array("Porto"),"equal")
);

try {
	if(!isset($_GET['params']))
		throw new GeneralException(new Err_MissingParameter("params"));	
	$products=Product::getInstancesByFields($db, $_GET["params"]);
	echo json_encode($products);
} catch (GeneralException $e) {
	echo json_encode($e);
}


?>