<?php  
include "../conexaophp.php";

session_start();

$nunotaorig = $_SESSION["nunotaorig"]; 
//$sequencia = $_POST["sequencia"];

$sequencia = $_REQUEST["sequencia"];

echo $nunotaorig;
echo "<br>".$sequencia;


$tsql2 = "

	IF((SELECT STATUSNOTA FROM TGFCAB WHERE NUNOTA = $nunotaorig) <> 'L')
	BEGIN
	
		DECLARE @NUNOTA INT = (SELECT AD_VINCULONF FROM TGFCAB WHERE NUNOTA = $nunotaorig)

		DELETE FROM TGFITE WHERE NUNOTA = @NUNOTA AND SEQUENCIA in ($sequencia, $sequencia*-1)
		
	END

		 ";

$stmt2 = sqlsrv_query( $conn, $tsql2);

header('Location: insereestoque.php?Itens=1');



?>