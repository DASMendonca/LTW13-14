<?php 
class GeneralException extends Exception{
	
	
	public $error;
	
	function __construct($err){
		$this->error=$err;	
	}
}
class ApiError {
	
	public $code;
	public $reason;
	public $field;
	
	
};

class SimpleError {
	
	public $code;
	public $reason;
}


class NotFoundException extends Exception {};



class Err_MissingParameter extends ApiError {
	
	function __construct($field){
		$this->code="701";
		$this->reason="Missing Parameter";
		$this->field=$field;
		
	}
}

class Err_WrongNumberValues extends ApiError {
	
	function __construct($field){
		
		$this->code="702";
		$this->reason="Wrong number of arguments";
		$this->field=$field;
		
	}
}


class Err_UnknownField extends ApiError{
	
	function __construct($fieldName){
		$this->code="703";
		$this->reason="Unknown field";
		$this->field=$fieldName;
		
	}
}

class Err_UnknownOp extends ApiError{
	
	function __construct($fieldName){
		$this->code="704";
		$this->reason="Unknown operation";
		$this->field=$fieldName;
	}
	
}



class Err_Not_Found extends SimpleError{
	
	function __construct($entityName){
		$this->code="404";
		$this->reason="No $entityName found";
	}
	
}


class Err_Autentication extends SimpleError{
	
	function __construct(){
		$this->code="999";
		$this->reason="Failed Autentication";
	}
}

class Err_PermissionDenied extends SimpleError{
	
	
	function __construct(){
		$this->code="998";
		$this->reason="Permission Denied";
	}
}

class Err_MalformedField extends SimpleError{
	
	public function __construct($fieldName){
		
		$this->code="997";
		$this->reason="Malformed Parameter";
		$this->field=$fieldName;
			
	}
	
}

class Err_DBProblem extends SimpleError{
	
	public function __construct($exception){
		$this->code="996";
		$this->reason="DB error";
		$this->field=$exception->getMessage();
	}
}

interface savable
{
	public function insertIntoDB($db);
	static public function getInstancesByFields($db,$fields);
	//static public function updateInDB($db,$parameters);
}
interface changable{
	static public function updateInDB($db,$parameters);
	
	
}

class Invoice implements savable,changable{
	
	public $InvoiceNo;
	public $StartDate;
	public $EndDate;
	public $Customer;
	public $GenerationDate;
	public $Status;
	protected $Lines;
	public $GrossTotal;
	
	function __construct($InvoiceNo,$StartDate,$EndDate,$Status){
		
		$this->InvoiceNo=$InvoiceNo;
		$this->StartDate=$StartDate;
		$this->EndDate=$EndDate;
		$this->Status=$Status;		
		
		
	}
	function setLines($Lines){
		$this->Lines=$Lines;
		$this->GrossTotal=0;
		for($i=0;$i<count($this->Lines);$i++){
			$this->GrossTotal+=$this->Lines[$i]->CreditAmount*($this->Lines[$i]->Tax->TaxPercentage/100+1);
		}
		usort($this->Lines, "lineComparator");
		
	}
	function getCustomerId(){
		return $this->Customer->CustomerID;
	}
	static public function updateInDB($db,$parameters){
	
		
		
		for($i=0;$i<count($parameters);$i++){
			$columnName=$parameters[$i][0];
			if(!Invoice::isColumn($columnName))throw new GeneralException(new Err_UnknownField($columnName));
		}
		
		$unchangedInvoice =Invoice::getInstancesByFields($db, array(array($parameters[0][0],array($parameters[0][1]),"equal")))[0];
		if($unchangedInvoice->Status==1)throw new GeneralException(new Err_PermissionDenied());//not allowed to change generated Invoices
	
		$query=constructUpdate("Invoice", $parameters, $db);
		$result=$query->execute();
	
		if($result) return Invoice::getInstancesByFields($db, array(array($parameters[0][0],array($parameters[0][1]),"equal")))[0];
	
	
	
	}
	public function insertIntoDB($db){
		
		if($this->GenerationDate==null || !isset($this->GenerationDate))$this->GenerationDate=(new DateTime())->format('Y-m-d H:i:s');//now time
		$missing=$this->missingParameter();
		if($missing!=null)throw new GeneralException(new Err_MissingParameter($missing));
		
		$stmt="Insert into Invoice (StartDate,EndDate,CustomerID,CompanyName,CustomerTaxID,Email,AddressDetail,PostalCode1,PostalCode2,City,Country,GenerationDate,Status) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->StartDate);
		$query->bindParam(2,$this->EndDate);
		$query->bindParam(3,$this->Customer->CustomerID);
		$query->bindParam(4,$this->Customer->CompanyName);
		$query->bindParam(5,$this->Customer->CustomerTaxID);
		$query->bindParam(6,$this->Customer->Email);
		$query->bindParam(7,$this->Customer->BillingAddress->AddressDetail);
		$query->bindParam(8,$this->Customer->BillingAddress->PostalCode1);
		$query->bindParam(9,$this->Customer->BillingAddress->PostalCode2);
		$query->bindParam(10,$this->Customer->BillingAddress->City);
		$query->bindParam(11,$this->Customer->BillingAddress->Country);
		$query->bindParam(12,$this->GenerationDate);
		$query->bindParam(13,$this->Status);
		
		$query->execute();
		

		
		if(isset($this->lines) && $this->lines!=null){
			for($i=0;$i<count(lines);$i++){
				$lines[$i]->insertIntoDB($db);
			}
		}
		
		return $db->lastInsertId();
		
		
		
		
	}
	static public function getInstancesByFields($db,$fields){
		
		$params=array();
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(Invoice::isColumn($entry[0])){
				array_push($params, $entry);
			}
			else throw new GeneralException(new Err_UnknownField($entry[0]));
		}
		
		$query=constructSelect("Invoice", $params, $db);
		$query->execute();
		$result=$query->fetchAll();
		$instances=array();
		for($i=0;$i<count($result);$i++){
			$entry=$result[$i];
				
			$instance=new Invoice($entry["InvoiceNo"],$entry["StartDate"],$entry["EndDate"],$entry["Status"]);
			
			$LineFields=array(
				array("InvoiceNo",array($instance->InvoiceNo),"equal")
			);
			$lines=Line::getInstancesByFields($db,$LineFields);
			$instance->setLines($lines);
			
			$instance->Customer=new Customer($entry["CustomerID"], $entry["CustomerTaxID"], $entry["CompanyName"], $entry["Email"], null, null);
			$instance->Customer->BillingAddress=new Address($entry["AddressDetail"], $entry["City"], $entry["PostalCode1"], $entry["PostalCode2"], $entry["Country"]);
			$instance->GenerationDate=$entry["GenerationDate"];
			$instances[$i]=$instance;
		}
		
		return $instances;
		
		
	}
	function getLines(){
		return $this->Lines;
	}
	static public function isColumn($candidate){
	
		$columns=array("InvoiceNo","StartDate","EndDate","CustomerID","AddressDetail","PostalCode1","PostalCode2","City","Country","GenerationDate","Status","CompanyName","CustomerTaxID","Email");
		for($i=0;$i<count($columns);$i++){
			if(strcmp($candidate, $columns[$i])==0)return TRUE;
		}
	
		return FALSE;
	
	}
	public function missingParameter(){
		
		
		
		if($this->StartDate==null || !isset($this->StartDate))return"StartDate";
		else if($this->EndDate==null || !isset($this->EndDate))return"EndDate";
		else if($this->GenerationDate==null || !isset($this->GenerationDate))return"GenerationDate";
		else if(!isset($this->Status))return"Status";//not null cause it can be zero
		else if($this->Customer==null || !isset($this->Customer))return"Customer";
		return $this->Customer->missingParameter();
		
	}
	public function toXML(){
		$invoiceTemplate=simplexml_load_file("./invoice_xml/InvoiceTemplate.xml");
		$invoiceTemplate->InvoiceNo=$this->InvoiceNo;
		$invoiceTemplate->DocumentStatus->InvoiceStatusDate=$this->GenerationDate;
		$invoiceTemplate->DocumentStatus->SourceID=$this->Customer->CustomerID;
		$invoiceTemplate->InvoiceDate=$this->GenerationDate;
		$invoiceTemplate->SourceID=$this->Customer->CustomerID;
		$invoiceTemplate->SystemEntryDate=$this->GenerationDate;
		$invoiceTemplate->CustomerID=$this->Customer->CustomerID;
		$NetTotal=0;
		for($i=0;$i<count($this->Lines);$i++){
			$NetTotal+=$this->Lines[$i]->CreditAmount;
		}
		$invoiceTemplate->DocumentTotals->NetTotal=$NetTotal/100;
		$invoiceTemplate->DocumentTotals->GrossTotal=$this->GrossTotal/100;
		$invoiceTemplate->DocumentTotals->TaxPayable=($this->GrossTotal-$NetTotal)/100;
		
		usort($this->Lines,"lineComparator");
		for($i=count($this->Lines)-1;$i>=0;$i--){
			$LineToAdd=simplexml_load_string($this->Lines[$i]->toXML());
			simplexml_insert_after($LineToAdd, $invoiceTemplate->CustomerID);
		}
		
		return $invoiceTemplate->asXML();
	}
	static public function fromXML($xmlString){
		$invoiceXML=simplexml_load_string($xmlString);
		$invoice=new Invoice((string)$invoiceXML->InvoiceNo, (string)$invoiceXML->InvoiceDate,(string) $invoiceXML->InvoiceDate, 1);
		$invoice->GenerationDate=(string)$invoiceXML->SystemEntryDate;
		$invoice->GrossTotal=((string) $invoiceXML->DocumentTotals->GrossTotal)*100;
		
		$lines=array();
		for($i=0;$i<count($invoiceXML->Line);$i++){
			$lineXML=$invoiceXML->Line[$i];
			$line=Line::fromXML($lineXML->asXML());
			array_push($lines, $line);
			
		}
		$invoice->setLines($lines);
		$invoice->Customer=new Customer((string)$invoiceXML->CustomerID, null,null,null,null,null);
		return $invoice;
		
	}
	static public function exportSAFT_File($invoices){
	
		$customers=array();
		$products=array();
		$taxes=array();
		$minDate;
		$maxDate;
	
		$minDate=$invoices[0]->StartDate;
		$maxDate=$invoices[0]->EndDate;
	
		for($i=0;$i<count($invoices);$i++){
			$currentInvoice=$invoices[$i];
				
			$minDateTimeStamp=strtotime($minDate);
			$maxDateTimeStamp=strtotime($maxDate);
			$currentDateTimeStamp=strtotime($currentInvoice->StartDate);
				
			if($minDateTimeStamp>$currentDateTimeStamp)$minDate=$currentInvoice->StartDate;
			if($maxDateTimeStamp<$currentDateTimeStamp)$maxDate=$currentInvoice->EndDate;
				
			for($l=0;$l<count($currentInvoice->Lines);$l++){
				$currentLine=$currentInvoice->Lines[$l];
				$currentProduct=$currentLine->Product;
				$currentTax=$currentLine->Tax;
				addIfNotRepeated($products, $currentProduct);
				addIfNotRepeated($taxes, $currentTax);
			}
			$currentCustomer=$currentInvoice->Customer;
			addIfNotRepeated($customers,$currentCustomer);
				
		}
	
		$headerXML=simplexml_load_file("./invoice_xml/HeaderTemplate.xml");
		$headerXML->FiscalYear=explode("-", $minDate)[0];
		$headerXML->StartDate=$minDate;
		$headerXML->EndDate=$maxDate;
		$headerXML->DateCreated=(new DateTime())->format('Y-m-d');
	
		$masterFilesXML=simplexml_load_string("./invoice_xml/MasterFilesTemplate.xml");
	
		for($i=0;$i<count($customers);$i++){
			$customerXMLElement=simplexml_load_string($customers[$i]->toXML());
			simplexml_insert_before($customerXMLElement, $masterFilesXML->TaxTable);
		}
		for($i=0;$i<count($products);$i++){
			$productXMLElement=simplexml_load_string($products[$i]->toXML());
			simplexml_insert_before($productXMLElement, $masterFilesXML->TaxTable);
		}
		for($i=0;$i<count($customers);$i++){
			$TaxXMLElement=simplexml_load_string($taxes[$i]->toXML());
			$masterFilesXML->TaxTable->TaxTableEntry[$i]=$TaxXMLElement;
				
		}
	
	
	
	}

}
class Line implements savable{
	
	public $InvoiceNo;
	public $LineNo;
	public $Quantity;
	public $CreditAmount;
	public $Tax;
	public $Product;
	public $LineDate;
	
	
	function __construct($InvoiceNumber,$LineNo,$Quantity,$LineDate){
		
		$this->InvoiceNo=$InvoiceNumber;
		$this->LineNo=$LineNo;
		$this->Quantity=$Quantity;
		$this->LineDate=$LineDate;
	}
	public function insertIntoDB($db){
		
		if($this->LineDate==null || !isset($this->LineDate))$this->LineDate=(new DateTime())->format('Y-m-d');//now time
		$missing=$this->missingParameter();
		if($missing!=null)throw new GeneralException(new Err_MissingParameter($missing));
		
		$stmt="Insert into Invoice_Line (InvoiceNo,LineNo,Quantity,LineDate,ProductCode,ProductDescription,UnitPrice,UnitOfMeasure,TaxValue,TaxType) VALUES(?,?,?,?,?,?,?,?,?,?)";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->InvoiceNo);
		$query->bindParam(2,$this->LineNo);
		$query->bindParam(3,$this->Quantity);
		$query->bindParam(4,$this->LineDate);
		$query->bindParam(5,$this->Product->ProductCode);
		$query->bindParam(6,$this->Product->ProductDescription);
		$query->bindParam(7,$this->Product->UnitPrice);
		$query->bindParam(8,$this->Product->UnitOfMeasure);
		$query->bindParam(9,$this->Tax->TaxValue);
		$query->bindParam(10,$this->Tax->TaxType);
		$query->execute();
		
		return $db->lastInsertId();
		
	}
	static public function getInstancesByFields($db,$fields){
		
		$params=array();
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(Line::isColumn($entry[0])){
				array_push($params, $entry);
			}
			else throw new GeneralException(new Err_UnknownField($entry[0]));	
		}
		
		$query=constructSelect("Invoice_Line", $params, $db);
		$query->execute();
		$result=$query->fetchAll();
		$instances=array();
		for($i=0;$i<count($result);$i++){
			$entry=$result[$i];
			

			$instance=new Line($entry["InvoiceNo"],$entry["LineNo"], $entry["Quantity"],$entry["LineDate"]);
			$instance->Product=new Product($entry["ProductCode"], $entry["ProductDescription"], $entry["UnitPrice"], $entry["UnitOfMeasure"], null);
			$instance->Tax=new Tax(null, $entry["TaxValue"], $entry["TaxType"]);
			$instance->calculateCreditAmount();
			$instances[$i]=$instance;
		}
		
		return $instances;
			
	}
	public function calculateCreditAmount(){
		$this->CreditAmount=$this->Product->UnitPrice*$this->Quantity;
	}
	public function toXML(){
		
		$lineTemplate=simplexml_load_file("./invoice_xml/LineTemplate.xml");
		$lineTemplate->LineNumber=$this->LineNo;
		$lineTemplate->ProductCode=$this->Product->ProductCode;
		$lineTemplate->ProductDescription=$this->Product->ProductDescription;
		$lineTemplate->Quantity=$this->Quantity;
		$lineTemplate->UnitOfMeasure=$this->Product->UnitOfMeasure;
		$lineTemplate->UnitPrice=$this->Product->UnitPrice;
		$lineTemplate->Description=$this->Product->ProductDescription;
		$lineTemplate->CreditAmount=$this->CreditAmount;
		$lineTemplate->Tax->TaxType=$this->Tax->TaxType;
		$lineTemplate->Tax->TaxPercentage=$this->Tax->TaxPercentage;
		$lineTemplate->TaxPointDate=$this->LineDate;
		return $lineTemplate->asXML();
	}
	static public function fromXML($xmlString){
		$lineXML=simplexml_load_string($xmlString);
		$line=new Line(null, (string) $lineXML->LineNumber, (string) $lineXML->Quantity,(string) $lineXML->TaxPointDate);
		$line->Product=new Product((string) $lineXML->ProductCode, (string) $lineXML->ProductDescription, (string) $lineXML->UnitPrice, (string) $lineXML->UnitOfMeasure, null);
		$line->Tax=new Tax(null,(string) $lineXML->Tax->TaxPercentage,(string) $lineXML->Tax->TaxType);
		$line->calculateCreditAmount();
		return $line;
		
	}
	static public function isColumn($candidate){
		if(strcmp($candidate, "LineNo")==0)return true;
		else if(strcmp($candidate,"InvoiceNo")==0)return true;
		else if(strcmp($candidate,"Quantity")==0)return true;
		else if(strcmp($candidate,"LineDate")==0)return true;
		else if(Product::isColumn($candidate))return true;
		else return Tax::isColumn($candidate);
	}
	public function missingParameter(){
		if($this->InvoiceNo==null || !isset($this->InvoiceNo))return "InvoiceNo";
		if($this->LineNo==null || !isset($this->LineNo))return "LineNo";
		if($this->Quantity==null || !isset($this->Quantity))return "Quantity";
		if($this->Date==null || !isset($this->Date))return "Date";
		$missingProductParam=$this->Product->missingParameter();
		if($missingProductParam)return $missingProductParam;
		return $this->Tax->missingParameter();
	}
	static public function updateInDB($db,$parameters){
		for($i=0;$i<count($parameters);$i++){
			$columnName=$parameters[$i][0];
			if(!Line::isColumn($columnName))throw new GeneralException(new Err_UnknownField($columnName));
		}
		
		if(strcmp($parameters[0][0],"InvoiceNo")!=0)throw new GeneralException(new Err_MissingParameter("InvoiceNo"));
		if(strcmp($parameters[1][0],"LineNo")!=0)throw new GeneralException(new Err_MissingParameter("LineNo"));
		
		
		$query=constructUpdate("Invoice_Line", $parameters, $db,2);
		$result=$query->execute();
		
		$getBackParams=array(
			array("InvoiceNo",array($parameters[0][1]),"equal"),
			array("LineNo",array($parameters[1][1]),"equal")	
		);
		
		$lines= Line::getInstancesByFields($db,$getBackParams);
		if($lines!=null && count($lines)>0)return $lines[0];
		return null;
		
		
		
	}
	public function removeFromDB($db){
		
		$stmt="DELETE FROM Invoice_Line where LineNo= ? AND InvoiceNo= ?";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->LineNo);
		$query->bindParam(2,$this->InvoiceNo);
		$query->execute();
		
		$selectParams=array(
			array("InvoiceNo",array($this->InvoiceNo),"equal"),
			array("LineNo",array($this->LineNo),"min")
				
				
		);
		
		//decrease number of all lines
		$lines=Line::getInstancesByFields($db, $selectParams);
		usort($lines, "lineComparator");
		for($i=0;$i<count($lines);$i++){
			$theLine=$lines[$i];
			$updateParams=array(
					array("InvoiceNo",$theLine->InvoiceNo),
					array("LineNo",$theLine->LineNo),
					array("LineNo",$theLine->LineNo-1)
			);
			Line::updateInDB($db, $updateParams);
		}
		
		
		
	}
	

}
class Customer implements savable,changable{
	
	public $CustomerID;
	public $CustomerTaxID;
	public $CompanyName;
	public $BillingAddress;
	public $Email;
	public $Password;
	public $Permission;
	
	function __construct($ID,$TaxID,$Name,$email,$pw,$permissions){
		
		$this->CustomerID=$ID;
		if($TaxID>=0)$this->CustomerTaxID=$TaxID;//TODO: maybe use a validating function later
		else $this->CustomerTaxID=null;

		$this->CompanyName=$Name;
		
		$this->Email=$email;
		$this->Password=$pw;
		if($permissions==null)
			$this->Permission=1;
		else 
			$this->Permission=$permissions;//TODO: maybe validate these permissions
		
		
		
		
	}
	function insertIntoDB($db){
		
		
		
		if($this->Permission==null || !isset($this->Permission))$this->Permission=0;
		
		$missing=$this->missingParameter();
		
		if($missing!=null)throw new GeneralException(new Err_MissingParameter($missing));
		
		$stmt="Insert into Customer (CustomerTaxID,CompanyName,Email,AddressDetail,PostalCode1,PostalCode2,City,Country,Password,Permission) Values(?,?,?,?,?,?,?,?,?,?);";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->CustomerTaxID);
		$query->bindParam(2,$this->CompanyName);
		$query->bindParam(3,$this->Email);
		$query->bindParam(4,$this->BillingAddress->AddressDetail);
		$query->bindParam(5,$this->BillingAddress->PostalCode1);
		$query->bindParam(6,$this->BillingAddress->PostalCode2);
		$query->bindParam(7,$this->BillingAddress->City);
		$query->bindParam(8,$this->BillingAddress->Country);
		$query->bindParam(9,$this->Password);
		$query->bindParam(10,$this->Permission);
		
		$query->execute();
		
		
		return $db->lastInsertId();
		
		
	}
	
	/*
	 * 
	 * This method recieves an array. Each of the array entries are supposed to be an arry with to positions:
	 * 0-> the column name , 1-> column value
	 * The first of this pairs is not a value to be changed, it's the matching parameter instead (the one that will go in the where clause of the update) tipically it should be the ID
	 * 
	 * 
	 */
	
	static public function updateInDB($db,$parameters){
		
		for($i=0;$i<count($parameters);$i++){
			$columnName=$parameters[$i][0];
			if(!Customer::isColumn($columnName))throw new GeneralException(new Err_UnknownField($columnName));
		}
		
		
		
		
		$query=constructUpdate("Customer", $parameters, $db);
		$result=$query->execute();
		
		if($result) return Customer::getInstancesByFields($db, array(array($parameters[0][0],array($parameters[0][1]),"equal")))[0];
		
		
		
	}
	
	static public function getInstancesByFields($db,$fields){
		

		$params=array();
		
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(Customer::isColumn($entry[0])){
				array_push($params, $entry);
			}
			else throw new GeneralException(new Err_UnknownField($entry[0]));
		}
		
		
		$query=constructSelect("Customer", $params, $db);
		$query->execute();
		$result=$query->fetchAll();
		$instances=array();
		for($i=0;$i<count($result);$i++){
			$entry=$result[$i];
			$instance=new Customer($entry["CustomerID"], $entry["CustomerTaxID"], $entry["CompanyName"], $entry["Email"], $entry["Password"], $entry["Permission"]);
			$instance->BillingAddress=new Address($entry["AddressDetail"], $entry["City"],$entry["PostalCode1"], $entry["PostalCode2"], $entry["Country"]);
			$instances[$i]=$instance;
		}

		return $instances;
	}
	
	static public function isColumn($candidate){
	
		$columns=array("CustomerID","CustomerTaxID","CompanyName","Email","Password","Permission");
		
		if(strcmp($candidate, "CustomerID")==0)return true;
		if(strcmp($candidate, "CustomerTaxID")==0)return true;
		if(strcmp($candidate, "CompanyName")==0)return true;
		if(strcmp($candidate, "Email")==0)return true;
		if(strcmp($candidate,"Password")==0)return true;
		if(strcmp($candidate,"Permission")==0)return true;
		return Address::isColumn($candidate);
	
	}
	/*
	 * passed parameter same as updateInDB
	 * 
	 */
	static public function instatiate($db,$parameters){
		
		
		
		
		for($i=0;$i<count($parameters);$i++){
			$parameterName=$parameters[$i][0];
			if(!Customer::isColumn($parameterName))throw new GeneralException(new Err_UnknownField($parameterName));
			else if (strcmp($parameterName, "CustomerTaxID")==0)$TaxID=$parameters[$i][1];
			else if (strcmp($parameterName, "CompanyName")==0)$Name=$parameters[$i][1];
			else if (strcmp($parameterName, "Email")==0)$email=$parameters[$i][1];
			else if (strcmp($parameterName, "Password")==0)$pw=$parameters[$i][1];
			else if (strcmp($parameterName, "Permission")==0)$permissions=$parameters[$i][1];
			else if (strcmp($parameterName, "AddressDetail")==0)$addressDetail=$parameters[$i][1];
			else if (strcmp($parameterName, "PostalCode1")==0)$postalCode1=$parameters[$i][1];
			else if (strcmp($parameterName, "PostalCode2")==0)$postalCode2=$parameters[$i][1];
			else if (strcmp($parameterName, "City")==0)$city=$parameters[$i][1];
			else if (strcmp($parameterName, "Country")==0)$country=$parameters[$i][1];
		}
		
		
		$customer=new Customer(NULL, $TaxID, $Name, $email, $pw, $permissions);
		
		$customer->BillingAddress=new Address($addressDetail, $city, $postalCode1, $postalCode2, $country);
		
		$customer->CustomerID=$customer->insertIntoDB($db);
		
		
		return $customer;
	}
	
	function getAddress(){
		
		return $this->BillingAddress;
	}

	public function missingParameter(){
		
		if($this->CustomerTaxID==null || !isset($this->CustomerTaxID))return"CustomerTaxID";
		else if($this->CompanyName==null || !isset($this->CompanyName))return"CompanyName";
		else if($this->Email==null || !isset($this->Email))return"Email";
		else if($this->Password==null || !isset($this->Password))return"Password";
		else if($this->Permission==null || !isset($this->Permission))return"Permission";
		else if($this->BillingAddress==null || !isset($this->BillingAddress))return"BillingAddress";
		return$this->BillingAddress->missingParameter();
	}


}
class Address implements savable{
	
	public $AddressDetail;
	public $City;
	public $PostalCode1;
	public $PostalCode2;
	public $Country;
	
	function __construct($det,$theCity,$zip1,$zip2,$theCountry){
		
		$this->AddressDetail=$det;
		$this->City=$theCity;
		
		if($zip1>0)$this->PostalCode1=$zip1;
		else $this->PostalCode1=null;
		
		if($zip2>0)$this->PostalCode2=$zip2;
		else $this->PostalCode2=null;
		
		$this->Country=$theCountry;
		
		
	}
	function insertIntoDB($db){
		
		if($this->City==null || $this->AddressDetail==null || $this->City==null || $this->PostalCode1==null || $this->PostalCode2==null || $this->Country==null) return;
		
		$stmt="Insert into Address (AddressDetail,City,PostalCode1,PostalCode2,Country) Values(?,?,?,?,?);";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->AddressDetail);
		$query->bindParam(2,$this->City);
		$query->bindParam(3,$this->PostalCode1);
		$query->bindParam(4,$this->PostalCode2);
		$query->bindParam(5,$this->Country);
		
		
		return $query->execute();
		
		
	}
	static public function getInstancesByFields($db,$fields){
		
	/*	$params=array();
		
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(strcmp($entry[0],"AddressID")==0 || strcmp($entry[0],"AddressDetail")==0 || strcmp($entry[0],"City")==0 || strcmp($entry[0],"PostalCode1")==0 || strcmp($entry[0],"PostalCode2")==0 || strcmp($entry[0],"Country")==0)
				array_push($params, $entry);				
			else throw new GeneralException(new Err_UnknownField($entry[0]));
		}
		
		$query=constructSelect("Address", $params, $db);
		$query->execute();
		$result=$query->fetchAll();
		$instances=array();
		for($i=0;$i<count($result);$i++){
			$entry=$result[$i];
			$instance=new Address($entry["AddressID"], $entry["AddressDetail"], $entry["City"], $entry["PostalCode1"], $entry["PostalCode2"], $entry["Country"]);
			$instances[$i]=$instance;
		}
		
		return $instances;*/
		
	}
	public function missingParameter(){
		if($this->AddressDetail==null || !isset($this->AddressDetail))return "AddressDetail";
		else if($this->PostalCode1==null || !isset($this->PostalCode1))return "PostalCode1";
		else if($this->PostalCode2==null || !isset($this->PostalCode2))return "PostalCode2";
		else if($this->City==null || !isset($this->City))return "City";
		else if($this->Country==null || !isset($this->Country))return "Country";
		return null;
		
		
	}
	static function isColumn($candidate){
		
		if(strcmp("AddressDetail",$candidate)==0)return true;
		if(strcmp("PostalCode1",$candidate)==0)return true;
		if(strcmp("PostalCode2",$candidate)==0)return true;
		if(strcmp("City",$candidate)==0)return true;
		if(strcmp("Country",$candidate)==0)return true;
		return false;
	}
	
}
class Product implements savable,changable{
	
	public $ProductCode;
	public $ProductDescription;
	public $UnitPrice;//in cents
	public $UnitOfMeasure;
	public $ProductTypeID;
	
	function __construct($code,$descrip,$price,$unit,$typeID){
		
		$this->ProductCode=$code;
		$this->ProductDescription=$descrip;
		
		if($price>=0)$this->UnitPrice=$price;
		else $this->UnitPrice=null;
		
		$this->UnitOfMeasure=$unit;
		
		if($typeID>=0)$this->ProductTypeID=$typeID;
		else $this->ProductTypeID=null;
		
		
	}
	
	function insertIntoDB($db){
		

		if($this->ProductTypeID==null || !isset($this->ProductTypeID))$this->ProductTypeID=1;
		$missing=$this->missingParameter();
		if($missing!=null)throw new GeneralException(new Err_MissingParameter($missing));
		
		
		$stmt="Insert into Product (ProductDescription,UnitPrice,UnitOfMeasure,ProductTypeID) Values(?,?,?,?);";
		$theprice= ($this->UnitPrice)*100;
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->ProductDescription);
		$query->bindParam(2, $theprice);
		$query->bindParam(3,$this->UnitOfMeasure);
		$query->bindParam(4,$this->ProductTypeID);
		
		$query->execute();
		
		return $db->lastInsertId();
				
			
		
	}

	static public function getInstancesByFields($db,$fields){
		
		
		$params=array();
		
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(Product::isColumn($entry[0]))array_push($params, $entry);
			else throw new GeneralException(new Err_UnknownField($entry[0]));
		}
		
		
		
		$query=constructSelect("Product", $params, $db);
		$query->execute();
		$result=$query->fetchAll();
		
		$instances=array();
		for($i=0;$i<count($result);$i++){
			$entry=$result[$i];
			$instance=new Product($entry["ProductCode"],$entry["ProductDescription"], $entry["UnitPrice"]/100, $entry["UnitOfMeasure"], $entry["ProductTypeID"]);
			$instances[$i]=$instance;
			
		}
		
		return $instances;
		
	}

	static public function updateInDB($db,$parameters){
		
		for($i=0;$i<count($parameters);$i++){
			$columnName=$parameters[$i][0];
			if(strcmp($columnName,"UnitPrice")==0)$parameters[$i][1]*=100;
			if(!Product::isColumn($columnName))throw new GeneralException(new Err_UnknownField($columnName));
		}
		
		$query=constructUpdate("Product", $parameters, $db);
		$result=$query->execute();
		
		if($result) return Product::getInstancesByFields($db, array(array($parameters[0][0],array($parameters[0][1]),"equal")))[0];
	}
	
	static public function isColumn($candidate){
		
		$columns=array("ProductCode","ProductDescription","UnitOfMeasure","UnitPrice","ProductTypeID");
		for($i=0;$i<count($columns);$i++){
			if(strcmp($candidate, $columns[$i])==0)return TRUE;
		}
		
		return FALSE;
		
	}

	static public function instatiate($db,$parameters){
	
		for($i=0;$i<count($parameters);$i++){
			$parameterName=$parameters[$i][0];
			if(!Product::isColumn($parameterName))throw new GeneralException(new Err_UnknownField($parameterName));
			else if (strcmp($parameterName, "ProductDescription")==0)$descript=$parameters[$i][1];
			else if (strcmp($parameterName, "UnitOfMeasure")==0)$unit=$parameters[$i][1];
			else if (strcmp($parameterName, "UnitPrice")==0)$price=$parameters[$i][1];
			else if (strcmp($parameterName, "ProductTypeID")==0)$typeID=$parameters[$i][1];
		}
		
		$product=new Product(null, $descript, $price, $unit, $typeID);
	
		$product->ProductCode=$product->insertIntoDB($db);
		
		return $product;
	}
	public function missingParameter(){
		
		if($this->ProductDescription==null || !isset($this->ProductDescription))return"ProductDescription";
		else if($this->UnitPrice==null || !isset($this->UnitPrice))return "UnitPrice";
		else if($this->UnitOfMeasure==null || !isset($this->UnitOfMeasure))return "UnitOfMeasure";
		return null;
		
		
	}
	
	
	
	
}
class ProductType implements savable{
	
	public $typeID;
	public $typeDescription;
	public $taxID;
	
		
		
	
	function __construct($typeID, $typeDescription,$theTaxID){
		
		$this->typeID=$typeID;
		$this->typeDescription=$typeDescription;
		$this->taxID=$theTaxID;		
	}
	
	function insertIntoDB($db){
		
		if($this->typeDescription==null || $this->taxID==null)return;
		
		$stmt="Insert into ProductType (ProductTypeDescription,TaxID) Values(?,?);";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->typeDescription);
		$query->bindParam(2,$this->taxID);
		
		return $query->execute();
		
	}
	
	static public function getInstancesByFields($db,$fields){
		
		
		$params=array();
		
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(strcmp($entry[0],"ProductTypeID")==0 || strcmp($entry[0],"ProductTypeDescription")==0 || strcmp($entry[0],"TaxID")==0)array_push($params, $entry);
			else throw new GeneralException(new Err_UnknownField($entry[0]));
		}
		
		$query=constructSelect("ProductType", $params, $db);
		$query->execute();
		$result=$query->fetchAll();
		
		$instances=array();
		for($i=0;$i<count($result);$i++){
			$entry=$result[$i];
			$instance=new ProductType($entry["ProductTypeID"],$entry["ProductTypeDescription"],$entry["TaxID"]);
			$instances[$i]=$instance;
		}
		
		return $instances;
	}
}
class Tax implements savable{
	
	
	protected $TaxID;
	public $TaxPercentage;
	public $TaxType;
	
	
	function __construct($id,$value,$description){
		
		$this->TaxID=$id;
		if($value>=0) $this->TaxPercentage=$value;
		else $this->TaxPercentage=null;
		$this->TaxType=$description;
		

		
	}
	function insertIntoDB($db){
		
		$missingParam=$this->missingParameter();
		if($missingParam!=null)throw new GeneralException(new Err_MissingParameter($missingParam));
		$stmt="Insert into Tax (TaxValue,Description) Values(?,?);";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->TaxPercentage);
		$query->bindParam(2,$this->TaxType);
		
		return $query->execute();
	}
	static public function getInstancesByFields($db,$fields){
		
		
		$params=array();
		
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(strcmp($entry[0],"TaxID")==0 || strcmp($entry[0],"TaxValue")==0 || strcmp($entry[0],"TaxType")==0)array_push($params, $entry);
			else throw new GeneralException(new Err_UnknownField($entry[0]));		
		}
		
		$query=constructSelect("Tax", $params, $db);
		$query->execute();
		$result=$query->fetchAll();
		
		$instances=array();
		for($i=0;$i<count($result);$i++){
			$entry=$result[$i];
			$instance=new Tax($entry["TaxID"],$entry["TaxValue"],$entry["TaxType"]);
			$instances[$i]=$instance;
		}
		
		return $instances;
		
		
		
	}
	static public function isColumn($candidate){
		if(strcmp($candidate,"TaxID"))return true;
		if(strcmp($candidate,"TaxPercentage"))return true;
		if(strcmp($candidate,"TaxType"))return true;
		return false;
	}
	public function missingParameter(){
		
	
		if($this->TaxPercentage==null || !isset($this->TaxPercentage))return "TaxPercentage";
		if($this->TaxType==null || !isset($this->TaxType))return "TaxType";
		return null;
		
	}
	public function toXML(){
		$xmlTemplate=simplexml_load_file("./invoice_xml/TaxTableEntryTemplate.xml");
		$xmlTemplate->TaxType=$this->TaxType;
		$xmlTemplate->TaxPercentage=$this->TaxPercentage;
		$xmlTemplate->Description=$this->TaxType;
		return $xmlTemplate->asXML();
		
	}
}




function getConditionStr($entry){
	
	$op=$entry[2];
	$fieldName=$entry[0];
	
	if($op=="equal"){
		if(count($entry[1])!=1)throw new GeneralException(new Err_WrongNumberValues($fieldName));
		return $fieldName." = ? ";	
	}
	else if($op=="max"){
		if(count($entry[1])!=1)throw new GeneralException(new Err_WrongNumberValues($fieldName));
		return $fieldName." <= ? ";
	}
	else if($op=="min"){
		if(count($entry[1])!=1)throw new GeneralException(new Err_WrongNumberValues($fieldName));
		return $fieldName." >= ? ";
	}
	else if($op=="range"){
		if(count($entry[1])!=2)throw new GeneralException(new Err_WrongNumberValues($fieldName));
		return $fieldName." BETWEEN ? AND ? ";
	}
	else if($op=="contains"){
		if(count($entry[1])!=1)throw new GeneralException(new Err_WrongNumberValues($fieldName));
		return $fieldName." LIKE ? ";
	}
	else throw new GeneralException(new Err_UnknownOp($entry[2]));
	
	
	
	
}

function constructSelect($tableName,$parameters,$db){
	
	$stmt="Select * from $tableName";
	
	if($parameters==NULL || count($parameters)==0){
		$query=$db->prepare($stmt.';');
		return $query;
	}
	

	
	$stmt.=" WHERE " ;
	for($i=0;$i<count($parameters)-1;$i++){//for everyone but the last
		$cond=getConditionStr($parameters[$i]);
		$elem=$parameters[$i];
		$stmt.=" $cond AND ";
	}
		
	$stmt.=getConditionStr($parameters[$i]);
	$query=$db->prepare($stmt);
	$place=1;
		
	for($i=0;$i<count($parameters);$i++){
		
		
		$entry=$parameters[$i];
		$query->bindParam($place,$entry[1][0]);
		$place++;
		
		if($entry[2]=="range"){
			$query->bindParam($place,$entry[1][1]);
			$place++;
		} 
		
	}

		
		
	return $query;
}

function constructInsert($tableName,$parameters,$db){
	
	if($parameters==NULL|| count($parameters)==0) throw new GeneralException(new Err_MissingParameter("parameters"));
	
	$stmt="INSERT INTO $tableName";
	
	$fields=" (";
	$values=" Values(";
	
	for($i=0;$i<count($parameters)-1;$i++){
		$fields.=$parameters[$i][0].",";
		$values.="?,";
	}
	$fields.=$parameters[$i][0].")";
	$values.="?)";
	$stmt.=$fields.$values;
	$query=$db->prepare($stmt);
	
	
	for($i=0;$i<count($parameters);$i++) $query->bindParam($i+1,$paramenters[$i][1]);
	
	return $query;
	
	
	
}

/*function constructUpdate($tableName,$parameters,$db){
	
	if($parameters==NULL|| count($parameters)==0) throw new GeneralException(new Err_MissingParameter("parameters"));
	
	$stmt="UPDATE $tableName SET ";
	
	//starting at 1 because first element will be the id that is the matching parameter
	
	for($i=1;$i<count($parameters)-1;$i++) $stmt.=$parameters[$i][0]." = ? ,";
	
	$stmt.=$parameters[$i][0]." = ? ";
	
	$stmt.=" WHERE ".$parameters[0][0]." = ".$parameters[0][1];
	
	$query=$db->prepare($stmt);
	
	for($i=1;$i<count($parameters);$i++) $query->bindParam($i,$parameters[$i][1]);
	
	return $query;
	
	
}*/
function constructUpdate($tableName,$parameters,$db,$nrMatching=1){

	if($parameters==NULL|| count($parameters)<$nrMatching) throw new GeneralException(new Err_MissingParameter("parameters"));

	$stmt="UPDATE $tableName SET ";

	//starting at 1 because first element will be the id that is the matching parameter

	for($i=$nrMatching;$i<count($parameters)-1;$i++) $stmt.=$parameters[$i][0]." = ? ,";

	$stmt.=$parameters[$i][0]." = ? ";
	
	$stmt.="WHERE ";
	
	for($i=0;$i<$nrMatching-1;$i++){
		$stmt.=$parameters[$i][0]." = ".$parameters[$i][1]." AND ";
	}

	$stmt.=$parameters[$i][0]." = ".$parameters[$i][1];

	$query=$db->prepare($stmt);

	for($i=$nrMatching;$i<count($parameters);$i++) $query->bindParam($i-$nrMatching+1,$parameters[$i][1]);

	return $query;


}

function lineComparator($line1,$line2){
	return $line1->LineNo-$line2->LineNo;
}

function simplexml_insert_after(SimpleXMLElement $insert, SimpleXMLElement $target)
{
	$target_dom = dom_import_simplexml($target);
	$insert_dom = $target_dom->ownerDocument->importNode(dom_import_simplexml($insert), true);
	if ($target_dom->nextSibling) {
		return $target_dom->parentNode->insertBefore($insert_dom, $target_dom->nextSibling);
	} else {
		return $target_dom->parentNode->appendChild($insert_dom);
	}
}
function simplexml_insert_before(SimpleXMLElement $insert, SimpleXMLElement $target){
	$target_dom = dom_import_simplexml($target);
	$insert_dom = $target_dom->ownerDocument->importNode(dom_import_simplexml($insert), true);
	return $target_dom->parentNode->insertBefore($insert_dom, $target_dom);
	
}

function addIfNotRepeated(&$array,$elementToAdd){
	
	if($elementToAdd instanceof Tax){
		for($i=0;$i<count($array);$i++){
			if(strcmp($array[$i]->TaxID,$elementToAdd->TaxID)==0){
				return;
			}
		}
		
	}
	else if($elementToAdd instanceof Product){
		for($i=0;$i<count($array);$i++){
			if(strcmp($array[$i]->ProductCode,$elementToAdd->ProductCode)==0){
				return;
			}
		}
		
	}
	else if($elementToAdd instanceof  Customer){
		for($i=0;$i<count($array);$i++){
			if(strcmp($array[$i]->CustomerID,$elementToAdd->CustomerID)==0){
				return;
			}
		}
	}
	array_push($array, $elementToAdd);
	
}
?>
