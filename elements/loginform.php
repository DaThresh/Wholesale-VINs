<?php

	if($_SESSION['userID']){
		
	} else {
		echo '
		<body>
			<form action="elements/login.php" method="post" id="login">
				Email: <input type="text" name="email">
				<br>
				Password: <input type="password" name="password">
				<br>
				<input type="submit" value="submit">
			</form>
		</body>
		';
	}
	
?>