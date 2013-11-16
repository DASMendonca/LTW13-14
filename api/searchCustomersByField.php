<?php

//TODO set header type
include 'classes.php';

$customers=Customer::getInstancesByFields($db, $_GET);

echo json_encode($customers);
?>