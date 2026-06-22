<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="themes/css/bootstrap.css" type="text/css" />
<link rel="stylesheet" href="themes/css/bootstrap-responsive.css" type="text/css" />
<link rel="stylesheet" href="themes/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="themes/css/bootstrap-responsive.min.css" type="text/css" />
<link rel="stylesheet" href="themes/css/apprise.css" type="text/css" />
<link rel="stylesheet" href="themes/default/css/sam.css" type="text/css" />
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<script language="javascript" type="text/javascript"  src="themes/js/jquery.js">
			</script>
<script language="javascript" type="text/javascript" src="themes/js/apprise.js"></script>
<script language="javascript" type="text/javascript"  src="themes/js/bootstrap.js"></script>
<title>Importa dados do excel</title>
<style type="text/css">
<!--
.style2 {
	font-family: Arial, Helvetica, sans-serif;
	color: #FF0000;
}
-->
</style>
</head><body>
<table align="center">
  <img src="themes/img/logo40.png" ”30%” height=”40%”  />
  <tr><h1 align="center" style="color:#0000FF" class="alert-heading">
    Upload de tabela de pre&ccedil;o de fornecedor</tr>
</table>

<br />
<label>  <span class="style2">&nbsp &nbsp Esta rotina ira importar xml para a tabela do banco de dados AD_MIGRACAO</span>.</label>
<label>  <span class="style2">&nbsp &nbsp O arquivo xml dever&aacute; ter os campos CODPPROPARC, DESCRPROPARC, PRECO E CODPARC com cabe&ccedil;alho</span>.</label>

<br />
<form method="post" action="processa.php" enctype="multipart/form-data">
  <table>
    <tr>

	<td><label> &nbsp &nbsp Arquivo</label></td>
      <td> &nbsp &nbsp<input type="file"  name="arquivo" /></td>
      <br />

      <br />	  	  
    </tr>
  </table>
   &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp<input type="submit"  class="btn btn-inverse" value="Enviar" />
</form>
</body>
</html>
