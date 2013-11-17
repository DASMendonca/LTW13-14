$(document).ready(function() {
// inicio uma requisi�‹o
	$("#search_button").click(
			function(){
				searchByFields();	
			}
	);
	$('#search_form input').keydown(function(e) {
	    if (e.keyCode == 13) {
	    	$("#search_button").click();
	    }
	});
});

function searchByFields(){
	var params = new Array();
	var divs = $("#search_form div");
	var possible_extr_nr = $(".extrafield").length;
	//console.log(possible_extr_nr);
	var i =0;
	for(i; i<divs.length; i++){
		var well_formed = searchArrayConstruct(divs[i]);
		if(well_formed != null)
		params.push(well_formed);
	}
	console.log(params);
	if(params.length==0){
		alert("Please fill at least one of the search fields.");
		return;
	}	
	$.ajax({
        url : "./private_api/getProduct.php",
        dataType : "html",
        data : {"params":params},
        success : function(data){
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

function searchArrayConstruct(element){
	

	var operation ="";
	var value = new Array();
	var query_array = new Array();
	
	var db_ColumnName= $(element).attr("id");
	query_array.push(db_ColumnName);
	
	var selected_query = $(element).find(":selected").text();
	//console.log(selected_query);
	
	var input_type = $(element).children("input");
	//console.log(input_type);
	if($(input_type[0]).val()== ""){
		return null;
	}
	else if(input_type.length >1 && $(input_type[1]).val() == ""){
		return null;
	}
	
	if(selected_query == null || selected_query.length == 0){
		var is_text = $(input_type[0]).attr("type");
		if(is_text == "text"){
			operation = "contains";
			is_text = "%";
			is_text= is_text.concat($(input_type[0]).val());
			is_text= is_text.concat("%");
			value.push(is_text);
		}
		else{
			value.push($(input_type[0]).val());
			operation="equal";
		}
		
		query_array.push(value);
	}
	else{
		if(selected_query=="Between"){ operation = "range";}
		else if(selected_query=="Is"){ operation ="equal";}  
		else if(selected_query=="Max"){operation = "max";}  
		else if(selected_query=="Min"){operation = "min";}  
		else if(selected_query=="Contains"){operation ="contains";}
	
	
	
		if(operation != "range"){ 
			value.push(parseInt($(input_type[0]).val()));
			query_array.push(value);
		}
		else{
			value.push(parseInt($(input_type[0]).val()));
			value.push(parseInt($(input_type[1]).val()));
			query_array.push(value);
		}
	}
	
	query_array.push(operation);
	
	return query_array;
}






function createExtraFields(i){
	var optionfield = new Array();
	optionfield= $(".extrafield");
	console.log(optionfield);
	var fields= $(".inputfield")[i];
	console.log(fields);
		if(optionfield[i].selected){
			//alert(optionfield.html());
			createNewInput(fields, i);				
			}
		else{
			var new_id= "#isextra";
			new_id= new_id.concat(i);
			var rem_elem = $(new_id);
			console.log(rem_elem[0]);
			if(rem_elem != null && rem_elem.length > 0)
				removeInput(rem_elem[0], i);
		}
}


function createNewInput(element, i){
	var element2 = element.cloneNode(true);
	element2.setAttribute("class", "inputextrafield");
	
	var new_id= "isextra";	
	new_id= new_id.concat(i);
	element2.setAttribute("id", new_id);
	
	var element2_name= element.getAttribute("name");
	element2_name= element2_name.concat(i);
	element2.setAttribute("name", element2_name);
	
	console.log(element2);
	var word= "<b> and </b>";
	$(word).insertAfter(element);
	$(element2).insertAfter(element.nextSibling);
	
}

function removeInput(element, i){
	var element2 = element.previousSibling;
	while(element2.nodeType != 1){
		element2 = element2.previousSibling;
	}
	element2.remove();
	element.remove();
}