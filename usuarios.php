<?php
	// Verifica e valida o usuário logado
	include 'verificalogin.php';
	VerificaLogin(true);
	
	// Funções para banco de dados
	include 'utildb.php';
	
	$msgErro = "";
	
	// Verifica se foi informado um registro para inserir
	if(isset($_POST['login']) && isset($_POST['submit'])){
		if($_POST['submit'] == "Adicionar"){
			$msgErro = InsereUsuarioPost();
		}
	}
		
	// Verifica se foi informado um registro para excluir
	if(isset($_GET['excluir'])){
		//excluindo um grupo
		ExecutarDB("delete from usuarios where cod = ".$_GET['excluir']);
	}
		
	// Verifica se foi informado um registro para alterar
	if(isset($_POST['login']) && isset($_POST['codigo']) && isset($_POST['submit'])){
		if($_POST['submit'] == "Alterar"){
			$msgErro = AlertaUsuarioPost();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!--<meta charset="utf-8">-->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Usuários</title>

		<!-- Bootstrap -->
		<link href="bootstrap-3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<script src="bootstrap-3.3.2/js/bootstrap.min.js"></script>
	</head>
	<body>
		<h2>Usuários</h2>
		<h3><?php echo "Usuario ".$_SESSION["login"]." conectado com sucesso!"; ?></h3>
		<?php
			if($msgErro != ""){
				echo "<h4>" . $msgErro . "</h4>";
			}
		?>
		<form action="usuarios.php" method="POST">
			<?php
				echo "<table><tr><td colspan='2'>";
						
				if(isset($_GET['alterar'])){
					echo "Alterar Usuário";
				}else{
					echo "Inserir Novo Usuário";
				}
					
				echo "</td></tr>";
				
				if(isset($_GET['alterar'])){
					echo "<tr><td>Código:</td><td>".$_GET['alterar']."<input type='hidden' name='codigo' value='".$_GET['alterar']."' /></td></tr>";
				}
					
				$login = null;
				//$admin = false;
				$grupo = null;
				$nascimento = null;
				$endereco = null;
				$sexo = null;
				$email = null;
				$tel1 = null;
				$tel2 = null;
				$tel3 = null;
					
				if(isset($_GET['alterar'])){
					// realiza a conexao com o banco de dados
					$conexao = RetornaConexao(); 
					
					//buscando pelo usuário
					//$sql = "select login, admin, grupo, DATE_FORMAT(nascimento, '%d/%m/%Y') as 'nascimento', endereco, sexo, email, tel1, tel2, tel3 from usuarios where cod = ".$_GET['alterar'];
					$sql = "select login, grupo, DATE_FORMAT(nascimento, '%d/%m/%Y') as 'nascimento', endereco, sexo, email, tel1, tel2, tel3 from usuarios where cod = ".$_GET['alterar'];
							
					$result = mysqli_query($conexao, $sql, $field=0);
					if (!$result){
						die('Erro consultando para alterar registro de usuarios no Banco de Dados.');
					}else{
						if (mysqli_num_rows($result) > 0){
							// verifica o login retornado
							while($row = mysqli_fetch_assoc($result)) {
								$login = $row["login"];
								//$admin = $row["admin"];
								$grupo = $row["grupo"];
								$nascimento = $row["nascimento"];
								$endereco = $row["endereco"];
								$sexo = $row["sexo"];
								$email = $row["email"];
								$tel1 = $row["tel1"];
								$tel2 = $row["tel2"];
								$tel3 = $row["tel3"];
							}
						}
					}
					mysqli_close($conexao);
				}
					
				// Login
				echo "<tr><td>Login:</td><td><input type='text' name='login' value='".$login."'  /></td></tr>";
					
				// Senha
				echo "<tr><td>Nova Senha:</td><td><input type='password' name='senha' /></td></tr>";
					
				/* Admin
				echo "<tr><td>Admin:</td><td><input type='checkbox' name='admin' value='X' ";
				if($admin == true){
					echo " checked";
				}
				echo "/></td></tr>";*/
					
				// Grupo
				echo "<tr><td>Grupo:</td><td>";
				echo ObjetoSelectGrupos($grupo); 
				echo "</td></tr>";
					
				// Nascimento
				echo "<tr><td>Nascimento:</td><td><input type='text' name='nascimento' value='".$nascimento."'  /></td></tr>";
				
				// Endereço
				echo "<tr><td>Endereço:</td><td><textarea name='endereco' cols='25' rows='10'>". $endereco ."</textarea></td></tr>";
					
				// Sexo
				echo "<tr><td>Sexo:</td><td>";
				echo ObjetoSelectSexo($sexo); 
				echo "</td></tr>";
					
				// E-mail
				echo "<tr><td>E-mail:</td><td><input type='text' name='email' value='".$email."'  /></td></tr>";
					
				// Telefone 1
				echo "<tr><td>Telefone 1:</td><td><input type='text' name='tel1' value='".$tel1."'  /></td></tr>";
					
				// Telefone 2
				echo "<tr><td>Telefone 2:</td><td><input type='text' name='tel2' value='".$tel2."'  /></td></tr>";
					
				// Telefone 3
				echo "<tr><td>Telefone 3:</td><td><input type='text' name='tel3' value='".$tel3."'  /></td></tr>";
					
				// Rodapé
				echo "<tr><td colspan='2'>";

				if(isset($_GET['alterar'])){
					echo "<input type='submit' value='Alterar' name='submit'>";
				}else{
					echo "<input type='submit' value='Adicionar' name='submit'>";
				}
						
				echo "</td></tr></table>";
			?>
		</form>
		<h3>Usuários cadastrados</h3>
		<table>
			<tr>
				<td>Código</td>
				<td>Login</td>
				<td>Admin</td>
				<td>Grupo</td>
				<td>Nascimento</td>
				<td>Endereço</td>
				<td>Sexo</td>
				<td>E-mail</td>
				<td>Telefone 1</td>
				<td>Telefone 2</td>
				<td>Telefone 3</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?php
				// realiza a conexao com o banco de dados
				$conexao = RetornaConexao(); 
				
				//Consultando grupos cadastrados
				$sql = "SELECT u.cod, u.login, CASE u.admin WHEN 0 THEN 'Nao' ELSE 'Sim' END as 'admin', u.admin as 'vadmin', g.nome as 'grupo', DATE_FORMAT(nascimento, '%d/%m/%Y') as 'nascimento', left(u.endereco, 20) as 'endereco', CASE u.sexo WHEN 'M' THEN 'Masculino' WHEN 'F' THEN 'Feminino' ELSE '' END as 'sexo' , u.email, u.tel1, u.tel2, u.tel3 FROM usuarios u LEFT JOIN grupos g ON u.grupo = g.cod ";
					
				$result = mysqli_query($conexao, $sql, $field=0);
				if (!$result){
					die('Erro consultando registro de usuarios no Banco de Dados.');
				}else{
					$contador = 0;
						
					if (mysqli_num_rows($result) > 0){
						// avalia cada linha do resultado
						while($row = mysqli_fetch_assoc($result)) {
							$cod = $row["cod"];
							$login = $row["login"];
							$admin = $row["admin"];
							$vadmin = $row["vadmin"];
							$grupo = $row["grupo"];
							$nascimento = $row["nascimento"]; 
							$endereco = $row["endereco"]; 
							$sexo = $row["sexo"]; 
							$email = $row["email"]; 
							$tel1 = $row["tel1"]; 
							$tel2 = $row["tel2"]; 
							$tel3 = $row["tel3"]; 
							
							$contador++;
								
							echo "<tr><td>".$cod."</td>";
							echo "<td>".$login."</td>";
							echo "<td>".$admin."</td>";
							echo "<td>".$grupo."</td>";
							echo "<td>".$nascimento."</td>";
							echo "<td>".$endereco."</td>";
							echo "<td>".$sexo."</td>";
							echo "<td>".$email."</td>";
							echo "<td>".$tel1."</td>";
							echo "<td>".$tel2."</td>";
							echo "<td>".$tel3."</td>";
							if($vadmin == false){
								echo "<td><a href='usuarios.php?excluir=".$cod."'>Excluir</a></td>";
								echo "<td><a href='usuarios.php?alterar=".$cod."'>Alterar</a></td></tr>";
							}else{
								echo "<td>&nbsp;</td>";
								echo "<td>&nbsp;</td>";
							}
						}
					}
						
					echo "<tr><td colspan='13'>".$contador." linhas encontradas.</td></tr>";
				}
					
				mysqli_close($conexao);
				
			?>
			<tr>
				<td colspan="13"><a href="usuarios.php">Cliqui aqui para atualizar a lista!</a></td>
			</tr>
			<tr>
				<td colspan="13"><a href="index.php">Voltar</a></td>
			</tr>
		</table>
	</body>
</html>