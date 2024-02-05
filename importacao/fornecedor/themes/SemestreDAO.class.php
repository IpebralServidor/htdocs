<?php

require_once("Semestre.class.php");

require_once("../util/Conexao.php");

class SemestreDAO {


	static function Create(Semestre $s){

		$con = new Conexao();
		$con->Conectar();

		$sql = "Insert into semestre (nome, inicio, fim) values('".$s->getNome()."','".$s->getInicio()."','".$s->getFim()."')";

		$result = mysql_query($sql);

		$con->Close();

		return $result;

	}

	static function Update(Semestre $s){

		$con = new Conexao();
		$con->Conectar();

		$sql = "Update semestre set idsemsetre= '".$s->getIdSemestre()."', nome= '".$s->getNome()."', inicio ='".$s->getInicio()."', fim = '".$s->getFim()."')";

		$result = mysql_query($sql);

		$con->Close();

		return $result;

	}
	
static function SelectAll(){

$con = new Conexao();
$con->Conectar();

$sql = "Select * from semestre order by nome";

return mysql_query($sql);
$con->Close();



}

static function Select($nome, $inicio, $fim) {
  $con = new Conexao();
  if($nome == ""){
   
     $param0 = "1 = 1";
  }
    else{
    $param0 = "nome like '%".$nome."%'";
    }
  if($inicio == "")
   $param1 = "1 = 1";

  else
  
   $param1 = "inicio =".$inicio;
  if($duracao == "")
    
       $param2 = "1 = 1";
      else
       $param2 = "fim =".$fim;
  
$con->Conectar();  

$sql = "Select * from semestre where ".$param0." and ".$param1." and ".$param2;
$result = mysql_query($sql);
$con->Close();
return $result;
}



}

?>