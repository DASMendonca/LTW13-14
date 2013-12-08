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
	
	
	if($myInvoices != NULL){
		?>
		<table id="search_results_tb">
			<tr>
			<th>Invoice Number</th><th>Date</th><th>Company Name</th><th>status</th>
			</tr>
	<?php
		foreach ($myInvoices as $invoice){
			echo utf8_encode('
					<tr>
					<td>' .$invoice->InvoiceNo .'</td>
					<td>' .$invoice->StartDate .'</td>
					<td>' .$invoice->Customer->CompanyName.'</td>');?>
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
		echo '</table>
			</fieldset>';
	}
	
}