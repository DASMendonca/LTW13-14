<?php
 include 'classes.php';

 $Country = $_REQUEST['Country'];
 $City = $_REQUEST['City'];
 $AddressDetail = $_REQUEST['AddressDetail'];
 $PostalCode1 = $_REQUEST['PostalCode1'];
 $PostalCode2 = $_REQUEST['PostalCode2'];
 
$db = new PDO('sqlite:./database.sqlite');



$params = array(array("Country", array($Country), "equal"), array("City", array($City), "equal"),
		array("PostalCode1", array($PostalCode1), "equal"), array("PostalCode2", array($PostalCode2), "equal"));

$already_in_system= Address::getInstancesByFields($db, $params);

if(count($already_in_system)==0){
	$new_address = new Address(null, $AddressDetail, $City, $PostalCode1, $PostalCode2, $Country);
	$new_address->saveToDB($db);
	header("Location: main.php");
}
else{?>
	<script type="text/javascript">
	alert('Address already exists!');
	</script>
	<?php 
	header("Location: onlineInvoiceSystem.php");
	}	
?>