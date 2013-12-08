<?php
include 'classes.php';

//$customer=new Customer(null, 5555555, "Fransisco", "maluco@gmail.com", 1234, 1);
//$customer_json='{"CustomerTaxID":12314335,"CompanyName":"Sonae","Email":"sonae@gmail.com","Password":1234,"Permission":1,"BillingAddress":{"AddressDetail":"Rua dos Clerigos","PostalCode1":4200,"PostalCode2":222,"City":"Porto","Country":"Portugal"}}';

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

/*
$invoice= new Invoice(null, "2013-12-12", "2013-12-12", "0");
$customer=Customer::getInstancesByFields($db, array(array("CustomerID",array(3),"equal")))[0];
$invoice->Customer=$customer;

$invoice->insertIntoDB($db)
*/
try{
//$line=Line::getInstancesByFields($db, array(array("InvoiceNo",array(1),"equal")))[0];
$xml="<Line>
	<LineNumber/>
	<ProductCode>2</ProductCode>
	<ProductDescription>Argamassa de Revest. ArgTec. - Saco 30kg</ProductDescription>
	<ProductQuantity>15</ProductQuantity>
	<UnitOfMeasure>un</UnitOfMeasure>
	<UnitPrice>1262</UnitPrice>
	<TaxPointDate/>
	<Description>Argamassa de Revest. ArgTec. - Saco 30kg</Description>
	<CreditAmount>18930</CreditAmount>
	<Tax>
		<TaxType>IVA Normal</TaxType>
		<TaxCountryRegion>PT</TaxCountryRegion>
		<TaxCode>NOR</TaxCode>
		<TaxPercentage>23</TaxPercentage>
	</Tax>
<lineNumber>4</lineNumber></Line>";
$line=Line::fromXML($xml);
echo $line->toXML();
}
catch(GeneralException $e){
	echo $e;
}
?>
