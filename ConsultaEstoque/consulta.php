<?php
include "../conexaophp.php";
	$referencia = $_POST['REFERENCIA'];
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="css/consulta.css">
	<link rel="stylesheet" type="text/css" href="css/main.css?v=<?= time() ?>">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<title>Consulta Estoque</title>
</head>
<body>
	<div class="img-voltar">
		<a href="./">
			<img src="images/216446_arrow_left_icon.png" />
		</a>
	</div>
	<div class="container">
		<div class="header-body">
			<?php
				$tsql = "SELECT * FROM [sankhya].[AD_FNT_InfoProduto_ConsultaEstoque]('$referencia')";

				$stmt = sqlsrv_query( $conn, $tsql); 
				$row2 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
				
				$produto = $row2['CODPROD'];
				$codreferencia = $row2['REFERENCIA'];
				$descrprod = $row2['DESCRPROD'];
				$qtdmaxlocal = $row2['AD_QTDMAXLOCAL'];
				$mediavenda = $row2['MEDIA'];
				$agrupmin = $row2['AGRUPMIN'];
				$precovenda = $row2['PRECOVENDA'];
				$fornecedores = $row2['OBSETIQUETA'];

			?>

			<div class="header-body-left">
				<div class="infos">
					<div class="informacoes">
						<h6>Produto: <?php echo $produto;?></h6>
						<h6>Referência: <?php echo $codreferencia;?></h6>
						<h6>Preço venda: R$<?php echo str_replace('.',',',$precovenda);?></h6>									
						<h6>Ref. Fornecedores: <?php echo str_replace('.',',',$fornecedores);?></h6>
					</div>
					<div class="infos-2">
						<h6>Media venda: <?php echo $mediavenda;?></h6>
						<h6>Agrupamento mínimo: <?php echo $agrupmin;?></h6>
						<h6>Descrição: <?php echo $descrprod;?></h6>
						<h6>Local padrão: 
							<?php
								$tsql = "	SELECT DISTINCT CODLOCALPAD 
											FROM TGFPEM WHERE CODPROD = (SELECT CODPROD FROM TGFBAR WHERE CODBARRA = '$referencia') 
											  AND CODEMP IN (1,7)";
								$stmt = sqlsrv_query( $conn, $tsql); 
								while($row2 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)){
									echo $row2["CODLOCALPAD"] . " | ";
								}
							?>	
						</h6>
					</div>
				</div>
			</div>
		</div>


		<div class="image d-flex justify-content-center"> 
			<?php
				if($referencia!=''){
					$tsql2 = " select ISNULL(IMAGEM,(SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000))
								from TGFPRO inner join
									--TGFITE ON TGFITE.CODPROD = TGFPRO.CODPROD INNER JOIN
									TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD
								where CODBARRA = '{$referencia}' ";
				} else {
					$tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000";
				}
				$stmt2 = sqlsrv_query( $conn, $tsql2);
				$row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC);
				
				echo '<img style="vertical-align: middle; margin: auto; max-width: 100%; max-height: 166px;" src="data:image/jpeg;base64,'.base64_encode($row2[0]).'"/>';
			?>
		</div>

		<div class="overflow">
			<table class="table">
				<tr class="position-sticky"> 
					<th class="border-top-left-radius">Emp.</th>
					<th>Cód. Loc.</th>
					<th>Estoque</th>
					<th>Reserv.</th>
					<th class="border-top-right-radius">Pad./Máx.</th>
				</tr>

				<?php 
					$tsql2 = "SELECT * FROM [sankhya].[AD_FNT_TabelaEstoque_ConsultaEstoque]('$referencia')"; 

					$stmt2 = sqlsrv_query( $conn, $tsql2);  

					while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC)){ ?>
						<tr>
							<td><?php echo $row2['CODEMP']; ?></td>
							<td style="width: 50%"><?php echo $row2['CODLOCAL']; ?></td>
							<td><?php echo $row2['ESTOQUE']; ?></td>
							<td><?php echo $row2['RESERVADO']; ?></td>
							<td><?php echo $row2['PADRAO_QTDMAX']; ?></td>
						</tr>
				<?php } ?>
			</table>
		</div>
	</div>

</body>
</html>