<?php 



interface savable
{
	public function saveToDB($db);
}

class Customer implements savable{
	
	public $costumerID;
	public $costumerTaxID;
	public $costumerName;
	public $addressID;
	public $email;
	public $password;
	public $permission;
	
	
	function __construct($db,$id){
		
		$stmt="Select * from Costumer where CostumerID=?;";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$id);
		
		$result=$query->execute()->fetchAll();
		
		if($result==null || $result.count()!=1){
			
			$this->costumerID=null;
			$this->costumerTaxID=null;
			$this->costumerName=null;
			$this->address=null;
			$this->email=null;
			$this->password=null;
			$this->permission=null;
			return;
		}
		
		$result=$result[0];
		
		$this->costumerID=$result[0];
		$this->costumerTaxID=$result[1];
		$this->costumerName=$result[2];
		$this->email=$result[3];
		$this->password=$result[4];
		$this->permission=$result[5];
		
		
	}
	
	function __construct($TaxID,$Name,$addID,$email,$pw,$permissions){
		
		$this->costumerID=null;
		if($TaxID>=0)$this->costumerTaxID=$TaxID;//TODO: maybe use a validating function later
		else $this->costumerTaxID=null;

		$this->costumerName=$Name;
		
		if($addID>=0) $this->addressID=$addID;
		else $this->addressID=null;
		
		$this->email=$email;
		$this->password=pw;
		$this->permission=$permissions;//TODO: maybe validate these permissions
		
		
		
		
	}
	
	
	function saveToDB($db){
		
		if($this->costumerTaxID==null || $this->costumerName==null || $this->addressID==null || $this->email==null || $this->password==null) return;
		
		if($this->permission==null)$this->permission=0;
		
		$stmt="Insert into Costumer (CostumerTaxID,CostumerName,Email,AddressID,Password,Permissions) Values(?,?,?,?,?,?);";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->costumerTaxID);
		$query->bindParam(2,$this->costumerName);
		$query->bindParam(3,$this->email);
		$query->bindParam(4,$this->addressID);
		$query->bindParam(5,$this->password);
		$query->bindParam(6,$this->permission);
		
		
		return $query->execute();
		
		
	}
	
}


class Address implements savable{
	
	public $AddressID;
	public $detail;
	public $city;
	public $postalCode1;
	public $postalCode2;
	public $country;
	
	
	function __construct($db,$id){
		
		$stmt= "Select * from Address where AddressID=?;";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$id);
		
		$query->execute();
		$result=$query->fetchAll();
		
		if($result==null || $result.count()!=1){
			$this->AddressID=null;
			$this->detail=null;
			$this->city=null;
			$this->postalCode1=null;
			$this->postalCode2=null;
			$this->country=null;
			return;
			
		}
		
		$result=$result[0];
		
		//load data
		$this->AddressID=$result[0];
		$this->detail=$result[1];
		$this->city=$result[2];
		$this->postalCode1=$result[3];
		$this->postalCode2=$result[4];
		$this->country=$result[5];
		
		
	}

	
	function __construct($det,$theCity,$zip1,$zip2,$theCountry){
		
		$this->AddressID=null;
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
}


class Product implements savable{
	
	public $productCode;
	public $productDescription;
	public $unitPrice;//in cents
	public $unitOfMeasure;
	public $productTypeID;
	
	
	function __construct($db,$code){
		
		$stmt= "Select * from Product where produuctCode=?";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$code);
		
		
		$result=$query->execute()->fetchAll();
		
		if($result==null || $result.count()!=1){
			
			$this->productCode=null;
			$this->productDescription=null;
			$this->unitPrice=null;//in cents
			$this->unitOfMeasure=null;
			$this->productTypeID=null;
			
		}
		
		
		$result=$result[0];
		
		
		$this->productCode=$result[0];
		$this->productDescription=$result[1];
		$this->unitPrice=$result[2];
		$this->unitOfMeasure=$result[3];
		$this->productTypeID=$result[4];
		
		
		
		
	}

	
	function __construct($descrip,$price,$unit,$typeID){
		
		$this->productCode=null;
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
}

class ProductType implements savable{
	
	public $typeID;
	public $typeDescription;
	public $taxID;
	
	function __construct($db,$id){
		
		$stmt= "Select * from ProductType where ProductTypeID=?";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$id);
		
		
		$query->execute();
		$result=$query->fetchAll();
		
		if($result==null || $result.count()!=1){
				
			$this->typeID=null;
			$this->typeDescription=null;
			$this->taxID=null;
				
		}
		
		
		$result=$result[0];
		
		$this->typeID=$result[0];
		$this->typeDescription=$result[1];
		$this->taxID=$result[2];
		
		
		
	}
	function __construct($name,$theTaxID){
		
		$this->typeID=null;
		$this->typeDescription=$name;
		$this->taxID=$theTaxID;
		
		
		
		
		
		
		
	}
	function __saveToDB($db){
		
		if($this->typeDescription==null || $this->taxID==null)return;
		
		$stmt="Insert into ProductType (ProductTypeDescription,TaxID) Values(?,?);";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->typeDescription);
		$query->bindParam(2,$this->taxID);
		
		return $query->execute();
		
	}
	
	
}


class Tax implements savable{
	
	
	public $taxID;
	public $value;
	
	function __construct($db,$id) {
		
		$stmt="Select * from Tax where TaxID=?;";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$id);
		
		$query->execute();
		$result=$query->fetchAll();
		//TODO: do this separation 
		
		if($result==null || result.count()!=1){
			
			$this->taxID=null;
			$this->value=null;
			return;
		}
		
		$result=$result[0];//set result as only the first row
		
		$this->taxID=$result[0];
		$this->value=$result[1];
	}
	
	
	function __construct($value){
		
		$this->taxID=null;
		if($value>=0)$this->value=$value;
		else $this->value=null;

		
	}
	
	function saveToDB($db){
		
		if($this->value==null)return;//dont do nothing if it's not a valid tax
		$stmt="Insert into Tax (TaxValue) Values(?);";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$this->value);
		
		return $query->execute();
		
	
		
	}
	
}
?>