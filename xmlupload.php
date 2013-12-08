<?php
include 'classes.php';
session_start();




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
  //$mystr= file_get_contents($_FILES["xml_portion"], FILE_USE_INCLUDE_PATH);
  
  $line = Line::fromXML($str);
  
  echo $line->toXML();
  }
  
  
?>