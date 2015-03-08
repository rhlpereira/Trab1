<?php
	function RetornaConexao(){
		$conexao = mysqli_connect('localhost','root','', 'trab1');
		if (!$conexao) { 
			throw new Exception('Erro ao conectar com o Banco de Dados : ' . mysql_error()); 
		}
		return $conexao;
	}
	
	function ExecutarDB($sql){
		$conexao = RetornaConexao(); 
		
		$result = mysqli_query($conexao, $sql, $field=0);
		if (!$result){
			throw new Exception('Erro ao executar SQL no Banco de Dados. [' . $sql . ']');
		}
			
		mysqli_close($conexao);
	}
		
	function ValidarEmail($email){
		if (!@eregi("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$", $email)) {
			return false;
		}else{
			return true;
		}
	}
	
	function ObjetoSelectGrupos($grupo){
		$retornoHtml = "<select name='grupo'><option value=''";
								
		if($grupo == null || $grupo == ""){
			$retornoHtml = $retornoHtml . " selected ";
		}
			
		$retornoHtml = $retornoHtml . ">(Nao informado!)</option>";
			
		$sql = "SELECT cod, nome from grupos";
			
		$conexao = RetornaConexao();
		$result = mysqli_query($conexao, $sql, $field=0);
		if (mysqli_num_rows($result) > 0){
			// avalia cada linha do resultado
			while($row = mysqli_fetch_assoc($result)) {
				$scod = $row["cod"];
				$snome = $row["nome"];
					
				$retornoHtml = $retornoHtml . "<option value='".$scod."'";
					
				if($grupo != ""){
					if($grupo == $scod){
						$retornoHtml = $retornoHtml . " selected ";
					}
				}									
				$retornoHtml = $retornoHtml . ">".$snome."</option>";
			}
		}
		mysqli_close($conexao);
			
		$retornoHtml = $retornoHtml . "</select></td>";
		
		return $retornoHtml;
	}
	
	function ObjetoSelectSexo($sexo){
		$retornoHtml = "<select name='sexo'><option value=''";
				
		if($sexo == null || $sexo == "" || ($sexo != "M" && $sexo != "F") ){
			$retornoHtml = $retornoHtml . " selected ";
		}
			
		$retornoHtml = $retornoHtml . ">(Nao informado!)</option>";
		
		$retornoHtml = $retornoHtml . "<option value='M'";
				
		if($sexo == "M"){
			$retornoHtml = $retornoHtml . " selected ";
		}
			
		$retornoHtml = $retornoHtml . ">Masculino</option>";
		
		$retornoHtml = $retornoHtml . "<option value='F'";
				
		if($sexo == "F"){
			$retornoHtml = $retornoHtml . " selected ";
		}
			
		$retornoHtml = $retornoHtml . ">Feminino</option>";
		$retornoHtml = $retornoHtml . "</select>";
		
		return $retornoHtml;
	}
		
	function ValidaDadosUsuarioPost($inserir){
		$retorno = "";
		if(isset($_POST['codigo']) || isset($_POST['login'])){
			$cod = null;
			if(isset($_POST['codigo'])){
				$cod = $_POST['codigo'];
			}
				
			$login = null;
			if(isset($_POST['login'])){
				$login = trim(str_replace("'", "", $_POST['login']));
			}
				
			$grupo = null;
			if(isset($_POST['grupo'])){
				$grupo = $_POST['grupo'];
				$grupo = trim($grupo);
			}
				
			$senha = null;
			if(isset($_POST['senha'])){
				if(trim($_POST['senha']) != ""){
					$senha = md5($_POST['senha']);
				}
			}
				
			$endereco = null;
			if(isset($_POST['endereco'])){
				$endereco = $_POST['endereco'];
					
				$endereco = trim(str_replace("'", "", $endereco));
			}
				
			$nascimento = null;
			$alterar_nascimento = false;
			if(isset($_POST['nascimento'])){
				$strNascimento = trim($_POST['nascimento']);
					
				if($strNascimento != ""){
					$arrNascimento = explode("/", $strNascimento);
						
					if(count($arrNascimento) != 3)
					{
						$retorno = $retorno . "Data de nascimento em formato inválido não foi alterada (dd/MM/yyyy) : " . $strNascimento;
					}else{
						if(checkdate($arrNascimento[1], $arrNascimento[0], $arrNascimento[2]) == false){
							$retorno = $retorno . "Data de nascimento em formato inválido não foi alterada (dd/MM/yyyy) : " . $strNascimento;
						}else{
							$nascimento = $arrNascimento[2] . "-" . $arrNascimento[1] . "-" . $arrNascimento[0];
								
							$alterar_nascimento = true;
						}
					}
				}
			}
				
			$email = null;
			$alterar_email = false;
			if(isset($_POST['email'])){
				$strEmail = $_POST['email'];
					
				$strEmail = trim(str_replace("'", "", $strEmail));
					
				if($strEmail != "" && ValidarEmail($strEmail) == false){
					$retorno = $retorno . "E-mail inválido não foi alterado : " . $strEmail;
				}else{
					$email = $strEmail;
					$alterar_email = true;
				}
			}
				
			$sexo = null;
			$alterar_sexo = false;
			if(isset($_POST['sexo'])){
				$strSexo = $_POST['sexo'];
					
				$strSexo = trim(str_replace("'", "", $strSexo));
					
				if($strSexo != NULL && $strSexo != "" && $strSexo != "M" && $strSexo != "F"){
					$retorno = $retorno . "Sexo inválido não foi alterado : " . $strSexo;
				}else{
					$sexo = $strSexo;
					$alterar_sexo = true;
				}
			}
				
			$tel1 = null;
			if(isset($_POST['tel1'])){
				$tel1 = $_POST['tel1'];
					
				$tel1 = trim(str_replace("'", "", $tel1));
			}
				
			$tel2 = null;
			if(isset($_POST['tel2'])){
				$tel2 = $_POST['tel2'];
					
				$tel2 = trim(str_replace("'", "", $tel2));
			}
				
			$tel3 = null;
			if(isset($_POST['tel3'])){
				$tel3 = $_POST['tel3'];
					
				$tel3 = trim(str_replace("'", "", $tel3));
			}
				
			//alterando um usuário
			if($login == null || $login == ""){
				$retorno = $retorno . "Login não informado!";
				
				if($inserir == false){
					if($cod == null || $cod == ""){
						$retorno = $retorno . "Código não informado!";
					}
				}
			}else{
				$retorno  = "*OK*";
				
				$_SESSION["usu_cod"] = $cod;
				$_SESSION["usu_login"] = $login;
				$_SESSION["usu_grupo"] = $grupo;
				$_SESSION["usu_senha"] = $senha;
				$_SESSION["usu_endereco"] = $endereco;
				$_SESSION["usu_alterar_nascimento"] = $alterar_nascimento;
				$_SESSION["usu_nascimento"] = $nascimento;
				$_SESSION["usu_alterar_email"] = $alterar_email;
				$_SESSION["usu_email"] = $email;
				$_SESSION["usu_alterar_sexo"] = $alterar_sexo;
				$_SESSION["usu_sexo"] = $sexo;
				$_SESSION["usu_tel1"] = $tel1;
				$_SESSION["usu_tel2"] = $tel2;
				$_SESSION["usu_tel3"] = $tel3;
			}
		}
			
		return $retorno;
	}
		
	function AlertaUsuarioPost(){
		$msg = ValidaDadosUsuarioPost(false);
			
		if($msg == "*OK*"){
			AlertaUsuarioSql();
			return "";
		}else{
			return $msg;
		}
	}
		
	function AlertaUsuarioSql(){
		$cod = $_SESSION["usu_cod"];
		$login = $_SESSION["usu_login"];
		$grupo = $_SESSION["usu_grupo"];
		$senha = $_SESSION["usu_senha"];
		$endereco = $_SESSION["usu_endereco"];
		$alterar_nascimento = $_SESSION["usu_alterar_nascimento"];
		$nascimento = $_SESSION["usu_nascimento"];
		$alterar_email = $_SESSION["usu_alterar_email"];
		$email = $_SESSION["usu_email"];
		$alterar_sexo = $_SESSION["usu_alterar_sexo"];
		$sexo = $_SESSION["usu_sexo"];
		$tel1 = $_SESSION["usu_tel1"];
		$tel2 = $_SESSION["usu_tel2"];
		$tel3 = $_SESSION["usu_tel3"];
			
		$sql = "update usuarios set login = '".$login."'";
			
		if($grupo != null || $grupo != ""){
			$sql = $sql.", grupo=".$grupo;
		}else{
			$sql = $sql.", grupo=null";
		}
			
		if($senha != ""){
			$sql = $sql.", senha='".$senha."'";
		}
			
		if($endereco != null || $endereco != ""){
			$sql = $sql.", endereco='".$endereco."'";
		}else{
			$sql = $sql.", endereco=null";
		}
		
		if($alterar_nascimento = true){
			if($nascimento != null || $nascimento != ""){
				$sql = $sql.", nascimento='".$nascimento."'";
			}else{
				$sql = $sql.", nascimento=null";
			}
		}
			
		if($alterar_email = true){
			if($email != null || $email != ""){
				$sql = $sql.", email='".$email."'";
			}else{
				$sql = $sql.", email=null";
			}
		}
			
		if($alterar_sexo = true){
			if($sexo != null || $sexo != ""){
				$sql = $sql.", sexo='".$sexo."'";
			}else{
				$sql = $sql.", sexo=null";
			}
		}
			
		if($tel1 != null || $tel1 != ""){
			$sql = $sql.", tel1='".$tel1."'";
		}else{
			$sql = $sql.", tel1=null";
		}
			
		if($tel2 != null || $tel2 != ""){
			$sql = $sql.", tel2='".$tel2."'";
		}else{
			$sql = $sql.", tel2=null";
		}
			
		if($tel3 != null || $tel3 != ""){
			$sql = $sql.", tel3='".$tel3."'";
		}else{
			$sql = $sql.", tel3=null";
		}
			
		$sql = $sql." where cod = ".$cod;
			
		ExecutarDB($sql);
	}
		
	function InsereUsuarioPost(){
		$msg = ValidaDadosUsuarioPost(true);
			
		if($msg == "*OK*"){
			InsereUsuarioSql();
			return "";
		}else{
			return $msg;
		}
	}
		
	function InsereUsuarioSql(){
		$login = $_SESSION["usu_login"];
		$grupo = $_SESSION["usu_grupo"];
		$senha = $_SESSION["usu_senha"];
		$endereco = $_SESSION["usu_endereco"];
		$alterar_nascimento = $_SESSION["usu_alterar_nascimento"];
		$nascimento = $_SESSION["usu_nascimento"];
		$alterar_email = $_SESSION["usu_alterar_email"];
		$email = $_SESSION["usu_email"];
		$alterar_sexo = $_SESSION["usu_alterar_sexo"];
		$sexo = $_SESSION["usu_sexo"];
		$tel1 = $_SESSION["usu_tel1"];
		$tel2 = $_SESSION["usu_tel2"];
		$tel3 = $_SESSION["usu_tel3"];
			
		$sql = "INSERT INTO usuarios(login, senha, grupo, admin, nascimento, endereco, email, sexo, tel1, tel2, tel3) VALUES ('" .$login."', ";
			
		if($senha != ""){
			$sql = $sql."'".$senha."', ";
		}else{
		$sql = $sql."NULL, ";
		}
			
		if($grupo != null || $grupo != ""){
			$sql = $sql.$grupo.", ";
		}else{
			$sql = $sql."null, ";
		}
			
		$sql = $sql."0, ";
		
		if($alterar_nascimento = true){
			if($nascimento != null || $nascimento != ""){
				$sql = $sql."'".$nascimento."', ";
			}else{
				$sql = $sql."null, ";
			}
		}else{
			$sql = $sql."null, ";
		}
			
		if($endereco != null || $endereco != ""){
			$sql = $sql."'".$endereco."', ";
		}else{
			$sql = $sql."null, ";
		}
			
		if($alterar_email = true){
			if($email != null || $email != ""){
				$sql = $sql."'".$email."', ";
			}else{
				$sql = $sql."null, ";
			}
		}else{
			$sql = $sql."null, ";
		}
			
		if($alterar_sexo = true){
			if($sexo != null || $sexo != ""){
				$sql = $sql."'".$sexo."', ";
			}else{
				$sql = $sql."null, ";
			}
		}else{
			$sql = $sql."null, ";
		}	
			
		if($tel1 != null || $tel1 != ""){
			$sql = $sql."'".$tel1."', ";
		}else{
			$sql = $sql."null, ";
		}
			
		if($tel2 != null || $tel2 != ""){
			$sql = $sql."'".$tel2."', ";
		}else{
			$sql = $sql."null, ";
		}
			
		if($tel3 != null || $tel3 != ""){
			$sql = $sql."'".$tel3."')";
		}else{
			$sql = $sql."null)";
		}
			
		ExecutarDB($sql);
	}
?>