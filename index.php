<html>
	<head>
		<title>Sistema Web</title>
	</head>

	<body>
		<h3>
			<?php
				session_start();
				// verifica se usu�rio j� est� logado no sistema
				if(isset($_SESSION["login"]) && isset($_SESSION["senha"])){
					// verifica se o tempo de execu��o j� expirou
					if(isset($_COOKIE["logado"])){
						echo "Usuario ".$_SESSION["login"]." conectado com sucesso!";
						setcookie("logado", 'ok', time()+180);			
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
					$conexao = mysqli_connect('localhost','root','', 'Lab5'); 
					if (!$conexao) { 
						die('Erro ao conectar com o Banco de Dados: ' . mysql_error()); 
					}else{		
						// consultando usu�rio
						$sql = "SELECT count(login) as qtd from usuarios where login = '".$login."' and senha = '".$senha."'";
							
						$result = mysqli_query($conexao, $sql, $field=0);
						if (mysqli_num_rows($result) > 0){
							// avalia cada linha do resultado
							while($row = mysqli_fetch_assoc($result)) {
								$achou = $row["qtd"];
							}	
							if($achou){				
								echo "Usuario ".$login." conectado com sucesso!";
								setcookie("logado", 'ok', time()+180);
								$_SESSION["login"] = $login;	
								$_SESSION["senha"] = "lixo";				
							}else{
								session_destroy();
								header("Location: login.php?msg=Usuario ou Senha Inv�lido!");				
							}
						}				
						mysqli_close($conexao);
					}
				}		
			?>
		</h3>
		<ul>
			<li>
				<a href="grupos.php">Grupos de Estudo</a>
			</li>
			<li>
				<a href="usuarios.php">Altear informa��es do usu�rio</a>
			</li>
		</ul>
	</body>
</html>