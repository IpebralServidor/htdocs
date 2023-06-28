<?php
	include "../conexaophp.php";
	require_once 'App/auth.php';

	$usuconf = $_SESSION["idUsuario"];
	$_SESSION["codbarraselecionado"] = 0;
//Verifica se retornou resultado

/*if ( $stmt2 ) 
{  
    //echo "A query foi executada com sucesso..<br>\n";  
	echo "";

}   
else   
{  
     //echo "Houve erro na execução da query.\n";  
     //die( print_r( sqlsrv_errors(), true));  
} */ 
?>

<html>
<head>
	<meta charset="UTF-8"/>	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lista Conferência - <?php echo $usuconf; ?></title>

	<link href="css/main.css" rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>
<body class="background-lista">

	<div id="Filtro" class="filtro">
		<div class="img-voltar">
			<a href="../menu.php">
				<aabr title="Voltar para Menu">
					<button class="btn btn-back">
						<img src="images/216446_arrow_left_icon.png">
						<p>Voltar ao menu</p>
					</button>
				</aabr>
			</a>
		</div>
		<form action="listaconferencia.php" class="form" method="post">
			<div class="form-group">
				<label for="numnota" class="form-group">Número da Nota:</label>
				<input type="text" class="form-control" name="NUMNOTA" class="text">
			</div>
			<div>
				<label for="nunota">Número Único:</label>
				<input type="text" class="form-control" name="nunota" class="text">
			</div>	
			<div class="form-group">
				<label for="status">Status:</label>
				<select name="status" class="form-control">
					<option value= "todos">Todos</option>
					<option value= "aguardandoconf">Aguardando Conferência</option>
					<option value= "emandamento">Em Andamento</option>
					<option value= "aguardandorecont">Aguardando Recontagem</option>
					<option value= "recontemandamento">Recontagem em Andamento</option>
				</select>
			</div>		
			<div class="form-group">
				<label for="parceiro">Parceiro:</label>
				<input type="text" class="form-control" name="parceiro" class="text">
			</div>	
			
			<div class="form-group">
				<input name="aplicar" class="btn btn-form"  type="submit" value="Aplicar">
			</div>

		</form>
		
		<?php 

			$tsql2 = "";

			if(isset($_POST["aplicar"])){

			$nunota =  $_POST["nunota"];
			if($nunota == ''){
				$nunota = -1;
			}

			$numnota =  $_POST["NUMNOTA"];
			if($numnota == ''){
				$numnota = -1;
			}				

			$parceiro =  $_POST["parceiro"];
			if($parceiro == ''){
				$parceiro = -1;
			}

			$status = $_POST["status"];

			$tsql2 = " SELECT * FROM [sankhya].[AD_FNT_LISTANOTAS_CONFERENCIA]($nunota, $numnota, $parceiro, '$status', $usuconf)";

			}

			$stmt2 = sqlsrv_query( $conn, $tsql2);  
			$row_count = sqlsrv_num_rows( $stmt2 ); 

		?>

	</div> <!-- Filtro -->

	<div id="ListaConferencia" class="listaconferencia">
		<p class="text-center">Lista de Conferência</p>
		<table width="4000">
		<thead>
			<tr>
				<th>Nro. Único</font></th>
				<th>Tipo Operação</font></th>
				<th>Status Conferência</font></th>
				<th>Parceiro</th>
				<th>Dt. do Movimento</th>
				<th>Nro. Nota</th>
				<th>Empresa</th>
				<th>Nome Fantasia (Empresa)</th>
				<th>Descrição (Tipo de Operação)</th>
				<th>Ordem de Carga</th>
				<th>Sequência da Carga</th>
				<th>Qtd. Volumes</th>
				<th>Cód. Conferente</th>
				<th>Nome (Conferente)</th>
			</tr>
		</thead>

		<?php
			while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
			{ $NUCONF = $row2[0];
		?>

		<tbody>
			<tr onclick="<?php echo "location.href='detalhesconferencia.php?nunota=$row2[0]&codbarra=0'" ?>">
				<td><?php echo $row2[0]; ?></td>
				<td ><?php echo $row2[1]; ?></td>
				<td ><?php echo $row2[2]; ?></td>
				<td ><?php echo $row2[3]; ?></td>
				<td ><?php echo $row2[4]; ?></td>
				<td ><?php echo $row2[6]; ?></td>
				<td ><?php echo $row2[7]; ?></td>
				<td ><?php echo $row2[8]; ?></td>
				<td ><?php echo $row2[9]; ?></td>
				<td ><?php echo $row2[10]; ?></td>
				<td ><?php echo $row2[11]; ?></td>
				<td ><?php echo $row2[12]; ?></td>
				<td ><?php echo $row2[13]; ?></td>
				<td ><?php echo $row2[14]; ?></td>
			</tr> 
		</tbody>

		
		<?php
}
?>
	</table>

	</div> <!-- ListaConferencia -->

</body>
</html>