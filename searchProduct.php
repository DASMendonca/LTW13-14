<html>
<head>
<meta charset="UTF-8">
<title>Search product</title>
<script
	src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>
<script type="text/javascript" src="search.js"></script>
</head>


<body>
	<div>
		<form id="prodSearch" class="bySearch">
			<div>
				<select onchange="createExtraFields(0)">
					<option selected="selected">Is</option>
					<option class="extrafield">Between</option>
					<option>Min</option>
					<option>Max</option>
				</select> <input type="number" class="inputfield"
					name="ProductCode" placeholder="product code"> <br>
			</div>
			<div>
				<input type="text" name="ProductDescription"
					placeholder="name or description"> <br>
			</div>
			<div>
				<select onchange="createExtraFields(1)">
					<option selected="selected">Is</option>
					<option class="extrafield">Between</option>
					<option>Min</option>
					<option>Max</option>
				</select> <input name="UnitPrice" class="inputfield"
					type="number" placeholder="price per unit"><br> 
					<input type="button"
					action="searchprdoresults.php" value="search">
			</div>
		</form>
	</div>

</body>
</html>
