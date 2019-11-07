<?php

	session_start();
	
	require($_SERVER['DOCUMENT_ROOT']."/elements/session.php");
	
	checkSession();

	if($_SESSION['userID']){
		session_unset();
	}
	
	header("Location: http://wholesalevins.com/");


?>