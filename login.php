<?php
	session_start();
	session_destroy();
	if(isset($_GET['msg'])){
		echo "<h1>".$_GET['msg']."</h1><br/>";
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!--<meta charset="utf-8">-->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Sistema Web</title>

		<!-- Bootstrap -->
		<link href="bootstrap-3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<script src="bootstrap-3.3.2/js/bootstrap.min.js"></script>
	</head>
	<body>
		<form action="index.php" method="POST">
			Login: <br/><input type="text" name="login" /><br/>
			Senha: <br/><input type="password" name="senha" /><br/>
			<input type="submit" name="btn" value="Acessar"><br/>
		</form>
	</body>
</html>