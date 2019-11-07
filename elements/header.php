<head>
	<link rel="stylesheet" href="../css/main.css">
	<link rel="stylehseet" href="../css/bootstrap.css.map">
</head>

<body>
<?php
    if(!empty($_SESSION['banner'])){
        echo $_SESSION['banner'];  
        unset($_SESSION['banner']);
    }
?>
<div class="container">
<ul class="navbar-fixed-top">
	<li><a href="index.php">Home</a></li>
	<?php
		
		include($_SERVER['DOCUMENT_ROOT']."/elements/session.php");
		checkSession();
		$_SESSION['lastUse'] = time();
		
		if(!empty($_SESSION['userID'])){
			echo '<li class="navbarli">Welcome, '.$_SESSION["displayName"].'!</li>
				<li><a href="account.php">Account</a></li>
				<li><a href="elements/logout.php">Logout</a></li>
				';
		} else{
			echo '<li><a href="login.php">Login</a></li>
				<li><a href="register.php">Register</a></li>';
		}
		echo $_SESSION['pageviews'];
	
	?>
</ul>
</div>
</body>