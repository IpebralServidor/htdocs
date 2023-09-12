<?php
include "../conexaophp.php";

$referencia = $_POST['REFERENCIA'];


//echo $referencia;

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="css/consulta.css">
	<title>Consulta Estoque</title>
</head>
<body>

<div class="container">
	<a href="index.php"><aabr title="Voltar para Menu"><img style="width: 40px; margin-top: 10px; margin-left: 10px; position: fixed;" src="images/Seta Voltar.png" /></aabr></a>


	<div class="titulo">
		
		<h1> Consulta de Estoque </h1>

	</div>

	<div class="infoproduto">
		<div class="infoprodutodiv">
			<?php
				$tsql = "
										
					SELECT * FROM [sankhya].[AD_FNT_InfoProduto_ConsultaEstoque]('$referencia')

						 ";

				$stmt = sqlsrv_query( $conn, $tsql); 

				while( $row2 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))  
				{ $produto = $row2['CODPROD'];
				  $codreferencia = $row2['REFERENCIA'];
				  $descrprod = $row2['DESCRPROD'];
				  $qtdmaxlocal = $row2['AD_QTDMAXLOCAL'];
				  $mediavenda = $row2['MEDIA'];
				  $agrupmin = $row2['AGRUPMIN'];
				  $precovenda = $row2['PRECOVENDA'];
				}

			?>

			<span class="infoprodutotext"><b>Produto:</b> <?php if(!isset($produto)){ echo '';} else { echo $produto;} ?></span>
			<span class="infoprodutotext"><b>&nbsp;&nbsp;&nbsp;Referência:</b> <?php if(!isset($codreferencia)){ echo '';} else { echo $codreferencia;} ?></span><br>
			<span class="infoprodutotext"><b>Descrição:</b> <?php if(!isset($descrprod)){ echo '';} else { echo $descrprod;} ?></span><br>
			<!-- <span class="infoprodutotext"><b>Qtd. Máx. Local:</b> <?php if(!isset($qtdmaxlocal)){ echo '';} else { echo $qtdmaxlocal;} ?></span> -->
			<span class="infoprodutotext"><b>Méd. V.:</b> <?php if(!isset($mediavenda)){ echo '';} else { echo $mediavenda;} ?></span>
			<span class="infoprodutotext"><b>&nbsp;&nbsp;&nbsp;Agrup. Mín.:</b> <?php if(!isset($agrupmin)){ echo '';} else { echo $agrupmin;} ?></span><br>
			<span class="infoprodutotext"><b>Preço V.: </b>R$ <?php if(!isset($precovenda)){ echo '';} else { echo str_replace('.',',',$precovenda);} ?></span><br>
		</div>
	</div> <!-- Fim infoproduto -->


	<div class="foto">
			<div class="foto-d"> 
				<?php

					if($referencia!=''){
						$tsql2 = " select ISNULL(IMAGEM,(SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000))
								   from TGFPRO inner join
									    --TGFITE ON TGFITE.CODPROD = TGFPRO.CODPROD INNER JOIN
										TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD
								   where CODBARRA = '{$referencia}' ";
					} else {
						$tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000 ";
						}

					$stmt2 = sqlsrv_query( $conn, $tsql2);
					
					if($stmt2){
						$row_count = sqlsrv_num_rows( $stmt2 ); 

					
						while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
						{ //$img = $row2[0];
							//echo $img;
							//$varImg=base64_encode("'".$img."'");
							//echo "<img src='data:image/jpeg;base64,$varImg'>";

							echo '<img style="vertical-align: middle; margin: auto; max-width: 100%; max-height: 166px;" src="data:image/jpeg;base64,'.base64_encode($row2[0]).'"/>';
							//$imageData = $row2["image"];
						}
					} 

				?>
			</div>
		</div> <!-- Fim Imagem -->



		<div class="estoqueproduto">
			
			<div style=" width: 97%; height: 43%; position: absolute; overflow: auto; margin-top: 5px;">
					<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
						  <tr> 
						    <th width="20%" align="center">Emp.</th>
						    <th width="30%" align="center">Cód. Local</th>
						    <th width="25%" style="text-align: center;">Estoque</th>
						    <th width="25%" align="center">Pad./Máx.</th>
						  </tr>


						  <?php 
							$tsql2 = "  
								SELECT * FROM [sankhya].[AD_FNT_TabelaEstoque_ConsultaEstoque]('$referencia')
									 "; 

							$stmt2 = sqlsrv_query( $conn, $tsql2);  

							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))  
							{ 
						?>

							  <tr style="cursor: hand; cursor: pointer;">
							    <td width="15%" align="center"><?php echo $row2['CODEMP']; ?>&nbsp;</td>
							    <td width="30%" align="center"><?php echo $row2['CODLOCAL']; ?>&nbsp;</td>
							    <td width="25%" align="center"><?php echo $row2['ESTOQUE']; ?>&nbsp;</td>
							    <td width="30%" align="center"><?php echo $row2['PADRAO_QTDMAX']; ?></td>
							  </tr>
							 

						<?php

							//echo $checkVariosProd;
						}
						?>

					</table>
				</div>
		</div> <!-- Estoque Produto -->

</div> <!-- Fim Container -->

</body>
</html>