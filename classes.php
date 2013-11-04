<?php 


class Costumer {
	
	public $CostumerID;
	public $CostumerTaxID;
	public $CostumerName;
	public $Address;
	public $email;
	
	
	function __construct($db,$id){
		
		$stmt="Select * from Costumer where CostumerID=?";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$id);
		
		$result=$query->execute()->fetchAll();
		
		if(!$result){
			
			$this->CostumerID=null;
			$this->CostumerTaxID=null;
			$this->CostumerName=null;
			$this->Address=null;
			$this->email=null;
			return;
		}
		
		$firstResult=$result[0];
		
		foreach ($firstResult as $item){
			
			//TODO: get data from table
			
		}
		
		
	}
	
	function __construct($ID,$TaxID,$Name,$Address){
		
		$this->CostumerID=$ID;
		$this->CostumerTaxID=$TaxID;
		$this->CostumerName=$Name;
		if(get_class($Address)=="Address"){
			$this->Address=$Address;
		}
		else $this->Address=null;
		
		
		
	}
	
}


class Address{
	
	public $AddressID;
	public $detail;
	public $city;
	public $postalCode1;
	public $postalCode2;
	public $country;
	
	
	function __construct($db,$id){
		
		$stmt= "Select * from Address where AddressID=?";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$id);
		
		$result=$query->execute()->fetchAll();
		
		if($result==null){
			$this->AddressID=null;
			$this->detail=null;
			$this->city=null;
			$this->postalCode1=null;
			$this->postalCode2=null;
			$this->country=null;
			return;
			
		}
		
		$firstResult=$result[0];
		
		foreach ($firstResult as $item){
				
			//TODO: get data from table
				
		}
		
		
		
		
		
		
	}
	
}


class Product{
	
	public $productCode;
	public $productDescription;
	public $unitPrice;//in cents
	public $unitOfMeasure;
	public $productType;
	
	
	function __construct($db,$code){
		
		$stmt= "Select * from Product where produuctCode=?";
		$query=$db->prepare($stmt);
		$query->bindParam(1,$code);
		
		
		$result=$query->execute()->fetchAll();
		
		if($result==null){
			
			$this->productCode=null;
			$this->productDescription=null;
			$this->unitPrice=null;//in cents
			$this->unitOfMeasure=null;
			$this->productType=null;
			
		}
		
		
		$firstResult=$result[0];
		
		foreach ($firstResult as $item){
		
			//TODO: get data from table
		
		}
		
	}
	
	
}

?>