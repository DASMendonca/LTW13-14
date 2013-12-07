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
	protected $Customer;
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
		
	}
	function getCustomerId(){
		return $this->CustomerID;
	}
	
	static public function updateInDB($db,$parameters){
	
		for($i=0;$i<count($parameters);$i++){
			$columnName=$parameters[$i][0];
			if(!Invoice::isColumn($columnName))throw new GeneralException(new Err_UnknownField($columnName));
		}
	
	
	
	
		$query=constructUpdate("Invoice", $parameters, $db);
		$result=$query->execute();
	
		if($result) return Invoice::getInstancesByFields($db, array(array($parameters[0][0],array($parameters[0][1]),"equal")))[0];
	
	
	
	}
	
	public function insertIntoDB($db){
		
		
		
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

	public function missingParameters(){
		
		$missing=array();
		
		if($this->StartDate==null || !isset($this->StartDate))array_push($missing,"StartDate");
		if($this->EndDate==null || !isset($this->EndDate))array_push($missing,"EndDate");
		if($this->StartDate==null || !isset($this->StartDate))array_push($missing,"StartDate");
		
		
	}
	
}

class Line implements savable{
	
	public $LineNumber;
	public $ProductCode;
	public $Quantity;
	public $UnitPrice;
	public $CreditAmount;
	public $Tax;
	
	
	function __construct($LineNumber,$ProductCode,$Quantity,$UnitPrice,$Tax){
		
		$this->LineNumber=$LineNumber;
		$this->ProductCode=$ProductCode;
		$this->Quantity=$Quantity;
		$this->UnitPrice=$UnitPrice;
		$this->Tax=$Tax;
		$this->CreditAmount=$this->UnitPrice*$this->Quantity;
	}
	public function insertIntoDB($db){
		//TODO: implement it later
	}
	static public function getInstancesByFields($db,$fields){
		
		$params=array();
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(strcmp($entry[0],"LineNo")==0 || strcmp($entry[0],"ProductCode")==0 || 
			strcmp($entry[0],"Quantity")==0 || strcmp($entry[0],"UnitPrice")==0 || strcmp($entry[0],"Tax")==0 || strcmp($entry[0],"InvoiceNo")==0){
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
			
			$tax=new Tax(null, $entry["TaxValue"], $entry["TaxDescription"]);
			$instance=new Line($entry["LineNo"], $entry["ProductCode"], $entry["Quantity"], $entry["UnitPrice"],$tax);
			$instances[$i]=$instance;
		}
		
		return $instances;
			
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
		
		
		if($this->CustomerTaxID==null || !isset($this->CustomerTaxID))throw new GeneralException(new Err_MissingParameter("CustomerTaxID"));
		if($this->CompanyName==null || !isset($this->CompanyName))throw new GeneralException(new Err_MissingParameter("CompanyName"));
		if($this->BillingAddress==null || !isset($this->BillingAddress))throw new GeneralException(new Err_MissingParameter("BillingAddress"));
		if($this->Email==null || !isset($this->Email))throw new GeneralException(new Err_MissingParameter("Email"));
		if($this->Password==null || !isset($this->Password))throw new GeneralException(new Err_MissingParameter("Password"));
		
		
		
		if($this->BillingAddress->AddressDetail==null || !isset($this->BillingAddress->AddressDetail))throw new GeneralException(new Err_MissingParameter("AddressDetail"));
		if($this->BillingAddress->PostalCode1==null || !isset($this->BillingAddress->PostalCode1))throw new GeneralException(new Err_MissingParameter("PostalCode1"));
		if($this->BillingAddress->PostalCode2==null || !isset($this->BillingAddress->PostalCode2))throw new GeneralException(new Err_MissingParameter("PostalCode2"));
		if($this->BillingAddress->City==null || !isset($this->BillingAddress->City))throw new GeneralException(new Err_MissingParameter("City"));
		if($this->BillingAddress->Country==null || !isset($this->BillingAddress->Country))throw new GeneralException(new Err_MissingParameter("Country"));
		
		
		if($this->Permission==null || !isset($this->Permission))$this->Permission=0;
		
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
	
		$columns=array("CustomerID","CustomerTaxID","CompanyName","Email","Password","Permission","AddressDetail","PostalCode1","PostalCode2","City","Country");
		for($i=0;$i<count($columns);$i++){
			if(strcmp($candidate, $columns[$i])==0)return TRUE;
		}
	
		return FALSE;
	
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



}

//TODO: change isColumn and updateDB as interface methods
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
		
		
		if($this->ProductDescription==null || !isset($this->ProductDescription))throw new GeneralException(new Err_MissingParameter("ProductDescripition"));
		if($this->UnitPrice==null || !isset($this->UnitPrice))throw new GeneralException(new Err_MissingParameter("UnitPrice"));
		if($this->UnitOfMeasure==null || !isset($this->UnitOfMeasure))throw new GeneralException(new Err_MissingParameter("UnitOfMeasure"));
		
		if($this->ProductTypeID==null || !isset($this->ProductTypeID))$this->ProductTypeID=1;
		
			
			$stmt="Insert into Product (ProductDescription,UnitPrice,UnitOfMeasure,ProductTypeID) Values(?,?,?,?);";
			$query=$db->prepare($stmt);
			$query->bindParam(1,$this->ProductDescription);
			$query->bindParam(2,$this->UnitPrice*100);
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
			if(!Customer::isColumn($parameterName))throw new GeneralException(new Err_UnknownField($parameterName));
			else if (strcmp($parameterName, "ProductCode")==0)$code=$parameters[$i][1];
			else if (strcmp($parameterName, "ProductDescription")==0)$descript=$parameters[$i][1];
			else if (strcmp($parameterName, "UnitOfMeasure")==0)$unit=$parameters[$i][1];
			else if (strcmp($parameterName, "UnitPrice")==0)$price=$parameters[$i][1];
			else if (strcmp($parameterName, "ProductTypeID")==0)$typeID=$parameters[$i][1];
		}
		
		$product=new Product($code, $descrip, $price, $unit, $typeID);
	
		$product->ProductCode=$product->insertIntoDB($db);
		
		return $product;
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
		
		if($this->TaxPercentage==null)return;//dont do nothing if it's not a valid tax
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
			if(strcmp($entry[0],"TaxID")==0 || strcmp($entry[0],"TaxValue")==0 || strcmp($entry[0],"Description")==0)array_push($params, $entry);
			else throw new GeneralException(new Err_UnknownField($entry[0]));		
		}
		
		$query=constructSelect("Tax", $params, $db);
		$query->execute();
		$result=$query->fetchAll();
		
		$instances=array();
		for($i=0;$i<count($result);$i++){
			$entry=$result[$i];
			$instance=new Tax($entry["TaxID"],$entry["TaxValue"],$entry["Description"]);
			$instances[$i]=$instance;
		}
		
		return $instances;
		
		
		
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


function constructUpdate($tableName,$parameters,$db){
	
	if($parameters==NULL|| count($parameters)==0) throw new GeneralException(new Err_MissingParameter("parameters"));
	
	$stmt="UPDATE $tableName SET ";
	
	//starting at 1 because first element will be the id that is the matching parameter
	
	for($i=1;$i<count($parameters)-1;$i++) $stmt.=$parameters[$i][0]." = ? ,";
	
	$stmt.=$parameters[$i][0]." = ? ";
	
	$stmt.=" WHERE ".$parameters[0][0]." = ".$parameters[0][1];
	
	$query=$db->prepare($stmt);
	
	for($i=1;$i<count($parameters);$i++) $query->bindParam($i,$parameters[$i][1]);
	
	return $query;
	
	
}

?>
