<?php
include 'classes.php';

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$parameters=array(
	array("CustomerID",2),
	array("Email","ois4@ois.com"),
	array("CompanyName","Isadorinha")
		
		
);

$query=constructUpdate("Customer", $parameters, $db);
//$query=constructInsert("Customer", $parameters, $db);
$query->execute();

?>
