$(document).ready(function() {
	$(".update_form input#save_edit" ).click(function() {
		updateEntry();
	});
	
	$(".edit_img").click(
			function(){
				editAction(jQuery(this).attr("id"));
			});
});


function updateEntry(){
	var form_divs= $(".update_form div");
	var parameters= new Array();
	var parameter = new Array();
	parameter.push($(".update_form").attr("name"));
	parameter.push($(".update_form").attr("id"));
	parameters.push(parameter);
	var i=0;
	for(i=0; i< form_divs.length; i++){
		var to_update= isToUpdate(form_divs[i]);
		if(to_update != null)
			parameters.push(to_update);
	}
	
	if(parameters.length ==1){
		alert("No changes detected.");
		return;
	}
	var reloadSession = false;
	var what_api = $(".update_div").attr("name");
	if(what_api=="Customer") {
		what_api= "./private_api/changeCustomer.php";
		reloadSession = true;
		}
	else return;
	
	console.log(parameters);
	
	
	
	$.ajax({
		type: "POST",
        url : what_api ,
        dataType : "json",
        data : {"parameters": parameters, "reloadSession": reloadSession},
        success : function(data){
          alert("Customer information updated.");
          for(i=0; i< form_divs.length; i++){
      		var to_update= isToUpdate(form_divs[i]);
      		if(to_update != null)
      			updatePlaceholders(data, form_divs[i]);
      	}
          
          //TODO update placeholders
        },
        
        error: function(jqXHR, exception) {
            if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error [500].');
            } else if (exception === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Uncaught Error.\n' + jqXHR.responseText);
            }
        }
    });	
}


function isToUpdate(div_elem){
	var db_column_name= $(div_elem).attr('id');
	var query_array= new Array();
	
	var input_field= $(div_elem).children("input");
	//var input_type= $(input_field[0]).attr('type');
	var new_val = $(input_field[0]).val();
	var pre_val= $(input_field[0]).attr('placeholder');
	
	query_array.push(db_column_name);
	
	if(new_val=="")
	query_array.push(pre_val);
	
	else
		query_array.push(new_val);
	
	return query_array;
}


function updatePlaceholders(data, div_element){
	var db_column_name= $(div_element).attr('id');
	var address_field;
	var input_field= $(div_element).children("input")[0];
	
	console.log(db_column_name);
	console.log($(input_field).attr("placeholder"));
	
	if(db_column_name == "City" || 
			db_column_name == "Country" || 
			db_column_name == "AddressDetail"||	
			db_column_name == "PostalCode1" || 
			db_column_name == "PostalCode2")
	{
		address_field = "BillingAddress";
		$(input_field).attr('placeholder', data[address_field][db_column_name]);
		}
	else{
		$(input_field).attr('placeholder', data[db_column_name]);
	}
	console.log($(input_field).attr("placeholder"));
}



function editAction(id){
	var url= $("#search_form").attr("name");
	var column="";
	var parameter = new Array();
	var url2= url;
	if(url=="Products") {url= "./api/getProduct.php"; column="ProductCode";}
	else if(url=="Invoice"){ url="./api/getInvoice.php?params="; column="InvoiceNo";}
	else if(url=="Customer"){ url="./editCustomer.php"; column="CustomerID";}
	
	var params= new Array();
	params.push(column);
	var op= "equal";
	parameter.push(id);
	params.push(op);
	 
	$.ajax({
		type: "GET",
        url : url ,
        dataType : "html",
        data : {"CustomerID": id},
        success : function(data){
        	//TODO $("#search_results_div").html(data);
        	//editionForm(data, url2);
        	$("#mainDiv").html(data);
        	
        },
        
        error: function(jqXHR, exception) {
            if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error [500].');
            } else if (exception === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Uncaught Error.\n' + jqXHR.responseText);
            }
        }
    });
	
}

function editionForm(json_obj, url){
	var column="";
	
	if(url=="Products") {url= "./api/getProduct.php"; column="ProductCode";}
	else if(url=="Invoice"){ url="./api/getInvoice.php?params="; column="InvoiceNo";}
	else if(url=="Customer"){ url="./editCustomer.php"; column="CustomerID";}
	
	$.ajax({
		type: "POST",
        url : url ,
        dataType : "html",
        data : {"params": json_obj},
        success : function(data){
          //TODO $("#search_results_div").html(data);
        	$("#mainDiv").html(data);
        },
        
        error: function(jqXHR, exception) {
            if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error [500].');
            } else if (exception === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Uncaught Error.\n' + jqXHR.responseText);
            }
        }
    });
	
}


