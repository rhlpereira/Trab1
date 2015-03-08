<?php
	// Verifica e valida o usuário logado
	include 'verificalogin.php';
	VerificaLogin(false);
	
	// Funções para banco de dados
	include 'utildb.php';
	
	//verifica se foram passados dados para alteração
	$msgAltera = AlertaUsuarioPost();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!--<meta charset="utf-8">-->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Informações do usuário logado</title>

		<!-- Bootstrap -->
		<link href="bootstrap-3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<script src="bootstrap-3.3.2/js/bootstrap.min.js"></script>
	</head>
	<body>
		<h2>Informações do usuário</h2>
		<h3><?php echo "Usuario ".$_SESSION["login"]." conectado com sucesso!"; ?></h3>
		<?php
			if($msgAltera != ""){
				echo "<h4>" . $msgAltera . "</h4>";
			}
		?>
		<form action="usuariologado.php" method="POST">
			<table>
				<?php
					$login = $_SESSION['login'];
						
					// realiza a conexao com o banco de dados
					$conexao = RetornaConexao(); 
					if (!$conexao) { 
						die('Erro ao conectar com o Banco de Dados: ' . mysql_error()); 
					}else{
						$cod = 0;
						$grupo = null;
						$endereco = null;
						$nascimento = null;
						$email = null;
						$sexo = null;
						$tel1 = null;
						$tel2 = null;
						$tel3 = null;
							
						// consultando usuário
						$sql = "SELECT cod, grupo, endereco, DATE_FORMAT(nascimento, '%d/%m/%Y') as 'nascimento', email, upper(sexo) as 'sexo', tel1, tel2, tel3 from usuarios where login = '".$login."'";
								
						$result = mysqli_query($conexao, $sql, $field=0);
						if (mysqli_num_rows($result) > 0){
							// avalia cada linha do resultado
							while($row = mysqli_fetch_assoc($result)) {
								$cod = $row["cod"];
								$grupo = $row["grupo"];
								$endereco = $row["endereco"];
								$nascimento = $row["nascimento"];
								$email = $row["email"];
								$sexo = $row["sexo"];
								$tel1 = $row["tel1"];
								$tel2 = $row["tel2"];
								$tel3 = $row["tel3"];
							}
						}
							
						if($nascimento == "00/00/0000"){
							$nascimento = null;
						}
							
						if($cod == 0){
							die('Usuário '.$login.' não encontrado no banco de dados!');
						}
						
						// Código
						echo "<tr><td>Código:</td><td>".$cod."<input type='hidden' name='codigo' value='".$cod."' /></td></tr>";
						
						// Login
						echo "<tr><td>Login:</td><td><input type='text' name='login' value='".$login."' /></td></tr>";
							
						// Grupos
						echo "<tr><td>Grupo:</td><td>";
						echo ObjetoSelectGrupos($grupo); 
						echo "</td></tr>";
							
						// Senha
						echo "<tr><td>Nova Senha:</td><td><input type='password' name='senha' /></td></tr>";
							
						// Endereço
						echo "<tr><td>Endereço:</td><td><textarea name='endereco' cols='25' rows='10'>". $endereco ."</textarea></td></tr>";
						
						// Nascimento
						echo "<tr><td>Nascimento:</td><td><input type='text' name='nascimento' value='" . $nascimento . "' /></td></tr>";
						
						// Email
						echo "<tr><td>E-mail:</td><td><input type='text' name='email' value='" . $email . "' /></td></tr>";
						
						// Sexo
						echo "<tr><td>Sexo:</td><td>";
						echo ObjetoSelectSexo($sexo); 
						echo "</td></tr>";
						
						// Telefone 1
						echo "<tr><td>Telefone 1:</td><td><input type='text' name='tel1' value='" . $tel1 . "' /></td></tr>";
						
						// Telefone 2
						echo "<tr><td>Telefone 2:</td><td><input type='text' name='tel2' value='" . $tel2 . "' /></td></tr>";
						
						// Telefone 3
						echo "<tr><td>Telefone 3:</td><td><input type='text' name='tel3' value='" . $tel3 . "' /></td></tr>";
							
						mysqli_close($conexao);
					}
				?>
				<tr>
					<td><input type="submit" value="Salvar"></td>
					<td><a href="index.php">Voltar</a></td>
				</tr>
			</table>
		</form>
	</body>
</html>