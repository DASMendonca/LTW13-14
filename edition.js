$( ".update_form input#save_edit" ).on( "click", function() {
	var form_divs= $(".update_form div");
	var parameters= new Array();
	var parameter = new Array();
	parameter.push($(".update_for").attr("name"));
	parameter.push($(".update_for").attr("id"));
	parameters.push(parameter);
	var i=0;
	for(i=0; i< form_divs.length; i++){
		var to_update= isToUpdate(form_divs[i]);
		if(to_update != null)
			parameters.push(to_update);
	}
	
	
});


function isToUpdate(div_elem){
	var db_column_name= $(div_elem).attr('id').val();
	var query_array= new Array();
	
	var input_field= $(div_elem).children("input");
	var input_type= $(input_field[0]).attr('type');
	var new_val = $(input_field[0]).val();
	var pre_val= $(input_field[0]).attr('placeholder');
	
	
	if(input_type.localeCompare("text")==0 || input_type.localeCompare("email")==0){
	if(new_val == null || new_val.localeCompare(pre_val)==0)
		return null;
	}
	else{
		if(new_val== pre_val)
			return null;
	}
	query_array.push(db_column_name);
	query_array.push(new_val);
	
	return query_array;
}