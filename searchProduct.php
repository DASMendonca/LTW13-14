<html>
<head>
<link rel="stylesheet" href="homepage.css">
<meta charset="UTF-8">
<title>Search product</title>
<script
	src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>
<script type="text/javascript" src="search.js"></script>
</head>


<body>
	<div>
		<form id="search_form" class="bySearch" name="Products" method="GET">
		<h1>Procurar Produto:</h1>
			<div id="ProductCode">
			 Codigo do Produto: 
				 <select onchange="createExtraFields(0)">
					<option selected="selected">Is</option>
					<option class="extrafield">Between</option>
					<option>Min</option>
					<option>Max</option>
				</select> <input type="number" class="inputfield"
					name="ProductCode" placeholder="product code"> <br>
			</div>
			<div id="ProductDescription">
				Nome/Descricao do Produto: <input type="text" name="ProductDescription"
					placeholder="name or description"> <br>
			</div>
			<div id="UnitPrice">
			Preco Por Unidade: 
				<select onchange="createExtraFields(1)">
					<option selected="selected">Is</option>
					<option class="extrafield">Between</option>
					<option>Min</option>
					<option>Max</option>
				</select> 
				<input name="UnitPrice" class="inputfield"
					type="number" placeholder="price per unit"><br> 
			</div>
			
			<div id="button">
				<input type="button"
						value="search" id="search_button">
			</div>
		</form>
	</div>
	<div id="mainDiv"></div>

</body>
</html>
