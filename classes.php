<?php 


class NotFoundException extends Exception {};
class DBInconsistencyException extends Exception {};


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
		
		
		$params=array(
			array("CustomerID",$fields["CustomerID"]),
			array("CustomerTaxID",$fields["CustomerTaxID"]),
			array("CustomerName",$fields["CustomerName"]),
			array("Email",$fields["Email"]),
			array("AddressID",$fields["AddressID"]),
			array("Password",$fields["Password"]),
			array("Permission",$fields["Permission"])	
						
		);
		
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
		
		$params=array(
				array("ProductTypeID",$fields["ProductTypeID"]),
				array("ProductTypeDescription",$fields["ProducTypeDescription"]),
				array("TaxID", $fields["TaxID"])
		);
		
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
		
		
		
		$params=array(
			array("TaxID",$fields["TaxID"]),
			array("TaxValue",$fields["TaxValue"]),
			array("Description", $fields["Description"])
		);
		
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
	
	if($op=="equal")return $fieldName." = ? ";
	else if($op=="max")return $fieldName." <= ? ";
	else if($op=="min")return $fieldName." >= ? ";
	else if($op=="range")return $fieldName." >= ? AND ".$fieldName." <= ? ";
	
	
	
	
}

function constructSelect($tableName,$parameters,$db){
	
	$stmt="Select * from $tableName";
	
	if($parameters==NULL || count($parameters)==0){
		$query=$db->prepare($stmt.';');
		return $query;
	}
	
	$goodParams=array();
	$pos=0;
	foreach ($parameters as $elem){
		if($elem[1]!=NULL){
			$goodParams[$pos]=$elem; //if not empty add
			$pos++;
		}
	}
	
	if($goodParams==NULL || count($goodParams)==0){
		$query=$db->prepare($stmt.';');
		return $query;
	}
	
		$stmt.=" WHERE " ;
		for($i=0;$i<count($goodParams)-1;$i++){//for everyone but the last
			$elem=$goodParams[$i];
			$stmt.=" $elem[0] = ? AND";
		}
		
		$value=$goodParams[$i][0];
		$stmt.=" $value = ?;";
		$query=$db->prepare($stmt);
		$place=1;
		
		for($i=0;$i<count($goodParams);$i++){
			;
			$query->bindParam($place,$goodParams[$i][1]);
			$place++;
		}

		
		$finished=$query->queryString;
		
	return $query;
}

?>
