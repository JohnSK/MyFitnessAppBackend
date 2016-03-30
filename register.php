
<?php
	require "config.inc.php";

	if(!empty($_POST)) {
		if(empty($_POST['username']) || empty($_POST['password'])) {
			$response["success"] = 0;
			$response["message"] = "Please enter both Username and Password!";
			
			die(json_encode($response));
		}
		
		// check if email exists
		$query = "SELECT 1 FROM users WHERE email = :email";
		$query_params = array(
			'email' => urldecode($_POST['email']));
		
		try{
			$stmt = $db -> prepare($query);
			$result = $stmt -> execute($query_params);
		} catch (PDOException $ex) {
			$response["success"] = 0;
			$response["message"] = "Database connection error!";
			
			die(json_encode($response));
		}
		
		$row = $stmt -> fetch();
		if($row) {
			$response["success"] = 0;
			$response["message"] = "This email is already taken!";
			
			die(json_encode($response));
		}
		
		// check if username exists
		$query = "SELECT 1 FROM users WHERE username = :username";
		$query_params = array(
			'username' => $_POST['username']);
		
		try{
			$stmt = $db -> prepare($query);
			$result = $stmt -> execute($query_params);
		} catch (PDOException $ex) {
			$response["success"] = 0;
			$response["message"] = "Database connection error!";
			
			die(json_encode($response));
		}
		
		$row = $stmt -> fetch();
		if($row) {
			$response["success"] = 0;
			$response["message"] = "This username is already taken!";
			
			die(json_encode($response));
		}
		
		$query = "INSERT INTO users(email, username, password) VALUES(:email, :username, :password)";
		$query_params = array(
			'email' => urldecode($_POST['email']),
			'username' => $_POST['username'],
			'password' => $_POST['password']);
		
		try{
			$stmt = $db -> prepare($query);
			$result = $stmt -> execute($query_params);
		} catch (PDOException $ex) {
			$response["success"] = 0;
			$response["message"] = "Database connection error!";
			
			die(json_encode($response));
		}
		
		$response["success"] = 1;
		$response["message"] = "User Registration successful!";
		
		echo json_encode($response);
	} else {
?>
	<h1>Register</h1>
	
	<form action="register.php" method="post">
		Username:<br/>
		<input type="text" name="username" value=""/><br/><br/>
		Password:<br/>
		<input type="password" name="password" value=""/><br/><br/>
		<input type="submit" value="Register New User"/>
	</form>
<?php
	}
?>