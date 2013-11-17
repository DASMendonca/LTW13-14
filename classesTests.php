<?php
include 'classes.phh';

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$fields=array(
	array("LineNumber",array(1),"equal")
		
		
);

$Lines= Line::getInstancesByFields($db, $fields);
