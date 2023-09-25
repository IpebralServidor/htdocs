<?php
	include "conexaophp.php";

	if(isset($_REQUEST['alert'])){
		$alert = $_REQUEST['alert'];
		if($alert == 2){
			echo "<script>alert('Senha Inválida!');</script>";
		}	

		if($alert == 1){
			echo "<script>alert('Usuário Inexistente!');</script>";
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link href="css/login.css" rel='stylesheet' type='text/css' />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<title>Login</title>
	</head>
	
	<body>
		<div class="screen">
			<div class="left">
				<img src="images/carro.png" alt="">
			</div>
			<div class="right">
				<img src="images/logo ipebral.png" alt="" width="120px">
				
				<div class="title">
					<h4>Acesse sua conta</h4>
				</div>

				<form method="post" action="App/session.php">
					<div class="form-group">
						<label for="exampleInputEmail1">Usuário</label>
						<input type="text" name="username" class="form-control" id="exampleInputEmail1">
					</div>
					<div class="form-group">
						<label for="exampleInputPassword1">Senha</label>
						<input type="password" name="password" class="form-control" id="exampleInputPassword1" required>
					</div>
					
					<button type="submit" value="Login" name="submit" class="btn btn-primary form-control">Entrar</button>
				</form>
			</div>
		</div>
		
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	</body>
</html>

<?php
	$stmt ="";
?>