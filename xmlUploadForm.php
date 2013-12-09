<?php 
include 'classes.php';
session_start();

?>
<div>
	<form id="search_form" class="bySearch" action="xmlupload.php" method="post"
		enctype="multipart/form-data">
		<label for="file">File:</label> 
		<input type="file" name="xml_portion"	id="xml_portion"><br> 
		<input type="submit" name="submit" value="Submit">
	</form>
</div>