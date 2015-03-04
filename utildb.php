<?php
	function RetornaConexao(){
		$conexao = mysqli_connect('localhost','root','', 'Lab5');
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
?>