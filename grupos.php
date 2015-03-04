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
				if(isset($_POST['tipo'])){
					$tipo = $_POST['tipo'];
						
					if($tipo == 0){
						$tipo = null;
					}
				}else{
					$tipo = null;
				}
					
				//inserindo novo grupo
				$sql = "insert into grupos (nome, tipo) values ('".$_POST['nome']."', ";
				if($tipo == null){
					$sql = $sql . "null";
				}else{
					$sql = $sql . $tipo;
				}
				$sql = $sql . ")";
					
				//echo $sql;
				ExecutarDB($sql);
			}
		}
	}
		
	// Verifica se foi informado um registro para excluir
	if(isset($_GET['excluir'])){
		ExecutarDB("delete from grupos where cod = ".$_GET['excluir']);
	}
		
	// Verifica se foi informado um registro para alterar
	if(isset($_POST['nome']) && isset($_POST['codigo']) && isset($_POST['submit'])){
		if($_POST['submit'] == "Alterar"){
			//alterando um grupo
			if(trim($_POST['nome']) != ""){
				if(isset($_POST['tipo'])){
					$tipo = $_POST['tipo'];
						
					if($tipo == 0){
						$tipo = null;
					}
				}else{
					$tipo = null;
				}
					
				$sql = "update grupos set nome = '".$_POST['nome']."', tipo = ";
				if($tipo == null){
					$sql = $sql . "null";
				}else{
					$sql = $sql . $tipo;
				}
				$sql = $sql . " where cod = ".$_POST['codigo'];
					
				ExecutarDB($sql);
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
							$tipo = null;
								
							if(isset($_GET['alterar'])){
								$nome = "";
									
								// realiza a conexao com o banco de dados
								$conexao = RetornaConexao(); 
								
								//buscando pelo grupo
								$sql = "select * from grupos where cod = ".$_GET['alterar'];
									
								$result = mysqli_query($conexao, $sql, $field=0);
								if (!$result){
									die('Erro consultando para alterar registro de grupos no Banco de Dados.');
								}else{
									if (mysqli_num_rows($result) > 0){
										// verifica o nome retornado
										while($row = mysqli_fetch_assoc($result)) {
											$nome = $row["nome"];
											$tipo = $row["tipo"];
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
				</tr>
				<tr>
					<td>Tipo:</td>
					<td>
						<select name='tipo'>
							<?php
								echo "<option value='0'";
									
								if($tipo == null){
									echo " selected ";
								}									
								echo ">Nenhum</option>";
											
								// realiza a conexao com o banco de dados
								$conexao = RetornaConexao(); 
								
								//buscando pelos tipos
								$sql = "select * from tipos";
									
								$result = mysqli_query($conexao, $sql, $field=0);
								if (!$result){
									die('Erro consultando tipos no Banco de Dados.');
								}else{
									if (mysqli_num_rows($result) > 0){
										// verifica o nome retornado
										while($row = mysqli_fetch_assoc($result)) {
											$tipodsc = $row["nome"];
											$tipocod = $row["cod"];
												
											echo "<option value='".$tipocod."'";
												
											if($tipo != null){
												if($tipo == $tipocod){
													echo " selected ";
												}
											}									
											echo ">".$tipodsc."</option>";
										}
									}
								}
								mysqli_close($conexao);
							?>
						</select>
					</td>
				</tr>
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
				<td>Tipo</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?php
				// realiza a conexao com o banco de dados
				$conexao = RetornaConexao(); 
				
				//inserindo novo grupo
				$sql = "select t1.cod, t1.nome, t1.tipo, t2.nome as tipodsc from grupos t1 left join tipos t2 on t2.cod = t1.tipo ";
					
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
							$tipodsc = $row["tipodsc"];
							$contador++;
								
							echo "<tr><td>".$cod."</td><td>".$nome."</td><td>".$tipodsc."</td><td><a href='grupos.php?excluir=".$cod."'>Excluir</a></td><td><a href='grupos.php?alterar=".$cod."'>Alterar</a></td></tr>";
						}
					}
						
					echo "<tr><td colspan='4'>".$contador." linhas encontradas.</td></tr>";
				}
						
				mysqli_close($conexao);
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