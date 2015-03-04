<?php
	session_start();
	session_destroy();
	setcookie("logado", 'ok', time()-1);
	header("Location: login.php");
?>