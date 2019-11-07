<?php session_start(); ?>

<html>
<head>
	<title>Wholesale VINs - Register</title>
</head>

<?php
	require('elements/header.php');
	include('elements/acctfunctions.php');
	include('config/dbinfo.php');
?>

<?php
	if($_POST){
		if($_POST['email']){
			if(!strpos($_POST['email'], '@') || !strpos($_POST['email'], '.') || strpos($_POST['email'], ' ')){
				die('Invalid Email address');
			}
			if(strrpos($_POST['email'], '@') == strlen($_POST['email'])-1 || strrpos($_POST['email'], '@') != strpos($_POST['email'], '@')){
				die('Invalid email domain');
			}
			$pos = (strlen($_POST['email'])-1-strpos($_POST['email'], '@')) * -1;
			$domain = substr($_POST['email'], $pos);
			if(strpos($_POST['email'], '.') == strlen($_POST['email'])-1 || !strpos($domain, '.') || strpos($domain, '.') == 0){
				die('Invalid email domain');
			}
			if($_POST['password'] && $_POST['confirm']){
				if($_POST['name']){
					if ($_POST['password'] === $_POST['confirm']){
						require_once('config/dbinfo.php');
						$conn = mysqli_connect('localhost',$db_user,$db_password,$db_db);
						
						if(!$conn){
							die('Unable to connect to database');
						}
						
						$email = strtolower($_POST['email']);
						
						$result = mysqli_query($conn,"SELECT * FROM users WHERE email = '$email'");
						if(mysqli_num_rows($result) > 0){
							die('Account with that email already exists.');
						} else{
							// REGISTER THE USER
							$name = $_POST['name'];
							
							$salt = getSalt();
							$hash = hash('sha512', $salt.$_POST['password']);
							$password = $salt . ":" . $hash;
							
							$sql = "INSERT INTO users (email,password,display_name,role)
							VALUES ('$email','$password','$name',1)";
							
							if (mysqli_query($conn,$sql)){
								// REGISTRATION SUCCESSFUL
								$_SESSION['displayName'] = $name;
								$_SESSION['userID'] = mysqli_insert_id($conn);
								$_SESSION['role'] = 1;
								$revisit = true;
								echo 'Registration successful.';
							} else {
								// REGISTRATION UNSUCCESSFUL
								echo 'Registration unsuccessful.';
							}
						}
						mysqli_free_result($result);
						mysqli_close($conn);
					} else {
						die('Passwords do not match!');
					}
				} else {
					die('You must enter a name for the account');
				}
			} else {
				die('You must enter & confirm a password!');
			}
		} else {
			die('Invalid Email address');
		}
	} else {
		if ($_SESSION['userID']){
			echo 'No need to register, already logged in';
		} else {
			echo '<body>
					<form action="register.php" method="post" id="register">
						Email: <input type="text" name="email">
						<br>
						Account name: <input type="text" name="name">
						<br>
						Password: <input type="password" name="password">
						<br>
						Confirm password: <input type="password" name="confirm">
						<br>
						<input type="submit" value="Register">
					</form>
				</body>';
		}
	}

?>

</html>