<?php

// GENERATES RANDOM SALT
// RETURNS STRING
function getSalt(){
	$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$randStringLen = 64;
	
	$randString = "";
	for($i = 0; $i < $randStringLen; $i++){
		$randString = $randString . $charset[mt_rand(0, strlen($charset) - 1)];
	}
	
	return $randString;
}

// CHECKS ROLE STATUS OF USER
// RETURNS ROLE INTEGER
function getRole($userID){
	include ($_SERVER['DOCUMENT_ROOT']."/config/dbinfo.php");
	$conn = mysqli_connect('localhost',$db_user,$db_password,$db_db);
	
	if(!$conn){
		// FAILURE TO CONNECT TO DATABASE RETURNS NEGATIVE ROLE
		return -1;
	}
	
	$result = mysqli_query($conn, "SELECT * FROM users WHERE id = ".$userID);
	$row = mysqli_fetch_assoc($result);
	$role = $row['role'];
	
	mysqli_free_result($result);
	mysqli_close($conn);
	
	return $role;
}

// CHECKS A CURRENT SESSION USER ID TO MAKE SURE THE ACCOUNT IS VALID
// RETURNS BOOLEAN
function confirmUser($userID){
	include ($_SERVER['DOCUMENT_ROOT']."/config/dbinfo.php");
	$conn = mysqli_connect('localhost',$db_user,$db_password,$db_db);
	
	if(!$conn){
		return false;
	}

	$result = mysqli_query($conn,"SELECT * FROM users WHERE id = ".$userID);
	if(mysqli_num_rows != false){
		return true;
	} else {
		return false;
	}
}

// CHECKS A PASSWORD FOR A USER
// RETURNS BOOLEAN
function checkPassword($userID,$password){
	if(!confirmUser($userID)){
		return false;
	}
	
	include ($_SERVER['DOCUMENT_ROOT']."/config/dbinfo.php");
	$conn = mysqli_connect('localhost',$db_user,$db_password,$db_db);
	
	$result = mysqli_query($conn,"SELECT * FROM users where id = ".$userID);
	$row = mysqli_fetch_assoc($result);
	
	$pass = substr($row['password'],-128);
	$salt = substr($row['password'],0,-129);
	$hash = hash('sha512',$salt.$password);
	
	if($pass == $hash){
		return true;
	} else {
		return false;
	}
}

// CHANGES A USER'S PASSWORD
// RETURNS BOOLEAN
function changePassword($userID,$oldPass,$newPass,$admin = false){
	if(!confirmUser($userID)){
		return false;
	}
	if($admin == false || confirmUser($_SESSION['userID']) == false || getRole($_SESSION['userID']) < 2){
		if(!checkPassword($userID,$oldPass)){
			return false;
		}
	}
	
	include ($_SERVER['document_root']."/config/dbinfo.php");
	$conn = mysqli_connect('localhost',$db_user,$db_password,$db_db);
	
	if(!$conn){
		return false;
	}
	
	$salt = getSalt();
	$hash = hash('sha512',$salt.$newPass);
	$password = $salt . ":" . $hash;
	
	$sql = "UPDATE users SET password='".$password."' WHERE id = ".$userID;
	
	if(mysqli_query($conn,$sql)){
		return true;
	} else {
		return false;
	}
}

?>