<?php
    session_start();
    include "../../conexaophp.php";

    $nuorcamento = $_SESSION['nuorcamento'];
    //echo "alert('teste');";
    //echo "Passou por aqui!";

    $json = file_get_contents('php://input'); // Recebe os dados brutos do POST
    $data = json_decode($json, true); // Decodifica o JSON para um array associativo

    //Exclui os itens antes de inserir, para que não insira nenhum item que não seja dessa seleção
    $query = "EXEC AD_STP_EXCLUIITENS_IMPORTACAO_TELEMARKETING ?";
    $params = array($nuorcamento);
    $stmt = sqlsrv_query($conn, $query, $params);

    if (isset($data['selecionados'])) {
        $selecionados = $data['selecionados'];

        //Insere primeiramente os itens em uma tabela temporária, para depois inserir na 1700
        foreach ($selecionados as $item) {
            $referencia = $item['referencia'];

            // Faça algo com os dados, como salvar no banco de dados
            //echo "Referência: $referencia\n";
            
            $query = "EXEC AD_STP_INSEREITENS1700_IMPORTACAO_TELEMARKETING ?, ?";

            $params = array($nuorcamento, $referencia);
            $stmt = sqlsrv_query($conn, $query, $params);
        
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
        }

  
        $query = "EXEC AD_STP_INSERE1700_IMPORTACAO_TELEMARKETING ?";
        $params = array($nuorcamento);
        $stmt = sqlsrv_query($conn, $query, $params);

          
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
            $retorno = $row[0];
            echo utf8_encode($retorno);
        }
        

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }



    } else {
        echo "Nenhum dado recebido.";
    }

    

?>