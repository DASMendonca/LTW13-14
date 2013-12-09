<?php

include '../classes.php';

session_start();
header('Content-type: application/json');

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$invoices=array();

try {
	if(!isset($_GET["InvoiceNo"])) throw new GeneralException(new Err_MissingParameter("InvoiceNo"));
	if(!isset($_SESSION["customer"])) throw new GeneralException(new Err_Autentication());
	$params=array(
			array("InvoiceNo",array($_GET["InvoiceNo"]),"equal")

	);
	
	$invoices=Invoice::getInstancesByFields($db, $params);
	if(count($invoices)==0)throw new GeneralException(new Err_Not_Found("invoices"));
	
	
	
	$logedInUser=$_SESSION["customer"];
	$InvoiceOwner=$invoices[0]->getCustomerId();
	
	if(($logedInUser->Permission==0 || $logedInUser->Permission==1) && $logedInUser->CustomerID!=$InvoiceOwner) throw  new GeneralException(new Err_PermissionDenied());
	
	$stringFinal='[';
	
	$currentInvoiceNo = $invoices[0]->InvoiceNo;
	$currentInvoiceDate = $invoices[0]->EndDate;
	$currentInvoiceCompany = $invoices[0]->Customer->CompanyName;
	$currentInvoiceCompanyID = $invoices[0]->Customer->CustomerID;
	$currentInvoiceTotal = number_format($invoices[0]->GrossTotal/100,2);
	$InvoiceLines = $invoices[0]->getLines();
	
	$subTotal=0;
	$taxAmount=0;
	
	$currentInvoiceLines = '[';
	for ($j=0; $j<(count($InvoiceLines)-1); $j++) {
		$LineNumber = $InvoiceLines[$j]->LineNo;
		$ProductCode = $InvoiceLines[$j]->Product->ProductCode;
		$Quantity = $InvoiceLines[$j]->Quantity;
		$UnitPrice = number_format($InvoiceLines[$j]->Product->UnitPrice/100,2);
		$CreditAmount = number_format($InvoiceLines[$j]->CreditAmount,2);
		$Tax = json_encode($InvoiceLines[$j]->Tax);
	
		$subTotal+=$CreditAmount;
	
		$currentInvoiceLines.='{"LineNumber" : "'.$LineNumber.'",
					"ProductCode" : "'.$ProductCode.'",
							"Quantity" : "'.$Quantity.'",
									"UnitPrice" : "'.$UnitPrice.'",
											"CreditAmount" : "'.$CreditAmount.'",
													"Tax" : '.$Tax.'},';
	}
	
	$LineNumber = $InvoiceLines[$j]->LineNo;
	$ProductCode = $InvoiceLines[$j]->Product->ProductCode;
	$Quantity = $InvoiceLines[$j]->Quantity;
	$UnitPrice = number_format($InvoiceLines[$j]->Product->UnitPrice/100,2);
	$CreditAmount = number_format($InvoiceLines[$j]->CreditAmount,2);
	$Tax = json_encode($InvoiceLines[$j]->Tax);
	
	$subTotal+=$CreditAmount;
	
	$currentInvoiceLines.='{"LineNumber" : "'.$LineNumber.'",
					"ProductCode" : "'.$ProductCode.'",
							"Quantity" : "'.$Quantity.'",
									"UnitPrice" : "'.$UnitPrice.'",
											"CreditAmount" : "'.$CreditAmount.'",
													"Tax" : '.$Tax.'}';
	
	$currentInvoiceLines .= ']';
	
	$taxPayable = $currentInvoiceTotal-$subTotal;
	
	$documentsTotal = '{"TaxPayable" : "'.$taxPayable.'",
				"NetTotal" : "'.$subTotal.'",
						"GrossTotal" : "'.$currentInvoiceTotal.'"}';
	
	$stringFinal.='{"InvoiceNo" : "'.$currentInvoiceNo.'",
				"InvoiceDate" : "'.$currentInvoiceDate.'",
						"CustomerID" : "'.$currentInvoiceCompanyID.'",
								"CompanyName" : "'.$currentInvoiceCompany.'",
										"Lines" : '.$currentInvoiceLines.',
												"DocumentTotals" :  '.$documentsTotal.'}';
	
	$stringFinal.=']';
	
	
	echo $stringFinal;

} catch (GeneralException  $e) {
	echo json_encode($e);
}catch (PDOException $e) {
	
	$exception=new GeneralException(new Err_DBProblem($e));
	echo json_encode($exception);
}



?>