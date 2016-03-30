<?php
require "config.inc.php";

if(!empty($_POST)) {
	//load user info from DB
	$query = "SELECT id, email, username, password FROM users WHERE email = :email";

	$query_params = array(
		'email' => urldecode($_POST['email']));
		
	try {
		$stmt = $db -> prepare($query);
		$result = $stmt -> execute($query_params);
	} catch (PDOException $ex) {
		$response["success"] = 0;
		$response["message"] = "Database error1. Please try again.";
		
		die(json_encode($response));
	}
	
	$login_ok = false;
	
	//load response from DB
	$row = $stmt -> fetch();
	
	//check if user exists
	if($row) {
		
		if($_POST['password'] === $row['password']) {
			$login_ok = true;
		}
	}
	
	if($login_ok) {
		$response["success"] = 1;
		$response["message"] = "Login successful";
		
		die(json_encode($response));
	} else {
		$response["success"] = 0;
		$response["message"] = "Invalid Username or Password!";
		
		die(json_encode($response));
	}
} else {
?>
	<h1>Login</h1>
	<form action="login.php" method="post">
		Username:<br/>
		<input type="text" name="username" placeholder="username"/><br/><br/>
		Password:<br/>
		<input type="password" name="password" placeholder="password"/><br/><br/>
		<input type="submit" value="Login"/>
	</form>
	
	<a href="register.php">Register</a>
<?php
}
?>