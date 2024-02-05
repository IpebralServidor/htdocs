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
	if (!empty( $_FILES['arquivo']['tmp_name'])){
	   $arquivo = new DomDocument();
	   $arquivo->load( $_FILES['arquivo']['tmp_name']);
	   ///var_dump($arquivo);
	   $linhas = $arquivo->getElementsByTagName("Row");
  
	  $primeira_linha = true;  
   
  	  foreach($linhas as $linha){
	  
		  $referencia= $linha->getElementsByTagName("Data")->item(0)->nodeValue;
		//  echo "Coluna1: $referencia <br>";
   		  $valida_codlocal= $linha->getElementsByTagName("Data")->item(1);
		 /// $codlocal= $linha->getElementsByTagName("Data")->item(1)->nodeValue;		  
		  if(is_object($valida_codlocal) and  trim($valida_codlocal->nodeValue) != ""){
             $codlocal = $valida_codlocal->nodeValue;
         } else {
           echo "Falha na importação. Existem ítens com campos invalidos";
		   ?>
			<br />
			<br />
			<br />
			<table width="1000" align="center">
			  <tr>
				<td><input type="button" value="Voltar" onClick="history.go(-1)" align="middle">
				</td>
			  </tr>
			</table>
			 <?php
		   $erro = true;
		   return;



///header("Location: ./index.php");
                   } 
	   }
  if($erro == true)  {

      return;
  }
      
	  ?>

<h1><font face="Arial, Helvetica, sans-serif" color="#0000CC">Resultado da Importação</font></h1>
<br />
<table>
  <tr
			<?php  var_dump($linhas->length -1);   ?>
  <font face="Arial, Helvetica, sans-serif" color="#0000CC"> &nbsp&nbsplinhas importadas com sucesso!</font>
  </tr>
</table>
<br />
<table width="300" border="0"   bgcolor="#0000CC" bordercolor="#FFFFFF">
  <tr>
    <td width="40%" align="center"><font face="Arial, Helvetica, sans-serif"><font color="#FFFFFF">Referencia</font></td>
    <td width="60%" align="center"><font face="Arial, Helvetica, sans-serif"><font color="#FFFFFF">Cod.Local</font></td>
  </tr>
</table			
       >
<?php
	 
	  foreach($linhas as $linha){
		  if($primeira_linha == false){
		  
		  $referencia= $linha->getElementsByTagName("Data")->item(0)->nodeValue;
		//  echo "Coluna1: $referencia <br>";
   		  $valida_codlocal= $linha->getElementsByTagName("Data")->item(1);
		 /// $codlocal= $linha->getElementsByTagName("Data")->item(1)->nodeValue;		  
		  if(is_object($valida_codlocal) ){
    $codlocal = $valida_codlocal->nodeValue;
} else {
    echo "falta  codigo local";
	break;
  // throw new Exception('teste');
}
	
	//	  echo "Coluna2: $codlocal <br>";
	//	  echo "<hr>";
	
	      //  Tratamento de exceçoes
		  
	//	  if (empty($codlocal)) {
    //             throw new Exception('Codigo local não informado');
    //          }
		  
		
	 ?>
<table width="300" border="1"  bordercolor="#EAEAEA" cellspacing="0">
  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
    <td width="40%"  align="center" ><font  face="Arial, Helvetica, sans-serif" color="#0000FF"><?php echo $referencia ?></font></td>
    <td width="60%"  align="center" ><font  face="Arial, Helvetica, sans-serif" color="#0000FF"><?php echo $codlocal ?></font></td>
  </tr>
</table>
<?php
		  		//Inserir o usuário no BD
				$result_usuario = "INSERT INTO AD_LOCACAO (REFERENCIA,CODLOCAL) VALUES ('$referencia', '$codlocal')";
		 	    $resultado_usuario =  sqlsrv_query($conn, $result_usuario);  
		  }
		  
		    $primeira_linha = false;
	  }
	
	
	
	}

//Libera os recursos do SQL server
if ($stmt) {

sqlsrv_free_stmt( $stmt);  
} 

unset($erro);
unset($dados);
unset($arquivo);

?>
