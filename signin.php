<?php

include 'classes.php';

$email = $_REQUEST['emailInput'];
$password = $_REQUEST['pwInput'];


$db = new PDO('sqlite:./database.sqlite');
$stmt="SELECT * FROM Customer WHERE Email=? AND Password=?;";
$costumer= Customer::fromDB_Email_Pw($db, $email, $password);

session_start();
$_SESSION['customer']= $email;
$_SESSION['pwd']= $password;

try {
	$costumer= Customer::fromDB_Email_Pw($db, $email, $password);
	session_start();
	$_SESSION['costumer']= $costumer;
	header("Location: onlineInvoiceSystem.php");
	die();
} 
catch (NotFoundException $e) {//case where there is no such a user


}
catch (DBInconsistencyException $e) {//case where ther 
}





?>