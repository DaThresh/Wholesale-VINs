<?php
	function checkSession(){
		if($_SESSION['lastUse']){
			$diff = time() - $_SESSION['lastUse'];
			if ($diff > 3600){
				session_unset();
			}
		}
		if($_SESSION['pageviews']){
			$_SESSION['pageviews'] = $_SESSION['pageviews'] + 1;
		} else{
			$_SESSION['pageviews'] = 1;
		}
	}

?>