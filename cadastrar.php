<?php
	/*
	if(!isset($_POST['login']) || !isset($_POST['senha'])){
		session_destroy();
		header("Location: login.php?msg=Informe usuário e senha.");
	}
	
	// Funções para banco de dados
	include 'utildb.php';
	
	// recebe os dados para serem cadastrados
	$login = $_POST['login'];
	$senha = md5($_POST['senha']);	
	// realiza a conexao com o banco de dados
	$conexao = RetornaConexao(); 
	if (!$conexao) { 
		die('Erro ao conectar com o Banco de Dados: ' . mysql_error()); 
	}else{		
		// tentar inserir o novo usuário no banco de dados
		$sql = "INSERT INTO usuarios (login, senha) VALUES ('$login', '$senha')";
		if (mysqli_query($conexao, $sql)) {
			echo "Usuario criado com sucesso! =D <br/>";
			echo "<a href=\"login.php\" >Clique aqui para retornar <a/>";
		} else {
			echo "Erro: " . $sql . "<br>" . mysqli_error($conexao);
		}	
		mysqli_close($conexao);
	}	
	*/
?>