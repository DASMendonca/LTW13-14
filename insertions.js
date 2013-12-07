/**
 * 
 */

$(document).ready(function() {
// inicio uma requisi�‹o
	$("body").on('click', '.to_db_btn', 
			function(){
				checkInputs();	
			}
	);
	
	$("body").on('keydown','.to_db_form input',
			function(e) {
	    		if (e.keyCode == 13) {
	    			$("#to_db_btn").click();
	    		}
			});
});



function checkInputs(){
	var input_fields= $(".to_db_form").children("input");
	var i = 0;
	var this_input;
	
	for(; i< input_fields.length; i++){
		this_input = $(input_fields[i]).val();
		if(this_input == ""){
			alert("All fields must be filled");
			return;
		}
	}	
	
	insertIntoDb();
}



function insertIntoDb(){
	
	var query_array= new Array();
	var db_table_name = $(".to_db_form").attr("name");
	var input_fields = $(".to_db_form").children("input");
	var select_fields = $(".to_db_form").children("select");
	var column_name;
	var to_column_val;
	
	var is_new= new Array();
	
	if(db_table_name =="Product") is_new.push("ProductCode");
	else if(db_table_name =="Invoice") is_new.push("InvoiceNo");
	else if(db_table_name =="Product") is_new.push("ProductCode");
	else return null;
	
	is_new.push("");	
	query_array.push(is_new);
	
	var i= 0;
	
	for(; i< input_fields.length; i++){
		this_input = input_fields[i];
		
		var param= new Array();
		
		column_name = $(this_input).attr("name");
		to_column_val = $(this_input).val();
		
		if(to_column_val != "Insert"){
		param.push(column_name);
		param.push(to_column_val);
		
		query_array.push(param);
	}
		
	}
	
	for(i=0; i< select_fields.length; i++){
		this_select = select_fields[i];
		
		var param= new Array();
		
		column_name = $(this_select).attr("name");
		to_column_val = $(this_select).find(":selected").val();
		
		param.push(column_name);
		param.push(to_column_val);
		
		query_array.push(param);
	}
	
	callAjax(db_table_name, query_array);
}


function callAjax(table_name, parameters){
	
	var what_api;
	
	if(table_name=="Customer")			what_api= "./private_api/changeCustomer.php";		
	else if(table_name == "Product") 	what_api= "./private_api/changeProduct.php";
	else if(table_name== "Invoice") 	what_api= "./private_api/changeInvoice.php";
	else return;
	
	var ignore = false;
	
	
	$.ajax({
		type: "POST",
        url : what_api ,
        dataType : "json",
        data : {"parameters": parameters, "reloadSession": ignore},
        success : function(data){
          if(typeof data.error != undefined)
        	  alert(data.error.fields);
          
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