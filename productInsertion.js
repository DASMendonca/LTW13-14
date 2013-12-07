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
	var input_fields= $("form").children("input");
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
	
}