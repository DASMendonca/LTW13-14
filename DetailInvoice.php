<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="DetailsPrint.css" media="print">
<title>DetailInvoice</title>
</head>

<body>
	<table class="Logo">
		<tr>
			<th>Sistema de Faturação Online</th>
		</tr>
		<tr>
			<td>Linguagens e Tecnologias Web</td>
		</tr>
	</table>

	<?php 
include './classes.php';
$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);



try {
	if(!isset($_GET['params']))
		throw new GeneralException(new Err_MissingParameter("params"));

	$params=array(json_decode($_GET["params"]));



	$invoices=invoice::getInstancesByFields($db, $params);
	$invoice=$invoices[0];
	
	$invoiceNo=$invoice->InvoiceNo;
	$invoiceDate=$invoice->InvoiceDate;
	$invoiceCompanyName=$invoice->CompanyName;
	$invoiceGrossTotal=$invoice->GrossTotal;
	$invoiceCustomerID=$invoice->getCustomerId();
	$invoiceLines =$invoice->getLines();
	
} catch (GeneralException $e) {

	$invoiceCode=-1;
	$invoiceName="No Name";
	$invoiceAddress="No Address";
	$invoicePostalCode1="0000";
	$invoicePostalCode2="000";
	$invoiceCity="No City";
	$invoiceNif="000000000";
	$invoiceEmail="noemail@nodomain.com";
}

	
	<p class="sheetID">Dados de Cliente</p>
	<br>
	<br>
	<p class="rowID">Código de Cliente:</p>
	<p>0001</p>
	<br>
	<p class="rowID">Nome:</p>
	<p>Filomena</p>
	<br>
	<p class="rowID">NIF:</p>
	<p>123456321</p>
	<br>
	<br>

	<p class="sheetID">Factura</p>
	<br>
	<table class="invoice">
		<tr>
			<td class="rowID">Fatura n.º:</td>
			<td>0001</td>
			<td class="rowID">Data:</td>
			<td>2013-12-12</td>
		</tr>
	</table>
	<br>

	<p class="Articles">Artigos</p>
	<br>
	<table class="products">
		<tr>
			<th>Cód. Produto</th>
			<th>Descrição do Produto</th>
			<th>UN</th>
			<th>Quantidade</th>
			<th>Preço Unit.</th>
			<th>IVA</th>
			<th>Preço Total</th>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
		<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
		<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
		<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
		<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
	</table>


</body>
</html>
