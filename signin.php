<?php

$email = $_GET['email'];
$password = $_GET['password'];


$db = new PDO('sqlite:./database.sqlite');
$stmt="SELECT * FROM Customer WHERE Email=? AND Password=?;";

/*$email= "admin@ois.com";
$password= "admin";
*/
$query=$db->prepare($stmt);
$query->bindParam(1,$email);
$query->bindParam(2,$password);

$query->execute();
$result=$query->fetchAll();

if(!$result){
	?>
	<script type="text/javascript">
		function(){
			alert("Wrong User and/or Password");
		}
	</script>
	<?php
}

else{
	 session_start();
	   $_SESSION['customer'] = $email;
	   $_SESSION['pwd'] = $password;
	   header("Location: mainPage.html");
		die();
}
?>