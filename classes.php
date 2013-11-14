<?php 


class NotFoundException extends Exception {};
class DBInconsistencyException extends Exception {};
class BadParameterException extends Exception {};
class BadOpException extends Exception {};
class BadNumberArgsException extends Exception {};


interface savable
{
	public function saveToDB($db);
	static public function getInstancesByFields($db,$fields);
}

class Customer implements savable{
	
	public $customerID;
	public $customerTaxID;
	public $customerName;
	public $addressID;
	public $email;
	public $password;
	public $permission;
	
	
	function __construct($ID,$TaxID,$Name,$addID,$email,$pw,$permissions){
		
		$this->customerID=$ID;
		if($TaxID>=0)$this->customerTaxID=$TaxID;//TODO: maybe use a validating function later
		else $this->customerTaxID=null;

		$this->customerName=$Name;
		
		if($addID>=0) $this->addressID=$addID;
		else $this->addressID=null;
		
		$this->email=$email;
		$this->password=$pw;
		$this->permission=$permissions;//TODO: maybe validate these permissions
		
		
		
		
	}
	function saveToDB($db){
		
		if($this->customerTaxID==null || $this->customerName==null || $this->addressID==null || $this->email==null || $this->password==null) return;
		
		if($this->permission==null)$this->permission=0;
		
		$stmt="Insert into customer (customerTaxID,customerName,Email,AddressID,Password,Permissions) Values(?,?,?,?,?,?);";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->customerTaxID);
		$query->bindParam(2,$this->customerName);
		$query->bindParam(3,$this->email);
		$query->bindParam(4,$this->addressID);
		$query->bindParam(5,$this->password);
		$query->bindParam(6,$this->permission);
		
		
		return $query->execute();
		
		
	}
	
	
	static public function getInstancesByFields($db,$fields){
		
		$i=0;
		$params=array();
		if(isset($fields["CustomerID"])) $params[$i]=array("CustomerID",$fields["CustomerID"]);
		if(isset($fields["CustomerTaxID"])) $params[$i++]=array("CustomerTaxID",$fields["CustomerTaxID"]);
		if(isset($fields["CustomerName"])) $params[$i++]=array("CustomerName",$fields["CustomerName"]);
		if(isset($fields["Email"])) $params[$i++]=array("Email",$fields["Email"]);
		if(isset($fields["AddressID"])) $params[$i++]=array("AddressID",$fields["AddressID"]);
		if(isset($fields["Password"])) $params[$i++]=array("Password",$fields["Password"]);
		if(isset($fields["Permission"])) $params[$i++]=array("Permission",$fields["Permission"]);

		$params=array();
		
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(strcmp($entry[0],"CustomerID")==0 || strcmp($entry[0],"CustomerTaxID")==0 || 
			strcmp($entry[0],"CustomerName")==0 || strcmp($entry[0],"Email")==0 || 
			strcmp($entry[0],"AddressID")==0 || strcmp($entry[0],"Password") || 
			strcmp($entry[0],"Permission")==0){
				array_push($params, $entry);
			}
			else throw new BadParameterException();
		}
		
		
		$query=constructSelect("Customer", $params, $db);
		$query->execute();
		$result=$query->fetchAll();
		$instances=array();
		for($i=0;$i<count($result);$i++){
			$entry=$result[$i];
			$instance=new Customer($entry["CustomerID"], $entry["CustomerTaxID"], $entry["CustomerName"], $entry["AddressID"], $entry["Email"], $entry["Password"], $entry["Permission"]);
			$instances[$i]=$instance;
		}

		return $instances;
	}
	
}


class Address implements savable{
	
	public $AddressID;
	public $detail;
	public $city;
	public $postalCode1;
	public $postalCode2;
	public $country;
	
	
	

	
	function __construct($id,$det,$theCity,$zip1,$zip2,$theCountry){
		
		$this->AddressID=$id;
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
		
		$params=array(
				array("AddressID",$fields["AddressID"]),
				array("AddressDetail",$fields["AddressDetail"]),
				array("City",$fields["City"]),
				array("PostalCode1",$fields["PostalCode1"]),
				array("PostalCode2",$fields["PostalCode2"]),
				array("Country",$fields["Country"])
		
		);
		$params=array();
		
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(strcmp($entry[0],"AddressID")==0 || strcmp($entry[0],"AddressDetail")==0 || strcmp($entry[0],"City") || strcmp($entry[0],"PostalCode1") || strcmp($entry[0],"PostalCode2") || strcmp($entry[0],"Country")==0)array_push($params, $entry);
			else throw new BadParameterException();
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
		
		return $instances;
		
	}
}


class Product implements savable{
	
	public $productCode;
	public $productDescription;
	public $unitPrice;//in cents
	public $unitOfMeasure;
	public $productTypeID;
	
	function __construct($code,$descrip,$price,$unit,$typeID){
		
		$this->productCode=$code;
		$this->productDescription=$descrip;
		
		if($price>=0)$this->unitPrice=$price;
		else $this->unitPrice=null;
		
		$this->unitOfMeasure=$unit;
		
		if($typeID>=0)$this->productTypeID=$typeID;
		else $this->productTypeID=null;
		
		
	}
	
	function saveToDB($db){
		
		if($this->productDescription==null || $this->unitPrice==null || $this->unitOfMeasure==null || $this->productTypeID==null){
			
			$stmt="Insert into Product (ProductDescription,UnitPrice,UnitOfMeasure,ProductTypeID) Values(?,?,?,?);";
			$query=$db->prepare($stmt);
			$query->bindParam(1,$this->productDescription);
			$query->bindParam(2,$this->unitPrice);
			$query->bindParam(3,$this->unitOfMeasure);
			$query->bindParam(4,$this->productTypeID);
			
			return $query->execute();
					
			
		}
		
	}

	static public function getInstancesByFields($db,$fields){
		
		
		$params=array(
			array("ProductCode",$fields["ProductCode"]),
			array("ProductDescription",$fields["ProductDescription"]),
			array("UnitOfMeasure",$fields["UnitOfMeasure"]),
			array("UnitPrice",$fields["UnitPrice"]),
			array("ProductTypeID",$fields["ProductTypeID"])		
		);
		
		$params=array();
		
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(strcmp($entry[0],"ProductCode")==0 || strcmp($entry[0],"ProductDescription")==0 || strcmp($entry[0],"UnitOfMeasure")==0 || strcmp($entry[0],"UnitPrice")==0 || strcmp($entry[0],"ProductTypeID")==0)array_push($params, $entry);
			else throw new BadParameterException();
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
			else throw new BadParameterException();
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
	
	
	public $taxID;
	public $value;
	public $description;
	
	
	function __construct($id,$value,$description){
		
		$this->taxID=$id;
		if($value>=0) $this->value=$value;
		else $this->value=null;
		$this->description=$description;
		

		
	}
	function saveToDB($db){
		
		if($this->value==null)return;//dont do nothing if it's not a valid tax
		$stmt="Insert into Tax (TaxValue,Description) Values(?,?);";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->value);
		$query->bindParam(2,$this->description);
		
		return $query->execute();
	}
	static public function getInstancesByFields($db,$fields){
		
		
		$params=array();
		
		
		for($i=0;$i<count($fields);$i++){
			$entry=$fields[$i];
			if(strcmp($entry[0],"TaxID")==0 || strcmp($entry[0],"TaxValue")==0 || strcmp($entry[0],"Description")==0)array_push($params, $entry);
			else throw new BadParameterException();		
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
		if(count($entry[1])!=1)throw new BadNumberArgsException();
		return $fieldName." = ? ";	
	}
	else if($op=="max"){
		if(count($entry[1])!=1)throw new BadNumberArgsException();
		return $fieldName." <= ? ";
	}
	else if($op=="min"){
		if(count($entry[1])!=1)throw new BadNumberArgsException();
		return $fieldName." >= ? ";
	}
	else if($op=="range"){
		if(count($entry[1])!=2)throw new BadNumberArgsException();
		return $fieldName." BETWEEN ? AND ? ";
	}
	else throw new BadOpException();
	
	
	
	
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

		
	$finished=$query->queryString;
		
	return $query;
}

?>
