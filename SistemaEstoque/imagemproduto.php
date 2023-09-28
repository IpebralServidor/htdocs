<?php header('Content-Type: text/html; iso-8859-1');?>
<?php
include "../conexaophp.php";

$codbarra = $_POST["produto"];

if($codbarra!=0){
	$tsql2 = " SELECT ISNULL((select IMAGEM
			   from TGFPRO inner join
				    --TGFITE ON TGFITE.CODPROD = TGFPRO.CODPROD INNER JOIN
					TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD
			   where CODBARRA = '{$codbarra}'),(SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000)) ";
} else {
	$tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000 ";
}

$stmt2 = sqlsrv_query( $conn, $tsql2);

if($stmt2){
	$row_count = sqlsrv_num_rows( $stmt2 ); 


	while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
	{ 
		echo '<img style="vertical-align: middle;  max-width: 260px; margin: auto; max-height: 110px;" src="data:image/jpeg;base64,'.base64_encode($row2[0]).'"/>';
		//$imageData = $row2["image"];
	}
} 

?>