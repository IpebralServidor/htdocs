<?php
include "../../conexaophp.php";
session_start();

$nunotadest = $_POST['nunota'];

?>

<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
	<tr>
		<th width="40%">Referência</th>
		<th width="20%">Qtd. Orig.</th>
		<th width="20%" align="center">Qtd. Dest.</th>
		<th width="20%" align="center">Diferença</th>
	</tr>
	<?php
	$tsql2 = "  
			SELECT * FROM SANKHYA.AD_FNT_PRODUTOSDIVERGENTES_SISTEMAESTOQUE($nunotadest)
			";

	$stmt2 = sqlsrv_query($conn, $tsql2);

	while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_NUMERIC)) {
		$NUCONF = $row2[0];
	?>
		<tr style="cursor: hand; cursor: pointer;">
			<td width="40%"><?php echo $row2[0]; ?>&nbsp;</td>
			<td width="20%"><?php echo $row2[1]; ?>&nbsp;</td>
			<td width="20%"><?php echo $row2[2]; ?>&nbsp;</td>
			<td width="20%" align="center"><?php echo $row2[3]; ?></td>
		</tr></a>
	<?php
	}
	?>
</table>