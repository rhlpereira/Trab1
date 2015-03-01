<?php
	session_start();
	session_destroy();
	if(isset($_GET['msg'])){
		echo "<h1>".$_GET['msg']."</h1><br/>";
	}
?>
<form action="index.php" method="POST">
Login: <br/><input type="text" name="login" /><br/>
Senha: <br/><input type="password" name="senha" /><br/>
<input type="submit" name="btn" value="Acessar"><br/>
<a href="cadastro.php" >Caso não seja usuario, clique aqui para se regitrar!<a/>
</form>