<?php
include '../classes.php';
session_start();
header('Content-type: application/json');

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);






$invoices=array();



try {

	if(!isset($_SESSION["customer"]))throw new GeneralException(new Err_Autentication());

	if(!isset($_GET["field"])) throw new GeneralException(new Err_MissingParameter("field"));
	if(!isset($_GET["value"])) throw new GeneralException(new Err_MissingParameter("value"));
	if(!isset($_GET["op"])) throw new GeneralException(new Err_MissingParameter("op"));

	if (strcmp($_GET["field"],"InvoiceDate")==0) $_GET["field"]="EndDate";
	
	$params=array(
			array($_GET["field"],$_GET["value"],$_GET["op"])

	);
	if($_SESSION["customer"]->Permission<3){//if it doesnt have permission to see other's Invoices
		$params[1]=array("CustomerID",array($_SESSION["customer"]->CustomerID),"equal");//Add another Constraint
	}

	$invoices=Invoice::getInstancesByFields($db, $params);

	$stringFinal='[';

	for ($i=0;$i<(count($invoices)-1);$i++){
		$currentInvoiceNo = $invoices[$i]->InvoiceNo;
		$currentInvoiceDate = $invoices[$i]->EndDate;
		$currentInvoiceCompany = $invoices[$i]->Customer->CompanyName;
		$currentInvoiceCompanyID = $invoices[$i]->Customer->CustomerID;
		$currentInvoiceTotal = number_format($invoices[$i]->GrossTotal/100,2);
		$InvoiceLines = $invoices[$i]->getLines();

		$subTotal=0;
		$taxAmount=0;

		$currentInvoiceLines = '[';
		for ($j=0; $j<(count($InvoiceLines)-1); $j++) {
			$LineNumber = $InvoiceLines[$j]->LineNo;
			$ProductCode = $InvoiceLines[$j]->Product->ProductCode;
			$Quantity = $InvoiceLines[$j]->Quantity;
			$UnitPrice = number_format($InvoiceLines[$j]->Product->UnitPrice/100,2);
			$CreditAmount = number_format($InvoiceLines[$j]->CreditAmount/100,2);
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
		$CreditAmount = number_format($InvoiceLines[$j]->CreditAmount/100,2);
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
										"Line" : '.$currentInvoiceLines.',
												"DocumentTotals" :  '.$documentsTotal.'},';
	}
	
	$currentInvoiceNo = $invoices[$i]->InvoiceNo;
	$currentInvoiceDate = $invoices[$i]->EndDate;
	$currentInvoiceCompany = $invoices[$i]->Customer->CompanyName;
	$currentInvoiceCompanyID = $invoices[$i]->Customer->CustomerID;
	$currentInvoiceTotal = number_format($invoices[$i]->GrossTotal/100,2);
	$InvoiceLines = $invoices[$i]->getLines();
	
	$subTotal=0;
	$taxAmount=0;
	
	$currentInvoiceLines = '[';
	for ($j=0; $j<(count($InvoiceLines)-1); $j++) {
		$LineNumber = $InvoiceLines[$j]->LineNo;
		$ProductCode = $InvoiceLines[$j]->Product->ProductCode;
		$Quantity = $InvoiceLines[$j]->Quantity;
		$UnitPrice = number_format($InvoiceLines[$j]->Product->UnitPrice/100,2);
		$CreditAmount = number_format($InvoiceLines[$j]->CreditAmount/100,2);
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
	$CreditAmount = number_format($InvoiceLines[$j]->CreditAmount/100,2);
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
										"Line" : '.$currentInvoiceLines.',
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