<?php
	// Funções para banco de dados
	include 'utildb.php';
?>
<html>
	<head>
		<title>Sistema Web</title>
	</head>

	<body>
		<h3>
			<?php
				session_start();
				
				if(isset($_SESSION["login"]) && isset($_SESSION["senha"])){
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
					$conexao = RetornaConexao(); 
							
					// consultando usuário
					$sql = "SELECT login from usuarios where login = '".$login."' and senha = '".$senha."'";
						
					$achou = false;
						
					$result = mysqli_query($conexao, $sql, $field=0);
						
					if($result == false){
						die("Não foi possível abrir a conexão " . $sql); 
					}
						
					if (mysqli_num_rows($result) > 0){
						// avalia cada linha do resultado
						while($row = mysqli_fetch_assoc($result)) {
							$achou = true;
						}
								
						if($achou){				
							echo "Usuario ".$login." conectado com sucesso!";
							setcookie("logado", 'ok', time()+180);
							$_SESSION["login"] = $login;	
							$_SESSION["senha"] = "lixo";
						}else{
							session_destroy();
							header("Location: login.php?msg=Usuario ou Senha Inválido!");				
						}
					}				
						
					mysqli_close($conexao);
				}		
			?>
		</h3>
		<ul>
			<?php
				// realiza a conexao com o banco de dados
				$conexao = RetornaConexao(); 
						
				// consultando usuário
				$sql = "SELECT login from usuarios where login = '".$_SESSION["login"]."' and admin = true";					
					
				$achou = false;
					
				$result = mysqli_query($conexao, $sql, $field=0);
					
				if($result == false){
					die("Não foi possível abrir a conexão " . $sql); 
				}
					
				if (mysqli_num_rows($result) > 0){
					// avalia cada linha do resultado
					while($row = mysqli_fetch_assoc($result)) {
						$achou = true;
					}
						
					if($achou){
						echo '<li>
								<a href="tipos.php">Tipos de Grupos de Estudo</a>
							  </li>
							  <li>
								<a href="grupos.php">Grupos de Estudo</a>
							  </li>';
					}
				}				
					
				mysqli_close($conexao);
			?>
			<li>
				<a href="usuariologado.php">Altear informações do usuário logado</a>
			</li>
			<li>
				<a href="logout.php">Sair</a>
			</li>
		</ul>
	</body>
</html>