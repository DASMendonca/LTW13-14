<?php

include '../classes.php';

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$parameters=array(
	array("City",array("Porto"),"equal")
);

try {
	$products=Product::getInstancesByFields($db, $_GET["params"]);
	echo json_encode($products);
} catch (ApiException $e) {
	echo json_encode($e);
}


?>