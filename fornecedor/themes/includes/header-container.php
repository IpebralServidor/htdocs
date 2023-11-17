<?php session_start();


 ?>
<div id="header-container">
<a href="../view/index.php"><img src="../themes/img/logo-tis.png" /></a>

<div id="status">
 <?php 


echo "Seja bem vindo, ".$_SESSION['user'];
?>! &nbsp; :: &nbsp; <a href="../controller/LogoutController.php">Logout</a>
</div>

</div>

