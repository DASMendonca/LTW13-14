<?php

include 'classes.php';

$email = $_REQUEST['Email'];
$password = $_REQUEST['Password'];

$params = array(array("Email", array($email), "equal"), array("Password", array($password), "equal"));

$db = new PDO('sqlite:./database.sqlite');
$stmt="SELECT * FROM Customer WHERE Email=? AND Password=?;";
$costumers= Customer::getInstancesByFields($db, $params);


if(count($costumers)==1){
	session_start();
	$_SESSION['customer']= $costumers[0];
	
}

header("Location: onlineInvoiceSystem.php");







?>