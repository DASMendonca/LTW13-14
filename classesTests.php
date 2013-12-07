<?php
include 'classes.php';

$customer=new Customer(null, 5555555, "Fransisco", "maluco@gmail.com", 1234, 1);
$customer_json='{"CustomerTaxID":12314335,"CompanyName":"Sonae","Email":"sonae@gmail.com","Password":1234,"Permission":1,"BillingAddress":{"AddressDetail":"Rua dos Clerigos","PostalCode1":4200,"PostalCode2":222,"City":"Porto","Country":"Portugal"}}';



header("Location: ./api/updateCustomer.php?customer=$customer_json");

?>
