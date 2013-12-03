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





interface savable
{
	public function saveToDB($db);
	static public function getInstancesByFields($db,$fields);
}




class Invoice implements savable{
	
	public $InvoiceNo;
	public $InvoiceDate;
	protected $CustomerID;
	public $CompanyName;
	protected $Lines;
	public $GrossTotal;
	
	function __construct($InvoiceNo,$InvoiceDate,$CustomerID,$CompanyName){
		
		$this->InvoiceNo=$InvoiceNo;
		$this->InvoiceDate=$InvoiceDate;
		$this->CustomerID=$CustomerID;
		$this->CompanyName=$CompanyName;
		
		
		
		
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
	
	
	
	public function saveToDB($db){
		//TODO implement it
	}
	static public function getInstancesByFields($db,$fields){
		
		$params=array();
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(strcmp($entry[0],"InvoiceNo")==0 || strcmp($entry[0],"InvoiceDate")==0 ||
			strcmp($entry[0],"CustomerID")==0 || strcmp($entry[0],"AddressID")==0 || strcmp($entry[0],"CompanyName")==0){
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
				
			$instance=new Invoice($entry["InvoiceNo"], $entry["InvoiceDate"], $entry["CustomerID"], $entry["CompanyName"]);
			
			$fields=array(
				array("InvoiceNo",array($instance->InvoiceNo),"equal")
			);
			$lines=Line::getInstancesByFields($db,$fields);
			$instance->setLines($lines);
			$instances[$i]=$instance;
		}
		
		return $instances;
		
		
	}
	
	function getLines(){
		return $this->Lines;
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
	public function saveToDB($db){
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


class Customer implements savable{
	
	public $CustomerID;
	public $CustomerTaxID;
	public $CompanyName;
	protected $address;
	public $email;
	public $password;
	public $permission;
	//protected $Address;
	
	
	function __construct($ID,$TaxID,$Name,$email,$pw,$permissions,$db){
		
		$this->CustomerID=$ID;
		if($TaxID>=0)$this->CustomerTaxID=$TaxID;//TODO: maybe use a validating function later
		else $this->CustomerTaxID=null;

		$this->CompanyName=$Name;
		
		$this->email=$email;
		$this->password=$pw;
		$this->permission=$permissions;//TODO: maybe validate these permissions
		
		$addressParameters=array(
			array("AddressID",array($this->addressID),"equal")
		);
		
		
		
		$ads=Address::getInstancesByFields($db, $addressParameters);
		$this->Address=$ads[0];
		
		
	}
	function saveToDB($db){
		
		if($this->CustomerTaxID==null || $this->CompanyName==null || $this->addressID==null || $this->email==null || $this->password==null) return;
		
		if($this->permission==null)$this->permission=0;
		
		$stmt="Insert into Customer (CustomerTaxID,CompanyName,Email,AddressID,Password,Permission) Values(?,?,?,?,?,?);";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->CustomerTaxID);
		$query->bindParam(2,$this->CompanyName);
		$query->bindParam(3,$this->email);
		$query->bindParam(4,$this->addressID);
		$query->bindParam(5,$this->password);
		$query->bindParam(6,$this->permission);
		
		
		return $query->execute();
		
		
	}
	
	
	static public function getInstancesByFields($db,$fields){
		

		$params=array();
		
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(strcmp($entry[0],"CustomerID")==0 || strcmp($entry[0],"CustomerTaxID")==0 || 
			strcmp($entry[0],"CompanyName")==0 || strcmp($entry[0],"Email")==0 || 
			strcmp($entry[0],"AddressDetail")==0 || strcmp($entry[0],"PostalCode1")==0 || 
			strcmp($entry[0],"PostalCode2")==0 || strcmp($entry[0],"City")==0 ||
			strcmp($entry[0],"Country")==0 || strcmp($entry[0],"Password")==0 ||
			strcmp($entry[0],"Permission")==0){
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
			$instance=new Customer($entry["CustomerID"], $entry["CustomerTaxID"], $entry["CompanyName"], $entry["Email"], $entry["Password"], $entry["Permission"],$db);
			$instance->address=new Address($entry["AddressDetail"], $entry["City"],$entry["PostalCode1"], $entry["PostalCode2"], $entry["Country"]);
			$instances[$i]=$instance;
		}

		return $instances;
	}
	
	
	function getAddress(){
		
		return $this->Address;
	}
}


class Address implements savable{
	
	public $detail;
	public $city;
	public $postalCode1;
	public $postalCode2;
	public $country;
	
	
	

	
	function __construct($det,$theCity,$zip1,$zip2,$theCountry){
		
		$this->detail=$det;
		$this->city=$theCity;
		
		if($zip1>0)$this->postalCode1=$zip1;
		else $this->postalCode1=null;
		
		if($zip2>0)$this->postalCode2=$zip2;
		else $this->postalCode2=null;
		
		$this->country=$theCountry;
		
		
	}
	
	
	function saveToDB($db){
		
		if($this->city==null || $this->detail==null || $this->city==null || $this->postalCode1==null || $this->postalCode2==null || $this->country==null) return;
		
		$stmt="Insert into Address (AddressDetail,City,PostalCode1,PostalCode2,Country) Values(?,?,?,?,?);";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->detail);
		$query->bindParam(2,$this->city);
		$query->bindParam(3,$this->postalCode1);
		$query->bindParam(4,$this->postalCode2);
		$query->bindParam(5,$this->country);
		
		
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


class Product implements savable{
	
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
	
	function saveToDB($db){
		
		if($this->ProductDescription==null || $this->UnitPrice==null || $this->UnitOfMeasure==null || $this->ProductTypeID==null){
			
			$stmt="Insert into Product (ProductDescription,UnitPrice,UnitOfMeasure,ProductTypeID) Values(?,?,?,?);";
			$query=$db->prepare($stmt);
			$query->bindParam(1,$this->ProductDescription);
			$query->bindParam(2,$this->UnitPrice);
			$query->bindParam(3,$this->UnitOfMeasure);
			$query->bindParam(4,$this->ProductTypeID);
			
			return $query->execute();
					
			
		}
		
	}

	static public function getInstancesByFields($db,$fields){
		
		
		$params=array();
		
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(strcmp($entry[0],"ProductCode")==0 || strcmp($entry[0],"ProductDescription")==0 || strcmp($entry[0],"UnitOfMeasure")==0 || strcmp($entry[0],"UnitPrice")==0 || strcmp($entry[0],"ProductTypeID")==0)array_push($params, $entry);
			else throw new GeneralException(new Err_UnknownField($entry[0]));
		}
		
		
		
		$query=constructSelect("Product", $params, $db);
		$query->execute();
		$result=$query->fetchAll();
		
		$instances=array();
		for($i=0;$i<count($result);$i++){
			$entry=$result[$i];
			$instance=new Product($entry["ProductCode"],$entry["ProductDescription"], $entry["UnitPrice"], $entry["UnitOfMeasure"], $entry["ProductTypeID"]);
			$instances[$i]=$instance;
			
		}
		
		return $instances;
		
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
	
	function saveToDB($db){
		
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
	function saveToDB($db){
		
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
