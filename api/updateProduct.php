<?php


include '../classes.php';

session_start();//resume session

header('Content-type: application/json');
$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

try {

	$parameters=array();

	if(!isset($_SESSION["customer"])) throw new GeneralException(new Err_Autentication());


	if(!isset($_REQUEST["product"]))throw new GeneralException(new Err_MissingParameter("product"));

	$productPassed=json_decode($_REQUEST["product"]);
	$productPassedAsArray=(array) $productPassed;


	$user=$_SESSION["customer"];
	$userPermission=$user->Permission;





	if($userPermission<2 ) throw new GeneralException(new Err_PermissionDenied());//only admins and editors may edit/add products



	if(isset($productPassedAsArray["ProductCode"])){//if is an update
		array_push($parameters,array("ProductCode",$productPassedAsArray["ProductCode"]));
	}


	//at this point the user has the permissions necessary to do what it is doing

	if(isset($productPassedAsArray["ProductDescription"]))array_push($parameters,array("ProductDescription",$productPassedAsArray["ProductDescription"]));
	if(isset($productPassedAsArray["UnitPrice"]))array_push($parameters,array("UnitPrice",$productPassedAsArray["UnitPrice"]));
	if(isset($productPassedAsArray["UnitOfMeasure"]))array_push($parameters,array("UnitOfMeasure",$productPassedAsArray["UnitOfMeasure"]));
	if(isset($productPassedAsArray["ProductTypeID"]))array_push($parameters,array("ProductTypeID",$productPassedAsArray["ProductTypeID"]));
	if(!isset($productPassedAsArray["ProductTypeID"]))array_push($parameters,array("ProductTypeID","1"));
	



	//if there is either nothing to update or no nothing to insert
	if(count($parameters)<=1)throw new GeneralException(new Err_MalformedField("product"));



	if(isset($productPassedAsArray["ProductCode"]))$product=Product::updateInDB($db, $parameters);
	else $product=Product::instatiate($db, $parameters);


	echo json_encode($product);


} catch (GeneralException $e) {
	echo json_encode($e);
}catch (PDOException $e) {

	$exception=new GeneralException(new Err_DBProblem($e));
	echo json_encode($exception);
}




?>