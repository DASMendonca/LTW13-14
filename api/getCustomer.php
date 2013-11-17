<?php

include '../classes.php';
header('Content-type: application/json');

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$costumers=array();

try {
	if(!isset($_GET["CustomerID"])) throw new GeneralException(new Err_MissingParameter("CustomerID"));

	$params=array(
			array("CustomerID",array($_GET["CustomerID"]),"equal")

	);

	$customers=Customer::getInstancesByFields($db, $params);
	if(count($customers)==0)throw new GeneralException(new Err_Not_Found("customers"));
	echo json_encode($customers[0]);

} catch (GeneralException  $e) {
	echo json_encode($e);
}

?>