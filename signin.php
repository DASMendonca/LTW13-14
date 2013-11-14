<?php

include 'classes.php';

$email = $_REQUEST['Email'];
$password = $_REQUEST['Password'];


$db = new PDO('sqlite:./database.sqlite');
$stmt="SELECT * FROM Customer WHERE Email=? AND Password=?;";
$costumers= Customer::getInstancesByFields($db,$_REQUEST);


if(count($costumers)==1){
	session_start();
	$_SESSION['customer']= $costumers[0];
	
}

header("Location: onlineInvoiceSystem.php");







?>