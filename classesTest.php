
<?php
include "classes.php";

$db = new PDO('sqlite:database.sqlite');
$newTax= new Tax($db, 1);
echo newTax->taxID;
echo newTax->value;
?>