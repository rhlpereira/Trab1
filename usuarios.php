<?php
	session_start();
		
	// verifica se usuário já está logado no sistema
	if(isset($_SESSION["login"]) && isset($_SESSION["senha"])){
		// verifica se o tempo de execução já expirou
		if(isset($_COOKIE["logado"])){
			setcookie("logado", 'ok', time()+180);			
		} else{
			session_destroy();
			header("Location: login.php");
		}
	}
	
	//verifica se foram passados dados para alteração
	if(isset($_POST['codigo']) && isset($_POST['login']) && isset($_POST['grupo'])){
		$cod = $_POST['codigo'];
		$login = trim($_POST['login']);
		$grupo = $_POST['grupo'];
			
		$senha = "";
		if(isset($_POST['senha'])){
			if(trim($_POST['senha']) != ""){
				$senha = md5($_POST['senha']);
			}
		}
			
		$conexao = mysqli_connect('localhost','root','', 'Lab5'); 
		if (!$conexao) { 
			die('Erro ao conectar com o Banco de Dados para alterar : ' . mysql_error()); 
		}else{
			//alterando um usuário
			if($login != ""){
				$sql = "update usuarios set login = '".$login."'";
					
				if($grupo != ""){
					$sql = $sql.", grupo=".$grupo;
				}else{
					$sql = $sql.", grupo=null";
				}
					
				if($senha != ""){
					$sql = $sql.", senha='".$senha."'";
				}
					
				$sql = $sql." where cod = ".$cod;
					
				//echo $sql;
					
				$result = mysqli_query($conexao, $sql, $field=0);
				if (!$result){
					die('Erro alterando registro de grupos no Banco de Dados.');
				}
			}	
			mysqli_close($conexao);
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Informações do usuário</title>
	</head>
	<body>
		<h2>Informações do usuário</h2>
		<h3><?php echo "Usuario ".$_SESSION["login"]." conectado com sucesso!"; ?></h3>
		<form action="usuarios.php" method="POST">
			<table>
				<?php
					$login = $_SESSION['login'];
						
					// realiza a conexao com o banco de dados
					$conexao = mysqli_connect('localhost','root','', 'Lab5'); 
					if (!$conexao) { 
						die('Erro ao conectar com o Banco de Dados: ' . mysql_error()); 
					}else{
						$cod = 0;
						$grupo = null;
							
						// consultando usuário
						$sql = "SELECT cod, grupo from usuarios where login = '".$login."'";
								
						$result = mysqli_query($conexao, $sql, $field=0);
						if (mysqli_num_rows($result) > 0){
							// avalia cada linha do resultado
							while($row = mysqli_fetch_assoc($result)) {
								$cod = $row["cod"];
								$grupo = $row["grupo"];
							}
						}
							
						/* consultando grupo
						$nomegrupo = "Não encontrado!";
						if($grupo != null && trim($grupo) != ""){
							$sql = "SELECT nome from grupos where cod = ".$grupo;
								
							$result = mysqli_query($conexao, $sql, $field=0);
							if (mysqli_num_rows($result) > 0){
								// avalia cada linha do resultado
								while($row = mysqli_fetch_assoc($result)) {
									$nomegrupo = $row["nome"];
								}
							}
						}*/
							
						if($cod == 0){
							die('Usuário '.$login.' não encontrado no banco de dados!');
						}
							
						echo "<tr><td>Código:</td><td>".$cod."<input type='hidden' name='codigo' value='".$cod."' /></td></tr>";
						echo "<tr><td>Login:</td><td><input type='text' name='login' value='".$login."' /></td></tr>";
							
						// select grupos
						echo "<tr><td>Grupo:</td><td><select name='grupo'>";
							
						if($grupo != null && trim($grupo) != ""){
							$sql = "SELECT cod, nome from grupos";
								
							$result = mysqli_query($conexao, $sql, $field=0);
							if (mysqli_num_rows($result) > 0){
								// avalia cada linha do resultado
								while($row = mysqli_fetch_assoc($result)) {
									$scod = $row["cod"];
									$snome = $row["nome"];
										
									echo "<option value='".$scod."'";
										
									if($grupo != ""){
										if($grupo == $scod){
											echo " selected ";
										}
									}									
									echo ">".$snome."</option>";
								}
							}
						}
							
						echo "</select></td></tr>";
							
						//echo "<tr><td>Grupo:</td><td><input type='number' name='grupo' value='".$grupo."' /> - ".$nomegrupo."</td></tr>";
						echo "<tr><td>Senha:</td><td><input type='password' name='senha' /></td></tr>";
							
						mysqli_close($conexao);
					}
				?>
				<tr><td><input type="submit" value="Salvar"></td><td><a href="index.php">Voltar</a></td></tr>
			</table>
		</form>
	</body>
</html>