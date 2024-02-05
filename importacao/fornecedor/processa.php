<?php

$erro = false;
	 
$stmt ="";

//Configuracoes do servidor SQL Server 
$serverName = "SERVER-BD"; 
$uid = "sankhya";   
$pwd = "tecsis";  
$databaseName = "SANKHYA_PROD"; 

$connectionInfo = array( "UID"=>$uid,                            
                         "PWD"=>$pwd,                            
                         "Database"=>$databaseName); 

/* Conexao com SQL Server usando autenticacao. */  
$conn = sqlsrv_connect( $serverName, $connectionInfo);  

//Verifica se a conexao foi estabelecida com sucesso
if( $conn ) {
     echo "";
	//   echo "Conexão estabelecida.<br />";
}else{
     echo "Não foi possível conectar ao SQL Server.<br />";
     die( print_r( sqlsrv_errors(), true));
}

   $dados = $_FILES['arquivo'];
    //var_dump($dados);

// zera tabela
$result_usuario = "DELETE FROM AD_MIGRACAOPRECO";
$resultado_usuario =  sqlsrv_query($conn, $result_usuario);  
      
	  ?>

<h1><font face="Arial, Helvetica, sans-serif" color="#0000CC">Resultado da Importação</font></h1>
<br />

<br />
<!-- 
<table width="900" border="0"   bgcolor="#0000CC" bordercolor="#FFFFFF">
  <tr>
    <td width="20%" align="center"><font face="Arial, Helvetica, sans-serif"><font color="#FFFFFF">CODPROPARC</font></td>
    <td width="50%" align="center"><font face="Arial, Helvetica, sans-serif"><font color="#FFFFFF">DESCPROPARC</font></td>
    <td width="15%" align="center"><font face="Arial, Helvetica, sans-serif"><font color="#FFFFFF">PRECO</font></td>	
    <td width="15%" align="center"><font face="Arial, Helvetica, sans-serif"><font color="#FFFFFF">CODPARC</font></td>	
  </tr>
</table	>   -->
<?php
	 if (!empty( $_FILES['arquivo']['tmp_name'])){
	   $arquivo = new DomDocument();
	   $arquivo->load( $_FILES['arquivo']['tmp_name']);
	   ///var_dump($arquivo);
	   $linhas = $arquivo->getElementsByTagName("Row");
  
	  $primeira_linha = true;  
	  foreach($linhas as $linha){
		  if($primeira_linha == false){
		  
		  $codproparc= $linha->getElementsByTagName("Data")->item(0)->nodeValue;
          $descrproparc= $linha->getElementsByTagName("Data")->item(1)->nodeValue;		  
          $preco= $linha->getElementsByTagName("Data")->item(2)->nodeValue;				  
          $codparc= $linha->getElementsByTagName("Data")->item(3)->nodeValue;	

	
		  
		
	 ?>
	 <!--
<table width="900" border="1"  bordercolor="#EAEAEA" cellspacing="0">
  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
    <td width="15%"  align="center" ><font  face="Arial, Helvetica, sans-serif" color="#0000FF"><?php echo $codproparc ?></font></td>
   <td width="55%"  align="center" ><font  face="Arial, Helvetica, sans-serif" color="#0000FF"><?php echo $descrproparc ?></font></td>
    <td width="15%"  align="center" ><font  face="Arial, Helvetica, sans-serif" color="#0000FF"><?php echo $preco ?></font></td>
    <td width="15%"  align="center" ><font  face="Arial, Helvetica, sans-serif" color="#0000FF"><?php echo $codparc ?></font></td>		
  </tr>
</table> -->
<?php
		  		//Inserir o usuário no BD
				$result_usuario = "INSERT INTO AD_MIGRACAOPRECO (CODPROPARC,DESCPROPARC,PRECO,CODPARC) VALUES ('$codproparc', '$descrproparc','$preco','$codparc')";
		 	   $resultado_usuario =  sqlsrv_query($conn, $result_usuario);  
			   
		  }
		  
		    $primeira_linha = false;
	  }
	
	
	}

?>
<br />
<table>
  <tr
			<?php  var_dump($linhas->length -1);   ?>
  <font face="Arial, Helvetica, sans-serif" color="#0000CC"> &nbsp&nbsplinhas importadas com sucesso!</font>
  </tr>
</table>
<br />



<?php
//Libera os recursos do SQL server
if ($stmt) {

sqlsrv_free_stmt( $stmt);  
} 

unset($erro);
unset($dados);
unset($arquivo);

?>
