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

	$user=$_SESSION["customer"];
	$userPermission=$user->Permission;

	if($userPermission!=3 ) throw new GeneralException(new Err_PermissionDenied());//only admins and editors may edit/add products
	
	if(!isset($_REQUEST["url"]))throw new GeneralException(new Err_MissingParameter("url"));
	
	$urlPassed=$_REQUEST["url"];
	
	$url = $urlPassed."/api/searchCustomersByField.php?op=min&field=CustomerID&value[]=1";
	
	$json = file_get_contents($url);
	$customers = json_decode($json);
		
	for ($i=0;i<count($customers);$i++) {
		$customerToInsert = new Customer(null, $customers[i]->CustomerTaxID, $customers[i]->CompanyName, $customers[i]->Email, "1234", null);
		$postalCode = explode('-', $customers[$i]->BillingAddress->PostalCode);
		$customerToInsert = new Address($customers[i]->BillingAddress->AddressDetail, $customers[i]->BillingAddress->City, $postalCode[0], $postalCode[1], $customers[i]->BillingAddress->Country);
		
		$customerToInsert->insertIntoDB($db);
	}
	
	$productsJSON = file_get_contents($urlPassed."/api/searchProductsByField.php?op=min&field=ProductCode&value[]=1");
	
	$products = json_decode($productsJSON);
	
	for ($i=0;i<count($products);$i++) {
		$productsToInsert = new Product($products[i]->ProductCode, $products[i]->ProductDescription, $products[i]->UnitPrice, $products[i]->UnitOfMeasure, 1);
	
		$productsToInsert->insertIntoDB($db);
	}
	
	
	
} catch (GeneralException $e) {
	echo json_encode($e);
} catch (PDOException $e) {

	$exception=new GeneralException(new Err_DBProblem($e));
	echo json_encode($exception);
}




?>
	
	
	
	