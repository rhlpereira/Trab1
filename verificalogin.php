<?php
	session_start();
		
	// verifica se usuсrio jс estс logado no sistema
	// se nуo estiver, redireciona para a pсgina de login
	function VerificaLogin(){
		if(isset($_SESSION["login"]) && isset($_SESSION["senha"])){
			// verifica se o tempo de execuчуo jс expirou
			if(isset($_COOKIE["logado"])){
				setcookie("logado", 'ok', time()+180);			
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