<?php
include 'classes.php';
session_start();

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);



if(isset($_REQUEST['DELETION'])){

$invoiceQuery=array("InvoiceNo", array($_POST['DELETION'][1]), "equal");

$params = array(array("LineNo", array($_POST['DELETION'][0]), "equal"),
					$invoiceQuery);

$the_one= Line::getInstancesByFields($db, $params);
$the_one= $the_one[0];

$the_one->removeFromDB($db);

$the_invoice = Invoice::getInstancesByFields($db, array($invoiceQuery));
$the_invoice= $the_invoice[0];

echo json_encode($the_invoice);
}


?>
