<?php header('Content-Type: text/html; iso-8859-1');?>
<?php
include "../conexaophp.php";
//fim da conexão com o banco de dados

$codigodebarra = $_POST['codigodebarra'];
$nunota = $_POST['nunota'];

 
//$qtd = sqlsrv_num_rows($query);
?>

<section class="produtosconferencia">

			<table id="table">
			  <tr> 
			    <th>Produto</th>
			    <th>Descrição do Produto</th>
			    <th>UN</th>
			    <th>Controle</th>
			    <th>Ref. do Forn.</th>
			    <th>Código de Barras</th>


			  </tr>



			<?php 
				$tsql2 = "  DECLARE @CODEMP INT 
							DECLARE @CODPARC INT 

							SELECT @CODEMP = CODEMP,
								   @CODPARC = CODPARC
							FROM TGFCAB 
							WHERE NUNOTA = {$nunota}

							SELECT TOP 1 TGFBAR.CODPROD, 
								   DESCRPROD,
								   TGFPRO.CODVOL,
								   TGFEST.CONTROLE,
								   ISNULL(CODPROPARC,'') AS CODPROPARC,
								   TGFBAR.CODBARRA
							FROM TGFPRO INNER JOIN
								 TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD INNER JOIN
								 TGFEST ON TGFEST.CODPROD = TGFPRO.CODPROD
									   AND TGFEST.CODPARC = 0
									   AND TGFEST.CODEMP = @CODEMP LEFT JOIN
								 TGFPAP ON TGFPAP.CODPROD = TGFPRO.CODPROD 
									   AND TGFPAP.CODPARC = @CODPARC
							WHERE TGFBAR.CODBARRA = '{$codigodebarra}'
							"; 

				$stmt2 = sqlsrv_query( $conn, $tsql2);  

				while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
				{ $NUCONF = $row2[0];
			?>

				  <tr style="cursor: hand; cursor: pointer;">
				    <td width="10.6%" ><?php echo $row2[0]; ?>&nbsp;</td>
				    <td width="36.6%"><?php echo $row2[1]; ?>&nbsp;</td>
				    <td width="10.6%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
				    <td width="12.6%" align="center"><?php echo $row2[3]; ?></td>
				    <td width="12.6%" align="center"><?php echo $row2[4]; ?></td>
				    <td width="16.6%" align="center"><?php echo $row2[5]; ?></td>
				  </tr>
				 

			<?php
			}
			?>
		</table>

</section>