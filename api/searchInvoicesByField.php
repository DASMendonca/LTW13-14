<?php
include 'classes.php';

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$invoices=array();

try {
	if(!isset($_GET["field"])) throw new GeneralException(new Err_MissingParameter("field"));
	if(!isset($_GET["value"])) throw new GeneralException(new Err_MissingParameter("value"));
	if(!isset($_GET["op"])) throw new GeneralException(new Err_MissingParameter("op"));
	
	$params=array(
			array($_GET["field"],$_GET["value"],$_GET["op"])
	
	);
	
	$invoices=Invoice::getInstancesByFields($db, $params);
	echo json_encode($invoices);
	
} catch (GeneralException  $e) {
	echo json_encode($e);
}

?>