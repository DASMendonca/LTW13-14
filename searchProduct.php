<script	src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src='search.js'></script>
		
		
		<form id="search_form" class="bySearch" name="Products" method="GET">
		<h1>Product search:</h1>
			<div id="ProductCode">
			 Product Code: 
				 <select onchange="createExtraFields(0)">
					<option selected="selected">Is</option>
					<option class="extrafield">Between</option>
					<option>Min</option>
					<option>Max</option>
				</select> <input type="number" class="inputfield"
					name="ProductCode" placeholder="product code"> <br>
			</div>
			<div id="ProductDescription">
				Product Description: <input type="text" name="ProductDescription"
					placeholder="name or description"> <br>
			</div>
			<div id="UnitPrice">
			Price per unit: 
				<select onchange="createExtraFields(1)">
					<option selected="selected">Is</option>
					<option class="extrafield">Between</option>
					<option>Min</option>
					<option>Max</option>
				</select> 
				<input name="UnitPrice" class="inputfield"
					type="number" placeholder="price per unit"><br> 						
			</div>
			<input type="button"
						value="search" id="search_button">
			</form>
	<div id="search_results_div"></div>


