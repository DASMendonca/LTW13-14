
<?php
include 'classes.php';

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$parameters=array(
<<<<<<< HEAD
	array("ProductCode",array(2),"equal"),
);

$result=Product::getInstancesByFields($db, $parameters);
=======
	array("City",array("Porto"),"equal")
);

$result=Address::getInstancesByFields($db, $parameters);
>>>>>>> 354d9c031d6bdadd7ecc563d9861b5ac6847c400
?>