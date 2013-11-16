<?php

header('Content-type: application/json');
include '../classes.php';

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$params=array(
	array($_GET["field"],$_GET["value"],$_GET["op"])
);


$products=array();	
try {
	$products=Product::getInstancesByFields($db, $params);
} catch (Exception $e) {
	
}

echo json_encode($products);

?>
