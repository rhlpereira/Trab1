<?php
	// Verifica e valida o usuário logado
	include 'verificalogin.php';
	VerificaLogin();
	
	// Funções para banco de dados
	include 'utildb.php';
	
	// Verifica se foi informado um nome para inserir
	if(isset($_POST['nome']) && isset($_POST['submit'])){
		if($_POST['submit'] == "Adicionar"){
			if(trim($_POST['nome']) != ""){
				//inserindo novo grupo
				ExecutarDB("insert into tipos (nome) values ('".$_POST['nome']."')");
			}
		}
	}
		
	// Verifica se foi informado um registro para excluir
	if(isset($_GET['excluir'])){
		//excluindo um grupo
		ExecutarDB("delete from tipos where cod = ".$_GET['excluir']);
	}
		
	// Verifica se foi informado um registro para alterar
	if(isset($_POST['nome']) && isset($_POST['codigo']) && isset($_POST['submit'])){
		if($_POST['submit'] == "Alterar"){
			//alterando um grupo
			if(trim($_POST['nome']) != ""){
				ExecutarDB("update tipos set nome = '".$_POST['nome']."' where cod = ".$_POST['codigo']);
			}
		}
	}
?>
<html>
	<head>
		<title>Tipos de Grupos de Estudo</title>
	</head>
	<body>
		<h2>Tipos de Grupos de Estudo</h2>
		<h3><?php echo "Usuario ".$_SESSION["login"]." conectado com sucesso!"; ?></h3>
		<form action="tipos.php" method="POST">
			<table>
				<tr>
					<td colspan="2">
						<?php
							if(isset($_GET['alterar'])){
								echo "Alterar tipo";
							}else{
								echo "Inserir novo tipo";
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
								$conexao = RetornaConexao(); 
								
								//buscando pelo grupo
								$sql = "select nome from tipos where cod = ".$_GET['alterar'];
										
								$result = mysqli_query($conexao, $sql, $field=0);
								if (!$result){
									die('Erro consultando para alterar registro de tipos no Banco de Dados.');
								}else{
									if (mysqli_num_rows($result) > 0){
										// verifica o nome retornado
										while($row = mysqli_fetch_assoc($result)) {
											$nome = $row["nome"];
										}
									}
								}
								mysqli_close($conexao);
									
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
		<h3>Tipos cadastrados</h3>
		<table>
			<tr>
				<td>Código</td>
				<td>Nome</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?php
				// realiza a conexao com o banco de dados
				$conexao = RetornaConexao(); 
				
				//Consultando grupos cadastrados
				$sql = "select cod, nome from tipos";
					
				$result = mysqli_query($conexao, $sql, $field=0);
				if (!$result){
					die('Erro consultando registro de tipos no Banco de Dados.');
				}else{
					$contador = 0;
						
					if (mysqli_num_rows($result) > 0){
						// avalia cada linha do resultado
						while($row = mysqli_fetch_assoc($result)) {
							$cod = $row["cod"];
							$nome = $row["nome"];
							$contador++;
								
							echo "<tr><td>".$cod."</td><td>".$nome."</td><td><a href='tipos.php?excluir=".$cod."'>Excluir</a></td><td><a href='tipos.php?alterar=".$cod."'>Alterar</a></td></tr>";
						}
					}
						
					echo "<tr><td colspan='4'>".$contador." linhas encontradas.</td></tr>";
				}
					
				mysqli_close($conexao);
				
			?>
			<tr>
				<td colspan="3"><a href="tipos.php">Cliqui aqui para atualizar a lista!</a></td>
			</tr>
			<tr>
				<td colspan="3"><a href="index.php">Voltar</a></td>
			</tr>
		</table>
	</body>
</html>