<?php

include 'classes.php';

$email = $_GET['email'];
$password = $_GET['password'];


$db = new PDO('sqlite:./database.sqlite');
$stmt="SELECT * FROM Customer WHERE Email=? AND Password=?;";
$costumer= Customer::fromDB_Email_Pw($db, $email, $password);

try {
	$costumer= Customer::fromDB_Email_Pw($db, $email, $password);
	session_start();
	$_SESSION['costumer']= $costumer;
	header("Location: mainPage.html");
	die();
} 
catch (NotFoundException $e) {//case where there is no such a user


}
catch (DBInconsistencyException $e) {//case where ther 
}





?>