<?php

session_start();

if(isset($_POST['email'],$_POST['password'])){
    $login = '';
    
	include ($_SERVER['DOCUMENT_ROOT']."/config/dbinfo.php");
	$conn = mysqli_connect('localhost',$db_user,$db_password,$db_db);
	if(!$conn){
		$_SESSION['banner'] = "Unable to connect to login servers";
		header("Location: http://wholesalevins.com/");
		exit();
	}
	$email = strtolower($_POST['email']);
	$result = mysqli_query($conn,"SELECT * FROM users WHERE email = '$email'");
	if(!mysqli_num_rows($result) > 0){
		$login = "Incorrect login";
	} else {
		$row = mysqli_fetch_assoc($result);
			
		include ($_SERVER['DOCUMENT_ROOT']."/elements/acctfunctions.php");
		if(checkPassword($row['id'],$_POST['password'])){
			// GOOD PASSWORD
			$_SESSION['userID'] = $row['id'];
			$_SESSION['displayName'] = $row['display_name'];
			$_SESSION['role'] = getRole($_SESSION['userID']);
			mysqli_query($conn,"UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ".$_SESSION['userID']);
			$login = "Welcome back, ".$row['display_name'].".";
		} else {
			//BAD PASSWORD
			$login = "Incorrect login";
		}
	}
	mysqli_free_result($result);
	mysqli_close($conn);
	
	$_SESSION['banner'] = $login;
	
}

header("Location:http://wholesalevins.com/");

?>