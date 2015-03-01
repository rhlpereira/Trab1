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
		
	// Verifica se foi informado um nome para inserir
	if(isset($_POST['nome']) && isset($_POST['submit'])){
		if($_POST['submit'] == "Adicionar"){
			// echo "<br>adicionando<br>";
			// realiza a conexao com o banco de dados
			$conexao = mysqli_connect('localhost','root','', 'Lab5'); 
			if (!$conexao) { 
				die('Erro ao conectar com o Banco de Dados para inserir : ' . mysql_error()); 
			}else{
				if(trim($_POST['nome']) != ""){
					//inserindo novo grupo
					$sql = "insert into grupos (nome) values ('".$_POST['nome']."')";
						
					$result = mysqli_query($conexao, $sql, $field=0);
					if (!$result){
						die('Erro inserindo registro de grupos no Banco de Dados.');
					}
				}
					
				mysqli_close($conexao);
			}
		}
	}
		
	// Verifica se foi informado um registro para excluir
	if(isset($_GET['excluir'])){
		// realiza a conexao com o banco de dados
		$conexao = mysqli_connect('localhost','root','', 'Lab5'); 
		if (!$conexao) { 
			die('Erro ao conectar com o Banco de Dados para excluir : ' . mysql_error()); 
		}else{
			//excluindo um grupo
			$sql = "delete from grupos where cod = ".$_GET['excluir'];
				
			$result = mysqli_query($conexao, $sql, $field=0);
			if (!$result){
				die('Erro excluindo registro de grupos no Banco de Dados.');
			}
				
			mysqli_close($conexao);
		}
	}
		
	// Verifica se foi informado um registro para alterar
	if(isset($_POST['nome']) && isset($_POST['codigo']) && isset($_POST['submit'])){
		if($_POST['submit'] == "Alterar"){
			//echo "<br>alterando<br>";
			// realiza a conexao com o banco de dados
			$conexao = mysqli_connect('localhost','root','', 'Lab5'); 
			if (!$conexao) { 
				die('Erro ao conectar com o Banco de Dados para alterar : ' . mysql_error()); 
			}else{
				//alterando um grupo
				if(trim($_POST['nome']) != ""){
					$sql = "update grupos set nome = '".$_POST['nome']."' where cod = ".$_POST['codigo'];
						
					$result = mysqli_query($conexao, $sql, $field=0);
					if (!$result){
						die('Erro alterando registro de grupos no Banco de Dados.');
					}
				}	
				mysqli_close($conexao);
			}
		}
	}
	
?>
<html>
	<head>
		<title>Grupos de Estudo</title>
	</head>
	<body>
		<h2>Grupos de Estudo</h2>
		<h3><?php echo "Usuario ".$_SESSION["login"]." conectado com sucesso!"; ?></h3>
		<form action="grupos.php" method="POST">
			<table>
				<tr>
					<td colspan="2">
						<?php
							if(isset($_GET['alterar'])){
								echo "Alterar grupo de estudo";
							}else{
								echo "Inserir novo grupo de estudo";
							}
						?>
					</td>
				</tr>
				<?php
					if(isset($_GET['alterar'])){
						echo "<tr><td>Código:</td><td>".$_GET['alterar']."<input type='hidden' name='codigo' value='".$_GET['alterar']."' /></td></tr>";
					}
				?>
				<tr>
					<td>Nome:</td>
					<td>
						<?php
							if(isset($_GET['alterar'])){
								$nome = "";
									
								// realiza a conexao com o banco de dados
								$conexao = mysqli_connect('localhost','root','', 'Lab5'); 
								if (!$conexao) { 
									die('Erro ao conectar com o Banco de Dados para alterar : ' . mysql_error()); 
								}else{
									//buscando pelo grupo
									$sql = "select nome from grupos where cod = ".$_GET['alterar'];
										
									$result = mysqli_query($conexao, $sql, $field=0);
									if (!$result){
										die('Erro consultando para alterar registro de grupos no Banco de Dados.');
									}else{
										if (mysqli_num_rows($result) > 0){
											// verifica o nome retornado
											while($row = mysqli_fetch_assoc($result)) {
												$nome = $row["nome"];
											}
										}
									}
									mysqli_close($conexao);
								}
									
								echo "<input type='text' name='nome' value='".$nome."'  />";
							}else{
								echo "<input type='text' name='nome'/>";
							}
						?>
					</td>
				<tr>
				<tr>
					<td colspan="2">
						<?php
							if(isset($_GET['alterar'])){
								echo "<input type='submit' value='Alterar' name='submit'>";
							}else{
								echo "<input type='submit' value='Adicionar' name='submit'>";
							}
						?>
					</td>
				</tr>
			</table>
		</form>
		<h3>Grupos cadastrados</h3>
		<table>
			<tr>
				<td>Código</td>
				<td>Nome</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?php
				// realiza a conexao com o banco de dados
				$conexao = mysqli_connect('localhost','root','', 'Lab5'); 
				if (!$conexao) { 
					die('Erro ao conectar com o Banco de Dados para consultar : ' . mysql_error()); 
				}else{
					//inserindo novo grupo
					$sql = "select cod, nome from grupos";
						
					$result = mysqli_query($conexao, $sql, $field=0);
					if (!$result){
						die('Erro consultando registro de grupos no Banco de Dados.');
					}else{
						$contador = 0;
							
						if (mysqli_num_rows($result) > 0){
							// avalia cada linha do resultado
							while($row = mysqli_fetch_assoc($result)) {
								$cod = $row["cod"];
								$nome = $row["nome"];
								$contador++;
									
								echo "<tr><td>".$cod."</td><td>".$nome."</td><td><a href='grupos.php?excluir=".$cod."'>Excluir</a></td><td><a href='grupos.php?alterar=".$cod."'>Alterar</a></td></tr>";
							}
						}
							
						echo "<tr><td colspan='4'>".$contador." linhas encontradas.</td></tr>";
					}
						
					mysqli_close($conexao);
				}
			?>
			<tr>
				<td colspan="3"><a href="grupos.php">Cliqui aqui para atualizar a lista!</a></td>
			</tr>
			<tr>
				<td colspan="3"><a href="index.php">Voltar</a></td>
			</tr>
		</table>
	</body>
</html>