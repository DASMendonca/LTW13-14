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


	if(!isset($_REQUEST["invoice"]))throw new GeneralException(new Err_MissingParameter("invoice"));

	$invoicePassed=json_decode($_REQUEST["invoice"]);
	$invoicePassedAsArray=(array) $invoicePassed;


	$user=$_SESSION["customer"];
	$userPermission=$user->Permission;





	if($userPermission<2 ) throw new GeneralException(new Err_PermissionDenied());//only admins and editors may edit/add products



	if(isset($invoicePassedAsArray["InvoiceNo"])){//if is an update
		array_push($parameters,array("InvoiceNo",$invoicePassedAsArray["InvoiceNo"]));
	}

	//at this point the user has the permissions necessary to do what it is doing

	if(isset($invoicePassedAsArray["InvoiceDate"])) {
		array_push($parameters,array("StartDate",$invoicePassedAsArray["InvoiceDate"]));
		array_push($parameters,array("EndDate",$invoicePassedAsArray["InvoiceDate"]));
	}
	if(isset($invoicePassedAsArray["CustomerID"]))array_push($parameters,array("CustomerID",$invoicePassedAsArray["CustomerID"]));
	if(isset($invoicePassedAsArray["CompanyName"]))array_push($parameters,array("CompanyName",$invoicePassedAsArray["CompanyName"]));
	
	//if there is either nothing to update or no nothing to insert
	if(count($parameters)<=1)throw new GeneralException(new Err_MalformedField("invoice"));
	
	
	
	if(isset($invoicePassedAsArray["InvoiceNo"]))$invoice=Invoice::updateInDB($db, $parameters);
	else $invoice=Invoice::instatiate($db, $parameters);
	
	
	for ($i=0;$i<count($invoicePassedAsArray["Line"]);$i++) {
		$line = $invoicePassedAsArray["Line"][$i];
		$Lines = Line::getInstancesByFields($db, array(array("InvoiceNo",array($invoice->InvoiceNo),"equal"),array("LineNo",array($line["LineNo"])),"equal"));
		if (count($Lines)==0) {
			$lineToInsert= new Line($invoice->InvoiceNo, $line["LineNo"], $line["Quantity"], $line["LineDate"]);
			$lineToInsert->Product=new Product($line["ProductCode"], $line["ProductDe"], $price, $unit, $typeID);
			$lineToInsert->Tax=new Tax(null, $value, $description);
			$lineToInsert->calculateCreditAmount();
			$lineToInsert->insertIntoDB($db);
		}
	}
	




	echo json_encode($invoice);


} catch (GeneralException $e) {
	echo json_encode($e);
}catch (PDOException $e) {

	$exception=new GeneralException(new Err_DBProblem($e));
	echo json_encode($exception);
}




?>