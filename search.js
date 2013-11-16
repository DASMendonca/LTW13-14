function createExtraFields(i){
	var optionfield = new Array();
	optionfield= $(".extrafield");
	console.log(optionfield);
	var fields= $(".inputextrafield")[i];
	console.log(fields);
		if(optionfield[i].selected){
			//alert(optionfield.html());
			cenas(fields, i);
			
				
			}
		else{
			
		}
}


function cenas(element, i){
	var element2 = element.cloneNode(true);
	var new_class= "isextra";
	new_class= new_class.concat(i);
	element2.setAttribute("class", "isextra");
	console.log(element2);
	var word= "<b> and </b>";
	$(word).insertAfter(element);
	$(element2).insertAfter(element.nextSibling);
}

function tiracenas(element, i){
	e
}

