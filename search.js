function createExtraFields(i){
	var optionfield = new Array();
	optionfield= $(".extrafield");
	console.log(optionfield);
	var fields= $(".inputfield")[i];
	console.log(fields);
		if(optionfield[i].selected){
			//alert(optionfield.html());
			cenas(fields, i);				
			}
		else{
			var new_id= "#isextra";
			new_id= new_id.concat(i);
			var rem_elem = $(new_id);
			console.log(rem_elem[0]);
			if(rem_elem != null && rem_elem.length > 0)
				tiracenas(rem_elem[0], i);
		}
}


function cenas(element, i){
	var element2 = element.cloneNode(true);
	var new_id= "isextra";
	new_id= new_id.concat(i);
	element2.setAttribute("class", "inputextrafield")
	element2.setAttribute("id", new_id);
	console.log(element2);
	var word= "<b> and </b>";
	$(word).insertAfter(element);
	$(element2).insertAfter(element.nextSibling);
}

function tiracenas(element, i){
	var element2 = element.previousSibling;
	while(element2.nodeType != 1){
		element2 = element2.previousSibling;
	}
	element2.remove();
	element.remove();
}

