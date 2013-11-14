
$(document).ready(function() {
// inicio uma requisi�‹o
	$("#SignInButton").click(
			function(){
				askIfLoginIsOk();	
			}
	);
});


function askIfLoginIsOk(){
	
	var emailField= $("#emailInput");
	var email=emailField.val();
	var pw= $("#pwInput").val();
	
	$.ajax({
        url : "loginAjax.php",
        dataType : "json",
        data : {"email":email,"password":pw},
        success : function(data){
           if($.isEmptyObject(data))alert("Invalid user and/or password.");
           else $('#loginForm').submit();
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
    });//termina o ajax
	
}



function loggedIn(){	
	$(document).ready(function() {
		// inicio uma requisi�‹o
		$(".logged").css('visibility', 'visible');
		});
}