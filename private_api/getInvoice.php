<?php

include '../classes.php';

header('Content-type: text/html; charset=UTF-8');
?>
<script	src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src='search.js'></script>
<fieldset>
	<legend></legend><h2>Search Results</h2></legend>
	
<?php

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

session_start();


try {
	if(!isset($_SESSION["customer"])) throw new GeneralException(new Err_Autentication());

	if(!isset($_GET['params']))
		throw new GeneralException(new Err_MissingParameter("params"));
	
	
	
	
	$invoices=Invoice::getInstancesByFields($db, $_GET["params"]);
} catch (GeneralException $e) {
	echo '</fieldset>';
	die();
}

if($invoices != NULL){
	?>
	<table id="search_results_tb">
		<tr>
		<th>Invoice Number</th><th>Invoice Date</th><th>Costumer ID</th><th>Company Name</th><th>Gross Total</th>
		</tr>
<?php
	foreach ($invoices as $invoice){
		if(isset($_SESSION['customer']) && $_SESSION['customer']->CustomerID == $invoice->getCustomerId() || 
			$_SESSION['customer']->Permission >1){
		echo utf8_encode('<tr>
				<td>' .$invoice->InvoiceNo .'</td>
				<td>' .$invoice->InvoiceDate .'</td>
				<td>' .$invoice->getCustomerId() .'</td>
				<td>' .$invoice->CompanyName.'</td>
				<td>' .((int)$invoice->GrossTotal/100).' &euro; </td>');?>
				<td><img src="./pictures/add.png" width="16" height="16" border="0" alt="Detailed"
					class="detail_img" id="<?php echo $invoice->InvoiceNo;?>"/></td>
				<?php if($_SESSION['customer']->Permission >1) echo '
				<td><img src="./pictures/edit.png" width="16" height="16" border="0" alt="Edit Invoice"
					class="edit_img" id="<?php echo $invoice->InvoiceNo;?>"/></td>
			<tr>
			<?php	
			}
	}
	echo '</table>
		</fieldset>';
}
?>