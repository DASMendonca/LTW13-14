<?php
include 'classes.php';
session_start();


$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);



if ($_FILES["xml_portion"]["error"] > 0)
  {
  echo "Error: " . $_FILES["xml_portion"]["error"] . "<br>";
  }
else
  {
  echo "Upload: " . $_FILES["xml_portion"]["name"] . "<br>";
  echo "Type: " . $_FILES["xml_portion"]["type"] . "<br>";
  echo "Size: " . ($_FILES["xml_portion"]["size"] / 1024) . " kB<br>";
  echo 'Stored in:  '. $_FILES["xml_portion"]["tmp_name"] .'<br>';
  
  $str = utf8_encode(file_get_contents($_FILES["xml_portion"]["tmp_name"]));
  
  Invoice::importSAFT_File($db, $str);
  
  
 // $file = file_get_contents($_FILES["xml_portion"]["tmp_name"], true);
  
  
  
  
  echo '<br> <br>';
  echo $str;
  
  
  }
  
  
?>