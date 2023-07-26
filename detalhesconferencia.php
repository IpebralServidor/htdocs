<?php
	include "conexaophp.php";
	require_once 'App/auth.php';

	$usuconf = $_SESSION["idUsuario"];

	$nunota2 = $_REQUEST["nunota"];
	$codbarra = $_REQUEST["codbarra"];
	$codbarra2 = $codbarra;

	if($codbarra == 0){
		$codbarra = '';
	}

	$tsql2 = "  SELECT NUMNOTA,
					   CONVERT(VARCHAR(MAX),TGFCAB.CODVEND) + ' - ' + APELIDO,
					   CONVERT(VARCHAR(MAX),TGFCAB.CODPARC) + ' - ' + TRIM(RAZAOSOCIAL)
				FROM TGFCAB INNER JOIN
					 TGFPAR ON TGFPAR.CODPARC = TGFCAB.CODPARC INNER JOIN
					 TGFVEN ON TGFVEN.CODVEND = TGFCAB.CODVEND
				WHERE NUNOTA = {$nunota2}
							"; 

	$stmt2 = sqlsrv_query( $conn, $tsql2);  

	while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
	{ $NUMNOTA = $row2[0];
	  $VENDEDOR = $row2[1];
	  $PARCEIRO = $row2[2];

	}

	$tsql3 = "  DECLARE @NUNOTA INT = {$nunota2}
				DECLARE @ULTCOD INT 

				IF ((SELECT NUCONFATUAL FROM TGFCAB WHERE NUNOTA = @NUNOTA) IS NULL)
				BEGIN
					
					SET @ULTCOD = (SELECT ULTCOD + 1 FROM TGFNUM WHERE ARQUIVO = 'TGFCON2')
					UPDATE TGFNUM SET ULTCOD = @ULTCOD WHERE ARQUIVO = 'TGFCON2'
					
					INSERT INTO TGFCON2 (CODUSUCONF,DHFINCONF,DHINICONF,NUCONF,NUCONFORIG,NUNOTADEV,NUNOTAORIG,NUPEDCOMP,QTDVOL,STATUS)
					SELECT $usuconf,
							NULL,
							GETDATE(),
							@ULTCOD,
							NULL,
							NULL,
							@NUNOTA,
							NULL,
							0,
							'A'

					UPDATE TGFCAB SET NUCONFATUAL = @ULTCOD WHERE NUNOTA = @NUNOTA

				END
						"; 
		
		$stmt3 = sqlsrv_query( $conn, $tsql3);

		//echo $nunota2;
	    //echo $codprod;
	    //echo "<script> RetornaConferencia(5) </script>";
	    //echo $codprod;
	//teste();
	//$teste3 = $_GET["codprod"];
	//echo $teste3;
?>

<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="content-type" ="text/html; charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Detalhes Conferência - <?php echo $usuconf; ?></title>


	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

	<link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>

	<link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>


	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); }>
</script>

<!-- start-smoth-scrolling -->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
	<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
	</script>

		<style>
			.popupdivergencias{
				position: fixed;
				top: 0; bottom: 0; 
				left: 0; right:0;
				margin: auto;
				width: 800px;
				height: 400px;
				padding: 15px;
				border: solid 1px #4c4d4f;
				background: white;
				display: none;
				overflow: auto;
				z-index: 10;
			}

			.popupconf {
				position: fixed;
				top: 0; bottom: 0; 
				left: 0; right:0;
				margin: auto;
				width: 450px;
				height: 160px;
				padding: 15px;
				border: solid 1px #4c4d4f;
				background: white;
				display: none;
				z-index: 0;
			}


			.popupconfdivergencia {
				position: fixed;
				top: 0; bottom: 0; 
				left: 0; right:0;
				margin: auto;
				width: 450px;
				height: 160px;
				padding: 15px;
				border: solid 1px #4c4d4f;
				background: white;
				display: none;
				z-index: 0;
			}

			.popupconfdivcorte {
				position: fixed;
				top: 0; bottom: 0; 
				left: 0; right:0;
				margin: auto;
				width: 450px;
				height: 160px;
				padding: 15px;
				border: solid 1px #4c4d4f;
				background: white;
				display: none;
				z-index: 10;
			}

			/*------------------------------------------------------------------*/
			/*---------Inserindo Fortamação para Consulta de Produtos-----------*/


			@import url(https://fonts.googleapis.com/css?family=Roboto+Condensed);
				

				.nav_tabs{
					width: 600px;
					/*height: 400px;*/
					margin: 0px auto;
					background-color: #fff;
					position: relative;
					margin-left: -40px;
				}

				.nav_tabs ul{
					list-style: none;
				}

				.nav_tabs ul li{
					float: left;
				}

				.tab_label{
					display: block;
					width: 60px;
					background-color: #363b48;
					padding: 5px;
					font-size: 15px;
					color:#fff;
					cursor: pointer;
					text-align: center;
				}


				.nav_tabs .rd_tab { 
				display:none;
				position: absolute;
			}

			.nav_tabs .rd_tab:checked ~ label { 
				background-color: #e54e43;
				color:#fff;}

			.tab-content{
				border-top: solid 5px #e54e43;
				background-color: #fff;
				display: none;
				position: absolute;
				height: 200px;
				width: 59%;
				left: 40px;	
				overflow: auto;
			}

			.rd_tab:checked ~ .tab-content{
				display: block;
			}
			.tab-content h2{
				/*padding: 10px;*/
				color: #87d3b7;
			}
			.tab-content article{
				/*padding: 10px;*/
				color: #555;
			}

			

			/*-------Fim Inserindo Fortamação para Consulta de Produtos---------*/
			/*------------------------------------------------------------------*/
			

		</style>


	<style type="text/css">
		/*#minhatabela tr:hover td{background-color: #feffb7;}
		//#minhatabela tr.selecionado td{background-color: #aff7ff}*/

	    tr{transition:all .25s ease-in-out}
        tr:hover{background-color:#FFFF95;cursor: pointer}
        tr:active{background-color: yellow;}

        .selected{background-color: yellow;}

	</style>

	<link href="css/main.css" rel='stylesheet' type='text/css' />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<?php 
		$tsql2 = "  select COUNT(*)
					from TGFITE inner join
							TGFCAB ON TGFCAB.NUNOTA = TGFITE.NUNOTA FULL OUTER JOIN
							TGFCOI2 on TGFCOI2.NUCONF = TGFCAB.NUCONFATUAL
								AND TGFCOI2.CODPROD = TGFITE.CODPROD INNER JOIN
							TGFPRO ON TGFPRO.CODPROD = TGFITE.CODPROD
					where TGFITE.NUNOTA = '{$nunota2}'
						and (TGFCOI2.QTDCONF <> TGFITE.QTDNEG or TGFCOI2.QTDCONF is null)
				"; 

		$stmt2 = sqlsrv_query( $conn, $tsql2);  

		while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
		{ $QtdDivergencias = $row2[0];
		}

		$tsql3 = "  select COUNT(*)
					from TGFITE inner join
							TGFCAB ON TGFCAB.NUNOTA = TGFITE.NUNOTA INNER JOIN
							TGFCOI2 on TGFCOI2.NUCONF = TGFCAB.NUCONFATUAL
								AND TGFCOI2.CODPROD = TGFITE.CODPROD INNER JOIN
							TGFPRO ON TGFPRO.CODPROD = TGFITE.CODPROD
					where TGFITE.NUNOTA = '{$nunota2}'
						and TGFCOI2.QTDCONF <> TGFITE.QTDNEG
				"; 

		$stmt3 = sqlsrv_query( $conn, $tsql3);  

		while( $row2 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_NUMERIC))  
		{ $QtdDivCorte = $row2[0];
		}

		//echo '<h4> '.$QtdDivergencias.'</h4>	';
	?>

	<script type="text/javascript">
		


		$(document).ready(function(){
	    $('#select_all').on('click',function(){
	        if(this.checked){
	            $('.checkbox').each(function(){
	                this.checked = true;
	            });
	        }else{
	             $('.checkbox').each(function(){
	                this.checked = false;
	            });
	        }
	    });
		
	    $('.checkbox').on('click',function(){
	        if($('.checkbox:checked').length == $('.checkbox').length){
	            $('#select_all').prop('checked',true);
	        }else{
	            $('#select_all').prop('checked',false);
	        }
	    });
	});


	function delete_confirm(){
    if($('.checkbox:checked').length > 0){
        var result = confirm("Tem certeza que deseja apagar esse(s) item(ns)?");
        if(result){
            return true;
        }else{
            return false;
        }
    }else{
        alert('Selecione pelo menos uma linha para poder excluir!');
        return false;
    }
}
	
	function confirmar_conf() {
		var result = confirm("Tem certeza que deseja confirmar essa conferência?");
        if(result){
        	if(<?php echo $QtdDivergencias ?> == 0 && <?php echo $QtdDivCorte ?> == 0){
	        	abrirconf();
	            return true;
        	} else if (<?php echo $QtdDivCorte ?> > 0){
				abrirconfdivcorte();
				return true;
			} else {
        		abrirconfdivergencia();
            return true;
        	}
        }else{
            return false;
        }
	}

	</script>

		<script type="text/javascript">
			function abrirdivergencias(){
				document.getElementById('popupdivergencias').style.display = 'block';
			}
			function fechardivergencias(){
				document.getElementById('popupdivergencias').style.display =  'none';
			}

			function abrirconf(){
				document.getElementById('popupconf').style.display = 'block';
			}
			function fecharconf(){
				document.getElementById('popupconf').style.display =  'none';
			}
			function abrirconfdivergencia(){
				document.getElementById('popupconfdivergencia').style.display =  'block';
			}
			function fecharconfdivergencia(){
				document.getElementById('popupconfdivergencia').style.display =  'none';
			}
			function abrirconfdivcorte(){
				document.getElementById('popupconfdivcorte').style.display =  'block';
			}
			function fecharconfdivcorte(){
				document.getElementById('popupconfdivcorte').style.display =  'none';
			}
		</script>

</head>
<body style="width: 100%; height: 100%; overflow: hidden;" 
	<?php 
					$tsql5 = "  DECLARE @NUNOTA INT = $nunota2
								DECLARE @NUCONF INT = (SELECT NUCONFATUAL from TGFCAB where NUNOTA = @NUNOTA)
								DECLARE @CODBARRA VARCHAR(100) = trim('$codbarra')
								DECLARE @CODPROD INT = (SELECT CODPROD FROM TGFBAR WHERE CODBARRA = @CODBARRA)
								DECLARE @CONT INT 
								DECLARE @CONTDIV INT

								SELECT @CONT = COUNT(*)
								FROM TGFIVC 
								WHERE CODBARRA = @CODBARRA
								  AND NUCONF = (SELECT NUCONFATUAL FROM TGFCAB WHERE NUNOTA = @NUNOTA)
								  AND QTD = (SELECT QTDNEG FROM TGFITE WHERE NUNOTA  = @NUNOTA AND CODPROD = @CODPROD)

								SELECT @CONTDIV = COUNT(*)
								FROM TGFIVC 
								WHERE CODBARRA = @CODBARRA
								  AND NUCONF = (SELECT NUCONFATUAL FROM TGFCAB WHERE NUNOTA = @NUNOTA)
								  AND QTD <> (SELECT QTDNEG FROM TGFITE WHERE NUNOTA  = @NUNOTA AND CODPROD = @CODPROD)


								SELECT @CONT, @CONTDIV
									"; 

				    $stmt5 = sqlsrv_query( $conn, $tsql5);  

					while( $row3 = sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_NUMERIC))  
						  {$cont = $row3[0]; 
						   $contdiv = $row3[1];
						  }

					if ($cont == 0 && $codbarra != 0 && $codbarra != '' && $contdiv == 0){
						echo ' onload="document.fmrInsreItens.QUANTIDADE.focus(); document.fmrInsreItens.QUANTIDADE.select();"';
					} else if($cont == 0 and $contdiv != 0){
						echo ' onload="document.fmrInsreItens.CODBAR.focus(); document.fmrInsreItens.CODBAR.select();"';
					} else{
						echo ' onload="document.fmrInsreItens.CODBAR.focus();"';
						$codbarra = '';
						$codbarra2 = '';
					}

						
						
				 ?>
				 >

	<span style="margin-bottom: 0; margin-left: 30px; font-size: 20px;">
			<!--<button onclick="window.location.href='listaconferencia.php'"></button>-->
			<a href="listaconferencia.php"><aabr title="Voltar"><img style="width: 40px; margin-top: 45px; position: fixed;" src="images/Seta Voltar.png" /></aabr></a>
			<strong> Nro. Nota: &nbsp </strong> <?php echo $NUMNOTA ?> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
			<strong>Vendedor: </strong> <?php echo $VENDEDOR ?> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
			<strong>Nro. Único: </strong> <?php echo $nunota2; ?> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
			<strong>Parceiro: </strong> <?php echo $PARCEIRO; ?> 
			
	</span>

	<form action="detalhesconferencia.php?nunota=<?php echo $nunota2 ?>&codbarra=<?php echo $codbarra ?>" method="post" style="margin-left: 80px; margin-top: -20px;" name="fmrInsreItens">

				<br><br>
				Cód. Barras: <input type="text" name="CODBAR" class="text" value="<?php 
					$tsql5 = "  DECLARE @NUNOTA INT = $nunota2
								DECLARE @NUCONF INT = (SELECT NUCONFATUAL from TGFCAB where NUNOTA = @NUNOTA)
								DECLARE @CODBARRA VARCHAR(100) = trim('$codbarra')
								DECLARE @CODPROD INT = (SELECT CODPROD FROM TGFBAR WHERE CODBARRA = @CODBARRA)

								SELECT COUNT(*)
								FROM TGFIVC 
								WHERE CODBARRA = @CODBARRA
								  AND NUCONF = (SELECT NUCONFATUAL FROM TGFCAB WHERE NUNOTA = @NUNOTA)
								  AND QTD = (SELECT QTDNEG FROM TGFITE WHERE NUNOTA  = @NUNOTA AND CODPROD = @CODPROD)
									"; 

				    $stmt5 = sqlsrv_query( $conn, $tsql5);  

					while( $row3 = sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_NUMERIC))  
						  {$cont = $row3[0]; }

					if ($cont == 0 && $codbarra != 0 && $codbarra != ''){
						echo $codbarra;
					} else {
						echo '';
					}

						
						
				 ?>" id='codbarra' style="margin-right: 30px;" required>
				Quantidade: <input type="text" name="QUANTIDADE" class="text" style="margin-right: 30px; text-align: left;" value = "<?php 
						if($codbarra != 0 && $codbarra != ''){
							echo '1';
						}
				 ?>">
				Controle: <input type="text" name="CONTROLE" class="text">
				<input name="conferir" type="submit" value="Conferir" style="margin-left: 50px;">

				<?php
				$codbarra='';
				
				 if(isset($_POST["conferir"])){

					$codbarra = $_POST["CODBAR"];
					$quantidade = str_replace(',', '.', $_POST["QUANTIDADE"]) ;
					$controle = $_POST["CONTROLE"];


					//Valida se o código de barra existe 
					$tsql6 = "  SELECT COUNT(*) FROM TGFBAR WHERE CODBARRA = '$codbarra'
							 "; 

					$stmt6 = sqlsrv_query( $conn, $tsql6);  

						while( $row3 = sqlsrv_fetch_array( $stmt6, SQLSRV_FETCH_NUMERIC))  
						{   $qtdcodigobarra = $row3[0];

							if($qtdcodigobarra == 0){
								//echo "teste";
							echo "<script> alert('Esse Código de Barra não está cadastrado!'); window.location.href='detalhesconferencia.php?nunota=$nunota2&codbarra=0';  </script>";
							}

						
						}


					if($quantidade == ''){
						$quantidade = 0;
					}
					//echo $codbarra;
					//header('Location: detalhesconferencia.php?nunota='.$nunota2.'&codbarra='.$codbarra);

					$tsql5 = "  DECLARE @NUNOTA INT = $nunota2
								DECLARE @NUCONF INT = (SELECT NUCONFATUAL from TGFCAB where NUNOTA = @NUNOTA)
								DECLARE @CODBARRA VARCHAR(100) = trim('$codbarra2')
								DECLARE @CODPROD INT = (SELECT CODPROD FROM TGFBAR WHERE CODBARRA = @CODBARRA)
								DECLARE @QTDNOTA FLOAT = (SELECT SUM(QTDNEG) FROM TGFITE WHERE CODPROD = @CODPROD AND NUNOTA = @NUNOTA)
								DECLARE @QTDCONT FLOAT = (SELECT QTDCONF FROM TGFCOI2 WHERE CODPROD = @CODPROD AND NUCONF = @NUCONF)

								SELECT ISNULL(@QTDCONT,0), @QTDNOTA
									"; 

						$stmt5 = sqlsrv_query( $conn, $tsql5);  

						while( $row3 = sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_NUMERIC))  
						{   $quantidadeconferida = $row3[0];
							$quantidadenota = $row3[1];

							if(($quantidade + $quantidadeconferida) > $quantidadenota){
								//echo "teste";
							echo "<script> alert('Quantidade inserida não pode ser maior do que a existente na nota!'); </script>";
							//header("Refresh: 0");
							}

						
						}


					echo "<script> window.location.href='detalhesconferencia.php?nunota=$nunota2&codbarra=$codbarra' </script>";
					//exit;
					//header("Refresh: 0");
					//exit();



					if($quantidade > 0) {
						

											  
						/*$tsql5 = "  DECLARE @NUNOTA INT = '{$nunota2}'
									DECLARE @NUCONF INT = (SELECT NUCONFATUAL from TGFCAB where NUNOTA = @NUNOTA)
									DECLARE @CODBARRA VARCHAR(100) = '{$codbarra2}'
									DECLARE @QTDNOTA INT = (SELECT SUM(QTDNEG) FROM TGFITE WHERE CODPROD = @CODPROD AND NUNOTA = @NUNOTA)

									SELECT QTDCONF, @QTDNOTA FROM TGFCOI2 WHERE CODBARRA = @CODBARRA AND NUCONF = @NUCONF
									"; 

						$stmt5 = sqlsrv_query( $conn, $tsql5);  

						while( $row3 = sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_NUMERIC))  
						{   $quantidadeconferida = $row3[0];
							$quantidadenota = $row3[1];

							if(($quantidade + $quantidadeconferida) > $quantidadenota){
								echo "teste";
							echo "<script> alert('Quantidade não pode ser maior do que a existente na nota!') </script>";
							//header("Refresh: 0");
							}

						
						}*/
					

						$tsql2 = "  DECLARE @NUNOTA INT = $nunota2
									DECLARE @NUCONF INT = (SELECT NUCONFATUAL from TGFCAB where NUNOTA = @NUNOTA)
									DECLARE @CODBARRA VARCHAR(100) = trim('$codbarra2')
									DECLARE @QTDNEG FLOAT = $quantidade
									DECLARE @QTDNEGANT FLOAT = (SELECT QTDCONF FROM TGFCOI2 WHERE CODBARRA = @CODBARRA AND NUCONF = @NUCONF)
									DECLARE @CODPROD INT = (SELECT CODPROD FROM TGFBAR WHERE CODBARRA = @CODBARRA)
									DECLARE @QTDNOTA FLOAT = (SELECT SUM(QTDNEG) FROM TGFITE WHERE CODPROD = @CODPROD AND NUNOTA = @NUNOTA)

									IF ((ISNULL(@QTDNEGANT,0) + ISNULL(@QTDNEG,0)) <= ISNULL(@QTDNOTA,0))
									BEGIN

										IF((SELECT MAX(SEQVOL) FROM TGFVCF WHERE NUCONF = @NUCONF) IS NULL)
										BEGIN
											INSERT INTO TGFVCF ( NUCONF,ORDEM,SEQVOL ) 
											SELECT  @NUCONF,
													1,
													1 
										END


										IF ((SELECT COUNT(1) FROM TGFIVC WHERE NUCONF = @NUCONF AND CODBARRA = @CODBARRA) = 0)
										BEGIN
											INSERT INTO TGFIVC ( CODBARRA,CODPROD,CODVOL,CONTROLE,IMPRIMEAUTO,NUCONF,QTD,QTDVOLPAD,SEQITEM,SEQVOL ) 
											SELECT TGFBAR.CODBARRA,
													TGFBAR.CODPROD,
													TGFITE.CODVOL,
													CONTROLE,
													'S',
													NUCONF,
													@QTDNEG,
													@QTDNEG, --CONFIRMAR
													ISNULL((SELECT MAX(SEQITEM) FROM TGFIVC WHERE NUCONF = @NUCONF),0) + 1,
													1
											FROM TGFBAR INNER JOIN
													TGFITE ON TGFITE.CODPROD = TGFBAR.CODPROD INNER JOIN
													TGFCAB ON TGFCAB.NUNOTA = TGFITE.NUNOTA INNER JOIN
													TGFVCF ON TGFVCF.NUCONF = TGFCAB.NUCONFATUAL
											WHERE CODBARRA = @CODBARRA
												AND TGFCAB.NUNOTA = @NUNOTA
										END 
										ELSE BEGIN
											SET @QTDNEGANT = (SELECT QTD FROM TGFIVC WHERE NUCONF = @NUCONF AND CODBARRA = @CODBARRA)
											UPDATE TGFIVC SET QTD = @QTDNEG + @QTDNEGANT, QTDVOLPAD = @QTDNEG + @QTDNEGANT WHERE NUCONF = @NUCONF AND CODBARRA = @CODBARRA
										END	

										IF ((SELECT COUNT(1) FROM TGFCOI2 WHERE CODBARRA = @CODBARRA AND NUCONF = @NUCONF) = 0)
										BEGIN
											INSERT INTO TGFCOI2 ( CODBARRA,CODPROD,CODVOL,CONTROLE,COPIA,DHALTER,NUCONF,QTDCONF,QTDCONFVOLPAD,SEQCONF ) 
											SELECT CODBARRA,
													TGFITE.CODPROD,
													TGFITE.CODVOL,
													TGFITE.CONTROLE,
													NULL,
													GETDATE(),
													@NUCONF,
													@QTDNEG, --QUANTIDADE CONFERIDA
													@QTDNEG, 
													ISNULL((SELECT MAX(SEQCONF) FROM TGFCOI2 WHERE NUCONF = @NUCONF),0) + 1
											FROM TGFBAR INNER JOIN
													TGFITE ON TGFITE.CODPROD = TGFBAR.CODPROD
											WHERE CODBARRA = @CODBARRA
												AND NUNOTA = @NUNOTA
										END
										ELSE BEGIN
											SET @QTDNEGANT = (SELECT QTDCONF FROM TGFCOI2 WHERE NUCONF = @NUCONF AND CODBARRA = @CODBARRA)

											IF ((@QTDNEG + @QTDNEGANT) > @QTDNOTA)
											BEGIN
												raiserror('Teste',16,1)
											END

											UPDATE TGFCOI2 SET QTDCONF = @QTDNEG + @QTDNEGANT, QTDCONFVOLPAD = @QTDNEG + @QTDNEGANT WHERE NUCONF = @NUCONF AND CODBARRA = @CODBARRA
										END

									END

									"; 

						$stmt2 = sqlsrv_query( $conn, $tsql2); 

						$codbarra = 0;
						$codbarra2 = 0;

						//echo "<script> document.getElementById('CODBAR').value = 0; </script>";
						echo "<script> window.location.href='detalhesconferencia.php?nunota=$nunota2&codbarra=0'; </script>";
						//header("Refresh: 0");
					} 
						
				}


				?>

	</form>

	<!-- Itens em Conferência-->
	<div id="container" style="width:100%; height: 90%; position: absolute; bottom: 0; margin-bottom: 0; padding-left: 5px; padding-right: 10px;">
		
		<div id="ItensConferencia" style="width: 48%; height:48%; /*background-color: green;*/ display: inline-block; margin-right: 0; overflow: hidden; margin: 1%;">

			<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-left:15px; padding-top: 2px; width: 90%; display:inline-block;">Itens em Conferência</h4><h4 style="width:6%; display: inline-block; margin-bottom:0; text-align: center;"></h4>



			<button style="margin-left: 20px; margin-top: 5px; cursor: hand; cursor: pointer;" onclick="confirmar_conf();">Finalizar Conferência</button> 
			<button style="cursor: hand; cursor: pointer;" onclick="abrirdivergencias();">Produtos Divergentes</button>


			<div id="popupconf" class="popupconf">

				<form action="finalizarconf.php" method="post" style="margin-left: 10px; margin-top: -10px;">
					<!-- <h6> Conferência finalizada com Sucesso! </h6> -->
					<br>Qtd. Volume: <input type="text" name="QTDVOLUME" class="text" value="" style="margin-top: 10px;" required>
					<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Volume: <input type="text" name="VOLUME" class="text" style="margin-top: 10px;" required>
					<br>&nbsp;&nbsp;&nbsp;Peso Bruto: <input type="text" name="PESOBRUTO" class="text" style="margin-top: 10px;">
					<input type="text" name="NUNOTACONF" class="text" value="<?php echo $nunota2 ?>" hidden>

					<input name="Confirmar" type="submit" value="Confirmar" style="margin-left: 80px; margin-top: 10%; ">

				</form>

	
				<button style="cursor: hand; cursor: pointer; position: absolute; right: 0; bottom: 0; margin-bottom: 5.8%; margin-right: 33%;" onclick="fecharconf();">Fechar</button>

				

			</div>


			<!-- 
				POP UP de Produtos Divergentes 
			-->

			<div id="popupdivergencias" class="popupdivergencias">
				<!--<div class="modal__close close-modal" title="Close">
                        <i class='bx bx-x'></i>
                    </div>-->

				<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-left:15px; padding-top: 2px; width: 90%; display:inline-block;">Produtos com Divergência</h4><h4 style="width:6%; display: inline-block; margin-bottom:0; text-align: center;"></h4>
				<div style=" width: 98%; height: 340px; position: absolute; overflow: auto; margin-top: 5px;">
					<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
						  <tr> 
						    <th width="10.6%" >Produto</th>
						    <th width="36.6%" style="text-align: center;">Descrição do Produto</th>
						    <th width="10.6%" align="center">Complemento</th>
						    <th width="12.6%" align="center">Controle</th>
						    <th width="12.6%" align="center">Qtd. Conferida</th>
						    <th width="16.6%" align="center">Qtd. Pedido</th>
						  </tr>


						  <?php 
							$tsql2 = "  select TGFITE.CODPROD, 
											   DESCRPROD, 
											   COMPLDESC, 
											   TGFITE.CONTROLE, 
											   ISNULL(TGFCOI2.QTDCONF,0), 
											   TGFITE.QTDNEG
										from TGFITE inner join
											 TGFCAB ON TGFCAB.NUNOTA = TGFITE.NUNOTA FULL OUTER JOIN
											 TGFCOI2 on TGFCOI2.NUCONF = TGFCAB.NUCONFATUAL
													AND TGFCOI2.CODPROD = TGFITE.CODPROD INNER JOIN
											 TGFPRO ON TGFPRO.CODPROD = TGFITE.CODPROD
										where TGFITE.NUNOTA = '{$nunota2}'
										  and (TGFCOI2.QTDCONF <> TGFITE.QTDNEG or TGFCOI2.QTDCONF is null)
										order by TGFCOI2.SEQCONF desc
										"; 

							$stmt2 = sqlsrv_query( $conn, $tsql2);  

							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
							{ $NUCONF = $row2[0];
						?>

							  <tr style="cursor: hand; cursor: pointer;">
							   <tr> 
							    <td width="10.6%" ><?php echo $row2[0]; ?>&nbsp;</td>
							    <td width="36.6%"><?php echo $row2[1]; ?>&nbsp;</td>
							    <td width="10.6%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
							    <td width="12.6%" align="center"><?php echo $row2[3]; ?></td>
							    <td width="12.6%" align="center"><?php echo $row2[4]; ?></td>
							    <td width="16.6%" align="center"><?php echo $row2[5]; ?></td>
							  </tr></a>
							 

						<?php
						}
						?>

					</table>
				</div>
				<button style="cursor: hand; cursor: pointer; position: relative; float: right; right: 0; bottom: 0; margin-bottom: 2%; margin-right: 2%; margin-top: 10px; position: absolute;" onclick="fechardivergencias();">Fechar</button>
			</div>

			<!-- Fim 
			POP UP de Produtos Divergentes 
			-->


			<!-- 
				POP UP Para Conferência Finalizada com Divergência
			-->



			<div id="popupconfdivergencia" class="popupconfdivergencia">
				<h4 style="text-align: center; margin-top: 8%;">Conferência finalizada como divergente!</h4>
				<button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto;" onclick="" >Realizar recontagem</button>
				<!--<form>
					<input type="submit" name="btn-recontagem" value="Realizar recontagem">
				</form>-->
				<button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto; margin-top: 3%;" onclick="fecharconfdivergencia(); abrirconf();">Concluir</button>
			</div>

			<!-- Fim 
				POP UP Para Conferência Finalizada com Divergência
			-->


			<!-- 
				POP UP Para Conferência Finalizada com Divergência (Corte)
			-->

			<?php

				if(isset($_POST['btn-recontagem'])){
					    $tsql3 = "  DECLARE @ULTCOD INT
									select @ULTCOD = ULTCOD + 1 from TGFNUM where ARQUIVO = 'TGFCON2' and CODEMP = 1 and SERIE = '.'

									UPDATE TGFNUM SET ULTCOD = @ULTCOD WHERE ARQUIVO = 'TGFCON2' and CODEMP = 1 and SERIE = '.'

									UPDATE TGFCAB SET NUCONFATUAL = @ULTCOD WHERE TGFCAB.NUNOTA = $nunota2
					            "; 

					    $stmt3 = sqlsrv_query( $conn, $tsql3); 
									}

			?>

			<div id="popupconfdivcorte" class="popupconfdivcorte">
				<h4 style="text-align: center; margin-top: 3%;">Conferência finalizada como divergente!</h4>
				<!--<button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto;" onclick="" >Realizar recontagem</button>-->
				<form method="post" style="width: 100%;">
					<input style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto; margin-top: 3%;" type="submit" name="btn-recontagem" value="Realizar recontagem">
				</form>
				<button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto; margin-top: 3%;" onclick="" >Cortar itens divergentes</button>
				
				<button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto; margin-top: 3%;" onclick="fecharconfdivcorte(); abrirconf();">Concluir</button>
			</div>

			<!-- Fim 
				POP UP Para Conferência Finalizada com Divergência (Corte)
			-->

		 <div style="overflow: auto; height: 83.5%;">
			<table width="1300" border="1px" bordercolor="black" style="margin-top: 5px; margin-left: 7px;" id="table">
			  <tr> 
			    <th width="10.6%" >Produto</th>
			    <th width="36.6%" style="text-align: center;">Descrição do Produto</th>
			    <th width="10.6%" align="center">UN</th>
			    <th width="12.6%" align="center">Controle</th>
			    <th width="12.6%" align="center">Ref. do Forn.</th>
			    <th width="16.6%" align="center">Código de Barras</th>


			  </tr>



			<?php 
				$tsql2 = "  DECLARE @CODEMP INT 
							DECLARE @CODPARC INT 

							SELECT @CODEMP = CODEMP,
								   @CODPARC = CODPARC
							FROM TGFCAB 
							WHERE NUNOTA = {$nunota2}

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
							WHERE TGFBAR.CODBARRA = '{$codbarra2}'
							"; 

				$stmt2 = sqlsrv_query( $conn, $tsql2);  

				while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
				{ $NUCONF = $row2[0];
			?>

				<table width="1300" border="1" cellspacing="0" style="margin-left: 7px;">
				  <a href="detalhesconferencia.php?codprod=<?php echo $row2[0] ?>"><tr style="cursor: hand; cursor: pointer;">
				    <td width="10.6%" ><?php echo $row2[0]; ?>&nbsp;</td>
				    <td width="36.6%"><?php echo $row2[1]; ?>&nbsp;</td>
				    <td width="10.6%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
				    <td width="12.6%" align="center"><?php echo $row2[3]; ?></td>
				    <td width="12.6%" align="center"><?php echo $row2[4]; ?></td>
				    <td width="16.6%" align="center"><?php echo $row2[5]; ?></td>
				  </tr></a>
				 

			<?php
			}
			?>
		</table>
	 </div>

		</div> <!-- Itens Conferência -->





		<!-- Itens Conferidos-->

		<div style="width: 48%; height:48%; /*background-color: yellow;*/ display: inline-block; float: right; margin-left: 0; margin: 1%; overflow: hidden;">
			
			<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-left:15px; padding-top: 2px; width: 90%; display: inline-block;">Itens Conferidos</h4><h4 style="width: 6%; color: black; display: inline-block; margin-bottom:0; text-align: center;padding-bottom: 1px; "><?php 
				$tsql2 = "  DECLARE @NUNOTA INT = $nunota2
							DECLARE @NUCONF INT = (SELECT NUCONFATUAL from TGFCAB where NUNOTA = @NUNOTA)

							SELECT COUNT (1)
							FROM TGFPRO INNER JOIN
								 TGFCOI2 ON TGFCOI2.CODPROD = TGFPRO.CODPROD
							WHERE TGFCOI2.NUCONF = @NUCONF
								 "; 

						$stmt2 = sqlsrv_query( $conn, $tsql2);  

						while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
						{ $QtdConferidos = $row2[0];
						}

						echo $QtdConferidos;
											  ?></h4>


		<form name="bulk_action_form" action="action.php" method="post" onSubmit="return delete_confirm();"/>
		<div style="overflow: auto; height: 92%; width: 109.5%;">
			<table width="2500" border="1px"    bordercolor="white" style="margin-top: 30px; margin-left: 7px;" id="table">
			  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
			  	<th width="1%" style="margin-right: 0; "><input type="checkbox" id="select_all" value=""/></th> 
			    <th width="4%" ><font  face="Arial, Helvetica, sans-serif">Referência</font></th>
			    <th width="5%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Produto</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Qtd. Conf.</font></th>
			    <th width="10%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Barras</font></th>
			    <th width="17%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Descrição (Produto)</font></th>
			    <th width="5%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">UN</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Controle</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Qth. Un. Pad.</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Complemento</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Ref. Forn.</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Marca</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Qth. Ident.</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Tip. Ident.</font></th>
			    <th width="10%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Tip. Contr. Est.</font></th>
			  </tr>

			<?php 
				$tsql2 = "  DECLARE @NUNOTA INT = {$nunota2}
							DECLARE @NUCONF INT = (SELECT NUCONFATUAL from TGFCAB where NUNOTA = @NUNOTA)

							SELECT REFERENCIA,
								   TGFPRO.CODPROD,
								   TGFCOI2.QTDCONF,
								   TGFCOI2.CODBARRA,
								   DESCRPROD,
								   TGFPRO.CODVOL,
								   CONTROLE,
								   TGFCOI2.QTDCONFVOLPAD,
								   NULL AS COMPLEMENTO,
								   REFFORN,
								   MARCA,
								   QTDIDENTIF,
								   TIPOIDENTIF,
								   TIPCONTEST
							FROM TGFPRO INNER JOIN
								 TGFCOI2 ON TGFCOI2.CODPROD = TGFPRO.CODPROD
							WHERE TGFCOI2.NUCONF = @NUCONF
							ORDER BY SEQCONF DESC
						 "; 

				$stmt2 = sqlsrv_query( $conn, $tsql2);  

				while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
				{ $NUCONF = $row2[0];
			?>

				
				  <tr >
				  	<td align="center" width="1%"><input type="checkbox" name="id[<?php echo "$row2[3]/$nunota2"; ?>]" class="checkbox"/></td>  
				    <td width="4%" ><?php echo $row2[0]; ?>&nbsp;</td>
				    <td width="5%"><?php echo $row2[1]; ?>&nbsp;</td>
				    <td width="5%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
				    <td width="10%" align="center"><?php echo $row2[3]; ?></td>
				    <td width="17%" align="center"><?php echo $row2[4]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[5]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[6]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[7]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[8]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[9]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[10]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[11]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[12]; ?></td>
				    <td width="10%" align="center"><?php echo $row2[13]; ?></td>
				  </tr>
				 

			<?php
			}
			?>
			</table></div>
			<div style="background-color: white ; position: absolute; top: 4px; width: 2000px; z-index: 10; height: 30px; margin-top:35px;">
			<input type="submit" name="bulk_delete_submit" value="Apagar Item(ns) Selecionado(s)" style="position: absolute; top: 5px; margin-left: 0px; border-collor: white; width: 230px; text-align: left; border-radius: 5px; border-width: 1px; padding-top:1px;"></div>
		 
		</form>
		</div> <!-- Itens Conferidos -->



		<!-- Imagem e Consulta de Produtos -->
		<div id="Imagem do Produto" style="width: 48%; height: 43%; /*background-color: #D9DAFA;*/ display: inline-block; overflow: hidden; margin: 1%; ">
			
			<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-left:15px; padding-top: 2px; width: 100%; text-align:center;">Informações do Produto</h4>

			<div style="width: 50%; height: 100%; line-height: 100%; align-items: center; display: inline-flex; margin-top: 0; padding-top: 0">

			<?php

				if($codbarra2!=0){
					$tsql2 = " select ISNULL(IMAGEM,(SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000))
							   from TGFPRO inner join
								    --TGFITE ON TGFITE.CODPROD = TGFPRO.CODPROD INNER JOIN
									TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD
							   where CODBARRA = '{$codbarra2}' ";
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

						echo '<img style="vertical-align: middle;  max-width: 280px; margin: auto; max-height: 90%;" src="data:image/jpeg;base64,'.base64_encode($row2[0]).'"/>';
						//$imageData = $row2["image"];
					}
				} 

				/*if($codbarra2 == 0){
					echo '<img style="vertical-align: middle; height: 90%; margin: auto;" src="http://ipebral.com.br/fotos/produtos/arquivo_sem_imagem.png"/>';
				}*/
				
			//header("content-type: image/jpeg");
			//echo $imageData;

			//echo $row_count; 
				/*$tsql2 = "  select top 1 IMAGEM
							from TGFPRO 
							where IMAGEM is not null
							"; 

				$mimetype = new \Mimey\MimeTypes();
				$mimetype = $mimer->getMimeType(pathinfo($path,PATHINFO_EXTENSION));

				$source = file_get_contents($path);
				$base64 = base64_encode($source);
				$blob = 'data:'.$$mimetype.';base64,'.base64;

				$html = '<img src="'.$blob.'" alt="Description for differently abled humans." />';
				echo $html;
				$stmt2 = sqlsrv_query( $conn, $tsql2);
				//echo $stmt2;

				while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
				{ $blob = $row2[0];

				}

				$image = imagecreatefromstring($blob);

				ob_start();
				imagejpeg($image,null,80);
				$data = ob_get_contents();
				ob_end_clean();
				echo '<img src= "data:image/jpg;base64,'. base64_encode($data) .'" />'; */

			?>

			</div > <!-- Parte da Imagem -->

			<!-- Parte das Características -->
			<div style=" display: inline-block; height: 90%; width: 49%; overflow-y: hidden; overflow-x: hidden; margin-top: 10px;"> 

				<nav class="nav_tabs">
				    <ul>
				        <li>
				            <input type="radio" id="tab1" class="rd_tab" name="tabs" checked>
				            <label for="tab1" class="tab_label">Carac.</label>
				            <div class="tab-content">
				                <!--<h2>Title 1</h2>-->
				                <article>
				                <div style="margin-top: 10px;">
				                    <?php

										$tsql2 = "SELECT   TGFPRO.CODPROD,
														   DESCRPROD,
														   REFERENCIA,
														   AD_CARACTERISTICAS,
														   PROMOCAO,
														   AGRUPMIN,
														   TGFPRO.CODVOL,
														   AD_CODLOCAL
													FROM TGFPRO INNER JOIN
														 TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD
													WHERE CODBARRA = '{$codbarra2}'";

										$stmt2 = sqlsrv_query( $conn, $tsql2);  

										while($row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
										{ 

											echo "<span><strong>Cód. Produto:</strong> " .$row2[0]."</span><br>";
											echo "<strong>Descr. Produto: </strong>" .$row2[1]."<br>";
											echo "<strong>Referência: </strong>" .$row2[2]."<br>";
											echo "<strong>Características: </strong>" .$row2[3]."<br>";
											echo "<strong>Promoção: </strong>" .$row2[4]."<br>";
											echo "<strong>Agrup. Mínimo: </strong>" .$row2[5]."<br>";
											echo "<strong>Unidade Padrão: </strong>" .$row2[6]."<br>";
											echo "<strong>Local Padrão Ad.: </strong>" .$row2[7]."<br>";

										}
									?>
								</div>
				                </article>
				            </div>
				        </li>
				        <li>
				            <input type="radio" name="tabs" class="rd_tab" id="tab2">
				            <label for="tab2" class="tab_label">Comp.</label>
				            <div class="tab-content" style="overflow: auto;">
				                <!-- <h2>Title 2</h2> -->
				                <article>
					                <table width="800" border="1px"    bordercolor="white" style="margin-top: 5px; " id="table">
									  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
									    <th width="20%" ><font  face="Arial, Helvetica, sans-serif">Referência</font></th>
									    <th width="40%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Descr. Produto</font></th>
									    <th width="20%" align="center"><font  face="Arial, Helvetica, sans-serif">Qtd. Mistura.</font></th>
									    <th width="20%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Mát. Prima</font></th>
									  </tr>

									<?php 
										$tsql2 = " 	SELECT REFERENCIA, 
														   DESCRPROD, 
														   QTDMISTURA, 
														   CODMATPRIMA 
													FROM TGFICP INNER JOIN
														 TGFPRO ON TGFPRO.CODPROD = TGFICP.CODMATPRIMA
													WHERE TGFICP.CODPROD IN (select CODPROD from TGFBAR where CODBARRA = '{$codbarra2}')
													  AND VARIACAO = 0
												 "; 

										$stmt2 = sqlsrv_query( $conn, $tsql2);  

										while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
										{
									?>

										
										  <tr >
										    <td width="20%" ><?php echo $row2[0]; ?>&nbsp;</td>
										    <td width="40%"><?php echo $row2[1]; ?>&nbsp;</td>
										    <td width="20%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
										    <td width="20%" align="center"><?php echo $row2[3]; ?></td>
										  </tr>
										 

									<?php
									}
									?>
								  </table>
				                </article>
				            </div>
				        </li>
				        <li>
				            <input type="radio" name="tabs" class="rd_tab" id="tab3">
				            <label for="tab3" class="tab_label">Estoque</label>
				            <div class="tab-content">
				                <!-- <h2>Title 3</h2> -->
				                <article>
				                    <table width="1500" border="1px"    bordercolor="white" style="margin-top: 5px; " id="table">
									  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
									    <th width="5%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Emp.</font></th>
									    <th width="7%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Disponível</font></th>
									    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Estoque</font></th>
									    <th width="7%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Reservado</font></th>
									    <th width="7%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Local</font></th>
									    <th width="20%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Desc. Local</font></th>
									    <th width="7%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Controle</font></th>
									    <th width="7%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Dt. Val.</font></th>
									    <th width="10%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Tipo</font></th>
									    <th width="25%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Barra</font></th>
									  </tr>

									<?php 
										$tsql2 = " 	SELECT CODEMP,
														   (ESTOQUE - RESERVADO) AS 'DISPONIVEL',
														   ESTOQUE,
														   RESERVADO,
														   TGFEST.CODLOCAL,
														   TGFLOC.DESCRLOCAL,
														   CONTROLE,
														   CONVERT(VARCHAR(MAX),TGFEST.DTVAL,103),
														   TGFEST.TIPO,
														   CODBARRA
													FROM TGFEST INNER JOIN
														 TGFLOC ON TGFLOC.CODLOCAL = TGFEST.CODLOCAL
													WHERE CODPROD = (select CODPROD from TGFBAR where CODBARRA = '{$codbarra2}')
													  and TGFEST.CODPARC = 0
												 "; 

										$stmt2 = sqlsrv_query( $conn, $tsql2);  

										while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
										{
									?>

										
										  <tr >
										    <td width="5%" align="center"><?php echo $row2[0]; ?>&nbsp;</td>
										    <td width="7%" align="center"><?php echo $row2[1]; ?>&nbsp;</td>
										    <td width="5%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
										    <td width="7%" align="center"><?php echo $row2[3]; ?></td>
										    <td width="7%" align="center"><?php echo $row2[4]; ?></td>
										    <td width="20%" align="center"><?php echo $row2[5]; ?></td>
										    <td width="7%" align="center"><?php echo $row2[6]; ?></td>
										    <td width="7%" align="center"><?php echo $row2[7]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[8]; ?></td>
										    <td width="25%" align="center"><?php echo $row2[9]; ?></td>
										  </tr>
										 

									<?php
									}
									?>
								  </table>
				                </article>
				            </div>
				        </li>
				        <li>
				            <input type="radio" name="tabs" class="rd_tab" id="tab4">
				            <label for="tab4" class="tab_label">Preço</label>
				            <div class="tab-content">
				                <!-- <h2>Title 4</h2> -->
				                <article>
				                	<table width="700" border="1px" bordercolor="white" style="margin-top: 5px;  text-align: center;" id="table">
									  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
									    <th width="16.66%" ><font  face="Arial, Helvetica, sans-serif">Preço</font></th>
									    <th width="16.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Tabela</font></th>
									    <th width="16.66%" align="center"><font  face="Arial, Helvetica, sans-serif">Nome Tab.</font></th>
									    <th width="16.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Qtd. Máx. Loc.</font></th>
									    <th width="16.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Média Vendas</font></th>
									    <th width="16.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Promoção?</font></th>
									  </tr>

									<?php 
										$tsql2 = " 	SELECT EXC.VLRVENDA,
														   TAB.CODTAB,
														   TGFNTA.NOMETAB,
														   --CODTABORIG,
														   AD_QTDMAXLOCAL,
														   ROUND(MEDIA6,2) AS MEDIA6,
														   PROMOCAO
													FROM TGFEXC EXC INNER JOIN 
														 TGFTAB TAB on EXC.NUTAB = TAB.NUTAB INNER JOIN
														 TGFPRO ON TGFPRO.CODPROD = EXC.CODPROD INNER JOIN
														 AD_MEDIAVENDAEMP ON AD_MEDIAVENDAEMP.CODPROD = TGFPRO.CODPROD
																		 AND CODEMP = 0 INNER JOIN
														 TGFNTA ON TGFNTA.CODTAB = TAB.CODTAB
													WHERE TAB.CODTAB = 0 
														AND EXC.CODPROD = (select CODPROD from TGFBAR where CODBARRA = '{$codbarra2}')
														AND TAB.DTVIGOR = (SELECT MAX(TAB1.DTVIGOR) 
																		FROM TGFEXC EXC1, 
																			TGFTAB TAB1 
																		WHERE EXC1.CODPROD = EXC.CODPROD
																			AND EXC1.NUTAB = TAB1.NUTAB 
																			AND TAB1.CODTAB = 0)
												 "; 

										$stmt2 = sqlsrv_query( $conn, $tsql2);  

										while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
										{
									?>

										
										  <tr >
										    <td width="16.66%" ><?php echo $row2[0]; ?>&nbsp;</td>
										    <td width="16.66%"><?php echo $row2[1]; ?>&nbsp;</td>
										    <td width="16.66%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
										    <td width="16.66%" align="center"><?php echo $row2[3]; ?></td>
										    <td width="16.66%" align="center"><?php echo $row2[4]; ?></td>
										    <td width="16.66%" align="center"><?php echo $row2[5]; ?></td>
										  </tr>
										 

									<?php
									}
									?>
								  </table>
				                </article>
				            </div>
				        </li>
				        <li>
				            <input type="radio" name="tabs" class="rd_tab" id="tab5">
				            <label for="tab5" class="tab_label">Res.</label>
				            <div class="tab-content">
				                <!-- <h2>Title 5</h2> -->
				                <article>
				                    <table width="2500" border="1px"    bordercolor="white" style="margin-top: 5px;" id="table">
									  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
										<th width="6.66%" ><font  face="Arial, Helvetica, sans-serif">Nro. Único</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Nro. Nota</font></th>
										<th width="6.66%" align="center"><font  face="Arial, Helvetica, sans-serif">Cód. Empresa</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Qtd. Neg.</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Dt. Neg.</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Controle</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Parceiro</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Nome Parceiro</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Vendedor</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Nome Vendedor</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">TOP</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Descr. TOP</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Dt. Prev. Entrega</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Tipo de Pedido</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Descr. Tip. Pedido</font></th>
									  </tr>

									<?php 
										$tsql2 = " 	SELECT DISTINCT CAB.NUNOTA,
																	CAB.NUMNOTA,
																	CAB.CODEMP,
																	ITE.QTDNEG,
																	CONVERT(VARCHAR(MAX),CAB.DTNEG,103),
																	ITE.CONTROLE,
																	CAB.CODPARC,
																	PAR.NOMEPARC,
																	CAB.CODVEND,
																	VEN.APELIDO,
																	CAB.CODTIPOPER,
																	TPO.DESCROPER,
																	CONVERT(VARCHAR(MAX),CAB.DTPREVENT,103),
																	(SELECT TPD.CODTPD
																	    FROM TGFTPD TPD
																		WHERE TPD.CODTPD = CAB.CODTPD) AS TIPOPEDIDO,
																    (SELECT TPD.DESCRICAO
																		FROM TGFTPD TPD
																		WHERE TPD.CODTPD = CAB.CODTPD) AS DESCTIPOPEDIDO
													FROM TGFITE ITE INNER JOIN
													     TGFCAB CAB ON ITE.NUNOTA = CAB.NUNOTA INNER JOIN
													     TGFPAR PAR ON CAB.CODPARC = PAR.CODPARC INNER JOIN
														 TGFVEN VEN ON CAB.CODVEND = VEN.CODVEND INNER JOIN
														 TSIEMP EMP ON ITE.CODEMP = EMP.CODEMP INNER JOIN
														 TGFTOP TPO ON TPO.CODTIPOPER = CAB.CODTIPOPER
													WHERE ITE.CODPROD  = (select CODPROD from TGFBAR where CODBARRA = '{$codbarra2}')
																  AND ITE.PENDENTE = 'S'
																  AND ITE.RESERVA  = 'S'   
												 "; 

										$stmt2 = sqlsrv_query( $conn, $tsql2);  

										while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
										{
									?>

										
										  <tr >
										    <td width="10%" ><?php echo $row2[0]; ?>&nbsp;</td>
										    <td width="10%"><?php echo $row2[1]; ?>&nbsp;</td>
										    <td width="10%" align="center"><?php echo $row2[2]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[3]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[4]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[5]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[6]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[7]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[8]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[9]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[10]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[11]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[12]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[13]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[14]; ?></td>
										  </tr>
										 

									<?php
									}
									?>
								  </table>
				                </article>
				            </div>
				        </li>
				    </ul>
				</nav>

								



			</div> <!-- Parte das Características -->

		</div> <!-- Imagem do Produto -->




		<div id="Itens do Pedido" style="width: 48%; height:43%; /*background-color: red;*/ display: inline-block; float: right;  margin-top: -200px; margin: 1%; overflow: hidden; margin-left: 0;">
			
			<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-left:15px; padding-top: 2px; width: 90%; display: inline-block;">Itens do Pedido</h4><h4 style="display: inline-block; margin-bottom: 0; text-align: center; width: 6%;"><?php 
				$tsql2 = "  SELECT COUNT(1)
							FROM TGFCAB CAB INNER JOIN
									TGFITE ITE ON ITE.NUNOTA = CAB.NUNOTA INNER JOIN
									TGFPRO PRO ON PRO.CODPROD = ITE.CODPROD INNER JOIN
									TGFBAR BAR ON BAR.CODPROD = PRO.CODPROD 
											  AND BAR.DHALTER = (SELECT MAX(TGFBAR.DHALTER) 
											  					 FROM TGFBAR 
											  					 WHERE TGFBAR.CODPROD = BAR.CODPROD
											  					) INNER JOIN
									TGFVOL VOL ON VOL.CODVOL = ITE.CODVOL 
							WHERE CAB.NUNOTA = $nunota2
								 "; 

						$stmt2 = sqlsrv_query( $conn, $tsql2);  

						while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
						{ $QtdPedido = $row2[0];
						}

						echo $QtdPedido;
											  ?></h4>

			<!--<button style="margin-left: 20px; margin-top: 5px; cursor: hand; cursor: pointer; " >Espaço</button> -->
			<div style="overflow: auto; width: 100%; height: 95%;">
			<table width="2000" border="1px" bordercolor="black" style="margin-top: 5px; margin-left: 7px;" id="table">
			  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
			    <th width="10%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Referência&nbsp;</font></th>
			    <th width="5%" ><font  face="Arial, Helvetica, sans-serif">Produto&nbsp;</font></th>
			    <th width="10%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Barra&nbsp;</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Qtd. Neg.</font></th>
			    <th width="25%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Descrição (Produto)</font></th>
			    <th width="10%" align="center"><font  face="Arial, Helvetica, sans-serif">Nro. Único</font></th>
			    <th width="10%" align="center"><font  face="Arial, Helvetica, sans-serif">Sequência</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">UN</font></th>
			    <th width="10%" align="center"><font  face="Arial, Helvetica, sans-serif">Descrição (UN)</font></th>
			    <th width="10%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Controle</font></th>
			  </tr>
			


						<!-- Pesquisa o Número da Nota no Banco para que sejam retornados os itens -->
			<?php 
				$tsql2 = "  SELECT PRO.REFERENCIA,
								   ITE.CODPROD,
								   BAR.CODBARRA,
								   ITE.QTDNEG,
								   PRO.DESCRPROD,
								   CAB.NUNOTA,
								   ITE.SEQUENCIA,
								   ITE.CODVOL,
								   VOL.DESCRVOL,
								   ITE.CONTROLE,
								   ITE.AD_DESCMAX,
								   ITE.AD_MOTIVOCORTE,
								   ITE.AD_DIF_ICMSST,
								   ITE.AD_EXCECAOTAB,
								   ITE.AD_CODPROPAR,
								   ITE.AD_VINCULONF,
								   ITE.AD_VINCULO_SEQ,
								   ITE.AD_MOTIVOVINCULONF,
								   PRO.REFFORN,
								   PRO.MARCA,
								   PRO.DECQTD,
								   PRO.AD_MANUFATUR,
								   PRO.AD_CODLOCSEC,
								   PRO.AD_CNPJ,
								   PRO.AD_EMBALAGEM,
								   PRO.AD_CODLOCAL,
								   PRO.AD_PROMO_FAST,
								   PRO.AD_NROCAIXA,
								   PRO.AD_CODLOCAL3,
								   PRO.AD_CODLOCAL4,
								   PRO.AD_CODLOCALPADEMP1,
								   PRO.AD_CODLOCALPADEMP1,
								   --PRO.AD_CODLOCALEMP1,
								   --PRO.AD_CODLOCALEMP7,
								   PRO.AD_DTPRIMEIRAENTRADA,
								   PRO.AD_SAZONAL
							FROM TGFCAB CAB INNER JOIN
								 TGFITE ITE ON ITE.NUNOTA = CAB.NUNOTA INNER JOIN
								 TGFPRO PRO ON PRO.CODPROD = ITE.CODPROD INNER JOIN
									 TGFBAR BAR ON BAR.CODPROD = PRO.CODPROD 
											   AND BAR.DHALTER = (SELECT MAX(TGFBAR.DHALTER) FROM TGFBAR WHERE TGFBAR.CODPROD = BAR.CODPROD) INNER JOIN
								 TGFVOL VOL ON VOL.CODVOL = ITE.CODVOL --INNER JOIN
								 --TGFCON2 CONF ON CONF.NUCONF = CAB.NUCONFATUAL
							WHERE CAB.NUNOTA = {$nunota2}
							"; 

				$stmt2 = sqlsrv_query( $conn, $tsql2);  

				while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
				{ $NUCONF = $row2[0];
			?>

				
				  <tr style="cursor: hand; cursor: pointer;">
				    <td width="10%" ><?php echo $row2[0]; ?>&nbsp;</td>
				    <td width="5%" ><?php echo $row2[1]; ?>&nbsp;</td>
				    <td width="10%"><?php echo $row2[2]; ?>&nbsp;</td>
				    <td width="5%" align="center"><?php echo $row2[3]; ?>&nbsp;</td>
				    <td width="25%" align="center"><?php echo $row2[4]; ?></td>
				    <td width="10%" align="center"><?php echo $row2[5]; ?></td>
				    <td width="10%" align="center"><?php echo $row2[6]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[7]; ?></td>
				    <td width="10%" align="center"><?php echo $row2[8]; ?></td>
				    <td width="10%" align="center"><?php echo $row2[9]; ?></td>
				  </tr></a>
				
			<?php
			}
			?>
			 </table>
			 </div>

	        <!--
			<script>
				var tabela = document.getElementById("minhatabela");
				var linhas = tabela.getElementsByTagName("tr");

				for(var=0;i<linhas.lenght;i++){
					var linha = linhas[i];
					linha.addEventListener("click",function(){
						selLinha(this,false);
					});
				}

				function selLinha(linha, multiplos){
				if(!multiplos){
			  	var linhas = linha.parentElement.getElementsByTagName("tr");
			    for(var i = 0; i < linhas.length; i++){
			      var linha_ = linhas[i];
			      linha_.classList.remove("selecionado");    
				    }
				  }
				  linha.classList.toggle("selecionado");
				}

				/**
				Exemplo de como capturar os dados
				**/
				var btnVisualizar = document.getElementById("visualizarDados");

				btnVisualizar.addEventListener("click", function(){
					var selecionados = tabela.getElementsByClassName("selecionado");
				  //Verificar se eestá selecionado
				  if(selecionados.length < 1){
				  	alert("Selecione pelo menos uma linha");
				    return false;
				  }
				  
				  var dados = "";
				  
				  for(var i = 0; i < selecionados.length; i++){
				  	var selecionado = selecionados[i];
				    selecionado = selecionado.getElementsByTagName("td");
				    dados += "ID: " + selecionado[0].innerHTML + " - Nome: " + selecionado[1].innerHTML + " - Idade: " + selecionado[2].innerHTML + "\n";
				  }
				  
				  alert(dados);
				});
				//function RetornaConferencia(codprod) {
					<?php/*
						function minhafuncao($teste){
							
							echo $teste;
							return $teste;
						
						}

						function teste(){
							minhafuncao(10);
						}
						
						*/
					?>
					//codprod = 5;
					//return codprod;
					//window.open("detalhes.php?id="+valor);
				//}
			</script>
			-->

		</div> <!-- Itens do Pedido -->


	
	</div> <!--container-->


	<script>
    
        var index,
            table = document.getElementById("table");
       
        
        
        // display selected row data into input text
        function selectedRowToInput()
        {
        	 
            //

            for(var i = 1; i < table.rows.length; i++)
            {

            	//alert("teste");

                table.rows[i].onclick = function()
                {
                	   
                  if(typeof index !== "undefined" ) {
                    table.rows[index].classList.toggle("selected");
                  }

                  // get the seected row index
                  index = this.rowIndex;
                  //<?php $fname ="456" ?>
                  //var $nome = document.getElementById("fname").value;
                  //document.getElementById("fname").value = this.cells[0].innerHTML;
                  //document.getElementById("lname").value = this.cells[1].innerHTML;
                  //document.getElementById("age").value = this.cells[2].innerHTML;
                  this.classList.toggle("selected");
                  //<?php echo $fname; ?>
                  //table.rows.style.backgroundColor = 'red';
                  //this.style.backgroundColor = 'yellow';
                  //this.toggleClass("active");




                };
            }
        }
        selectedRowToInput();
    
        
        function removeSelectedRow()
        {
            table.deleteRow(index);
            // clear input text
            //document.getElementById("fname").value = "";
            //document.getElementById("lname").value = "";
            //document.getElementById("age").value = "";
        }
    </script>
</body>
</html>