<script	src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src='search.js'></script>

<form id="search_form" class="bySearch" name="Customer" method="GET">
	<h1>Costumer Search:</h1>
	<div id="CustomerID">
		Customer ID:<input type="number" name="CustomerID" class="inputfield" placeholder="Customer Identification">
	</div>
	<div id="CustomerName">
		Customer Name:<input type="text" name="CustomerName" class="inputfield" placeholder="Customer Name">
	</div>
	<div id="Email">
		Email:<input type="email" name="Email" class="inputfield" placeholder="email@example.com">
	</div>
	<input type="button" value="search" id="search_button">
</form>

<div id="search_results_div"></div>