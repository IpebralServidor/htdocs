<?php
    $dados = $_FILES['arquivo'];
    //var_dump($dados);
	if (!empty( $_FILES['arquivo']['tmp_name'])){
	   $arquivo = new DomDocument();
	   $arquivo->load( $_FILES['arquivo']['tmp_name']);
	   ///var_dump($arquivo);
	   $linhas = $arquivo->getElementsByTagName("Row");
	  // var_dump($linhas);
	  
	  $primeira_linha = true;
	  ?>
		    <h1><font face="Arial, Helvetica, sans-serif" color="#0000CC">Resultado da Importação</font></h1>
			<br />
			<br />

		   <table width="300" border="0"   bgcolor="#0000CC" bordercolor="#FFFFFF">
			<tr>
             <td width="40%" align="center"><font face="Arial, Helvetica, sans-serif"><font color="#FFFFFF">Referencia</font></td>
            <td width="60%" align="center"><font face="Arial, Helvetica, sans-serif"><font color="#FFFFFF">Cod.Local</font></td>
			</tr>	
          </table			
       ><?php
	 
	  foreach($linhas as $linha){
		  if($primeira_linha == false){
		  
		  $referencia= $linha->getElementsByTagName("Data")->item(0)->nodeValue;
		//  echo "Coluna1: $referencia <br>";

		  $codlocal= $linha->getElementsByTagName("Data")->item(1)->nodeValue;
	//	  echo "Coluna2: $codlocal <br>";
	//	  echo "<hr>";
	 ?>
		   <table width="300" border="1"  bordercolor="#EAEAEA" cellspacing="0">
			<tr><font size="-1" face="Arial, Helvetica, sans-serif" >
             <td width="40%"  align="center" ><font  face="Arial, Helvetica, sans-serif" color="#0000FF"><?php echo $referencia ?></font></td>
			 <td width="60%"  align="center" ><font  face="Arial, Helvetica, sans-serif" color="#0000FF"><?php echo $codlocal ?></font></td>
			</tr>
	
		  </table>
	
		  <?php
		  		  
		  }
		  
		    $primeira_linha = false;
	  }
	
	
	
	}
//?>      <br />
//	    <br />
//	    <br />
//		<label>Para gravar estes dados no banco</label>
//	    <a href="gravabanco.php">Clique Aqui</a>

//<?php
?>
