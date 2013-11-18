<script	src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src='search.js'></script>

<form id="search_form" class="bySearch" name="Invoice" method="GET">
	<h1>Invoice Search:</h1>
	<div id="InvoiceNo">
		Invoice number:<input type="number" name="InvoiceNo" class="inputfield" placeholder="Invoice Number">
	</div>
	<div id="CustomerID">
		Customer ID:<input type="number" name="CustomerID" class="inputfield" placeholder="Customer Identification">
	</div>
	<div id="CompanyName">
		Company Name:<input type="text" name="CompanyName" class="inputfield" placeholder="Company Name">
	</div>
	<div id="InvoiceDate">
		<input type="date" name="InvoiceDate" class="inputfield" placeholder="YYYY-MM-DD">
	</div>
	<input type="button" value="search" id="search_button">
</form>

<div id="search_results_div"></div>