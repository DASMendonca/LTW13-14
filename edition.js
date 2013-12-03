$(document).ready(function() {
	$(".update_form input#save_edit" ).click(function() {
		updateEntry();
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
	var what_api = $(".update_div").attr("name");
	if(what_api=="Customer") what_api= "./private_api/changeCustomer.php";
	else return;
	
	console.log(parameters);
	
	$.ajax({
		type: "POST",
        url : what_api ,
        dataType : "json",
        data : {"parameters": parameters},
        success : function(data){
          alert("Customer information updated.");
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