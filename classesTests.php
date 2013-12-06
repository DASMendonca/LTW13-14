<?php
include 'classes.php';

$customer=new Customer(null, 5555555, "Joao", "joao@gmail.com", 1234, 1);
$customer_json='{"CustomerTaxID":5555555,"CompanyName":"Joao","Email":"joao@gmail.com","Password":1234,"Permission":1}';



header("Location: ./api/updateCustomer.php?customer=$customer_json");

?>
