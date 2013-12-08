/**
 * 
 */


$(document).ready(function() {
// inicio uma requisi�‹o
	$("body").on('click', '.rem_line', 
			function(){
				var vals = new Array();
				//name is for invoice
				//id is for line
				vals.push(jQuery(this).attr("id"));
				vals.push(jQuery(this).attr("name"));
				
				removeLine(vals);	
			}
	);
});


function removeLine(parameters){
	
	var what_api= "deleteInvoiceLine.php";
	
	
	$.ajax({
		type: "POST",
        url : what_api ,
        dataType : "json",
        data : {"DELETION": parameters},
        success : function(data){
        	currentInvoice(parameters[1]);     	  
       
        
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



function currentInvoice(id){
	var url="./editInvoice.php"; 
	
	
	$.ajax({
		type: "GET",
        url : url ,
        dataType : "html",
        data : {"param": id},
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