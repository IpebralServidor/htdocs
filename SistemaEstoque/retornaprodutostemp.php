<?php  
include "../conexaophp.php";
session_start();

$nunotadest = $_POST['nunota'];
$usuconf = $_SESSION["idUsuario"];

?>		

<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
	  <tr>
	  	<th width="5%" ></th> 
	    <th width="12.5%" ></th>
	    <th width="22.5%" ></th>
	    <th width="35%" align="center">Referência</th>
	    <th width="25%" align="center">Qtd. Neg.</th>
	  </tr>


	  <?php 
		$tsql2 = "  
			select ROW_NUMBER() OVER(ORDER BY SEQUENCIA),
				   NUNOTA,
				   SEQUENCIA,
				   REFERENCIA,
				   QTDNEG,
				   CODUSU
			from TEMP_PRODUTOS_COLETOR
			where CODUSU = $usuconf
			  AND NUNOTA = $nunotadest
					"; 

		$stmt2 = sqlsrv_query( $conn, $tsql2);  

		while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
		{ $NUCONF = $row2[0];
	?>

		  <tr style="cursor: hand; cursor: pointer;">
		   	<td width="5%"><?php echo $row2[0]; ?>&nbsp;</td> 
		    <td width="12.5%">
		    	<button class="btnexcluir" onclick="delete_confirm(<?php echo $nunotadest ?>, <?php echo $row2[2]; ?>, 'temp', <?php echo $row2[5]; ?>)" style="margin-top= 2px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-x" viewBox="0 0 16 16">
			            <path d="M4.293 4.293a1 1 0 0 1 1.414 0L8 6.586l2.293-2.293a1 1 0 0 1 1.414 1.414L9.414 8l2.293 2.293a1 1 0 0 1-1.414 1.414L8 9.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L6.586 8 4.293 5.707a1 1 0 0 1 0-1.414z"/>
			        </svg>
		    	</button>&nbsp;
		    </td>
		    <td width="22.5%">
		    	<button class="btneditar" data-toggle="modal" data-target="#editarQuantidade">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                    </svg>
                </button>
		    </td>
		    <td width="35%"><?php echo $row2[3]; ?>&nbsp;</td>
		    <td width="25%" align="center"><?php echo $row2[4]; ?></td>
		  </tr></a>
		 

	<?php

		//echo $checkVariosProd;
	}
	?>

</table>