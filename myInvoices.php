<?php
include 'classes.php';
session_start();


if(isset($_SESSION['customer'])){
	$myCustomer= $_SESSION['customer'];
	
	$db = new PDO('sqlite:./database.sqlite');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
	
	$params = array(array("CustomerID", array($myCustomer->CustomerID), "equal"));
	
	$myInvoices = Invoice::getInstancesByFields($db, $params);
	$to_xml= array();
	
	
	
	if($myInvoices != NULL){
		$xml_file='customer.xml';
		$current=file_get_contents($xml_file);
		?>
		<div id="search_results_div">
		<fieldset>
			<legend><h2>My invoices</h2></legend>
		<table id="search_results_tb">
			<tr>
			<th>Invoice Number</th><th>Date</th><th>Company Name</th><th>status</th>
			</tr>
	<?php
		foreach ($myInvoices as $invoice){
			array_push($to_xml, $invoice);
			$invoiceStat = $invoice->Status;
			if($invoiceStat==0)
				$invoiceStat="Open";
			else
				$invoiceStat="Close";
			echo utf8_encode('
					<tr>
					<td>' .$invoice->InvoiceNo .'</td>
					<td>' .$invoice->StartDate .'</td>
					<td>' .$invoice->Customer->CompanyName.'</td>
					<td>' .$invoiceStat.'</td>');?>
					<td><img src="./pictures/add.png" width="16" height="16" border="0" alt="Detailed"
						class="detail_img" id="<?php echo $invoice->InvoiceNo;?>"/></td>
			<?php 
				if($invoice->Status==0){
					echo '
		<td><img src="./pictures/edit.png" width="16" height="16" border="0" alt="Edit Invoice"
					class="edit_img" id="'.$invoice->InvoiceNo.'"/></td>
			';
			
					}
			
				echo '</tr>';	
				
		}
		$current.=Invoice::exportSAFT_File($to_xml);
		file_put_contents($xml_file, $current);
		echo '</table>
			<form action="xmldownload.php" method="post"
			enctype="multipart/form-data">
			<input type="submit" name="submit" value="Download as XML">
				</form>
			</fieldset></div>';
	}
	
}