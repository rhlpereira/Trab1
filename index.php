<?php
	// Funções para banco de dados
	include 'utildb.php';
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
		<?php
			session_start();
				
			$login = null;
			$senha = null;
			$admin = null;
				
			if(isset($_SESSION["login"]) && isset($_SESSION["senha"]) && isset($_SESSION["admin"])){
				if(isset($_COOKIE["logado"])){
					$login = $_SESSION["login"];
					$admin = $_SESSION["admin"];
					setcookie("logado", 'ok', time()+60);
				} else{
					session_destroy();
					header("Location: login.php");
				}
			}else{
				if(!isset($_POST['login']) || !isset($_POST['senha'])){
					session_destroy();
					header("Location: login.php");
				}
					
				$login = $_POST['login'];
				$senha = md5($_POST['senha']);
					
				// realiza a conexao com o banco de dados
				$conexao = RetornaConexao(); 
						
				// consultando usuário
				$sql = "SELECT admin from usuarios where login = '".$login."' and senha = '".$senha."'";
					
				$achou = false;
					
				$result = mysqli_query($conexao, $sql, $field=0);
					
				if($result == false){
					die("Não foi possível abrir a conexão " . $sql); 
				}
					
				if (mysqli_num_rows($result) > 0){
					// avalia cada linha do resultado
					while($row = mysqli_fetch_assoc($result)) {
						$achou = true;
						$admin = $row["admin"];
					}
				}				
					
				mysqli_close($conexao);
					
				if($achou){				
					setcookie("logado", 'ok', time()+180);
					$_SESSION["login"] = $login;	
					$_SESSION["senha"] = $senha;
					$_SESSION["admin"] = $admin;
				}else{
					session_destroy();
					header("Location: login.php?msg=Usuario ou Senha Inválido!");				
				}
			}
				
			echo "<h3>Usuario ".$login." conectado com sucesso!</h3>";
			echo "<ul>";
				
			if($admin == true){
				echo '<li>
						<a href="tipos.php">Cadastro de Tipos de Grupos de Estudo</a>
					  </li>
					  <li>
						<a href="grupos.php">Cadastro de Grupos de Estudo</a>
					  </li>
					  <li>
						<a href="usuarios.php">Cadastro de Usuarios</a>
					  </li>';
			}else{
				echo '<li>
						<a href="usuariologado.php">Altear informações do usuário logado</a>
					  </li>';
			}
				
			echo "<li><a href='logout.php'>Sair</a></li></ul>";
			?>
	</body>
</html>