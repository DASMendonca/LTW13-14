
<?php
include 'classes.php';

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

//$taxes=Tax::getInstancesByFields($db, array("TaxValue"=>13));
$products= Product::getInstancesByFields($db, array("UnitOfMeasure"=>"kg","ProductTypeID"=>3));
?>