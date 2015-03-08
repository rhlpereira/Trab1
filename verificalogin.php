<?php
	session_start();
		
	// verifica se usu�rio j� est� logado no sistema
	// se n�o estiver, redireciona para a p�gina de login
	function VerificaLogin($admin){
		if(isset($_SESSION["login"]) && isset($_SESSION["senha"]) && isset($_SESSION["admin"])){
			// verifica se o tempo de execu��o j� expirou
			if(isset($_COOKIE["logado"])){
				setcookie("logado", 'ok', time()+60);
					
				if($admin != $_SESSION["admin"]){
					session_destroy();
					header("Location: login.php");
				}
			} else{
				session_destroy();
				header("Location: login.php");
			}
		}else{
			session_destroy();
			header("Location: login.php");
		}
	}
?>