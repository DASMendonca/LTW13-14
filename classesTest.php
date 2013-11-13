
<?php
include 'classes.php';

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$params=array(array("TaxValue",13),array("TaxID",2));
$query=constructSelect("Tax", $params,$db);
//$query=$db->prepare("Select * from Tax WHERE  TaxValue = ? AND TaxID = ?;");


$res=$query->execute();
$result=$query->fetchAll();
?>