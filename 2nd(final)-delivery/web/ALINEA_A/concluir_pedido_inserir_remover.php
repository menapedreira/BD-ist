
<?php

    session_start();
    $VarTabela = $_SESSION['tabela']; //tabela escolhida para editar

?>
<!DOCTYPE html>

<html>
<meta charset="UTF-8">
	 <body>

<?php
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist186466";
        $password = "1234";
        $dbname = $user;


        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		echo("<h3>Inserir na Tabela $VarTabela </h3>");

	    if($VarTabela == "local"){
	    	$VarMoradaLocal = $_POST['morada_local'];
			  $sql = "INSERT INTO local VALUES (:MoradaLocal);";

  			try{
  				$result = $db->prepare($sql);
  				$result->bindParam(':MoradaLocal', $_POST['morada_local']);
  	    		$result->execute();
  				echo("<p>$VarMoradaLocal inserido em local</p>");
  				echo("<a href='alinea_a.php'>Voltar</a>");
  			}

  			catch(Exception $e){
  				echo("<p>ERROR:<br> Detalhes: <br>Não foi possivel inserir na tabela Local <br> <br>  {$e->getMessage()}</p>");
  			}

		  }
	    else if($VarTabela == "evento_emergencia"){

	    	$Var_num_telefone_evento = $_POST['num_telefone_evento'];
	    	$Var_inst_chamada_evento = $_POST['inst_chamada_evento'];
	    	$Var_nome_pessoa_evento = $_POST['nome_pessoa_evento'];
	    	$Var_morada_evento = $_POST['morada_evento'];
			  $Var_num_processo_socorro_evento = $_POST['num_processo_socorro_evento'];

        try{

        //inserir na tabela do evento emergencia
			  $sql1 = "INSERT INTO evento_emergencia VALUES (:n_tlf, :inst, :nome, :morada , :n_SOS);";

				$result1 = $db->prepare($sql1);
				$result1->bindParam(':n_tlf', $_POST['num_telefone_evento']);
				$result1->bindParam(':inst', $_POST['inst_chamada_evento']);
				$result1->bindParam(':nome', $_POST['nome_pessoa_evento']);
				$result1->bindParam(':morada', $_POST['morada_evento']);
				$result1->bindParam(':n_SOS', $_POST['num_processo_socorro_evento']);

        $result1->execute();

        $db->commit();

				echo("<p>$Var_num_telefone_evento, $Var_inst_chamada_evento, $Var_nome_pessoa_evento, $Var_morada_evento, $Var_num_processo_socorro_evento inserido em Evento de Emergência </p>");
				echo("<a href='alinea_a.php'>Voltar</a>");
			}
			catch(Exception $e){
				echo("<p>ERROR:<br> Detalhes: <br><br>Não foi possivel inserir na tabela Evento de emergência <br> <br>Crie o processo de socorro <br> {$e->getMessage()}</p>");
			}
	    }
	    else if($VarTabela == "processo_socorro"){ //fazer transaction
			$Var_num_processo_socorro = $_POST['num_processo_socorro'];
			$Var_num_telefone_evento = $_POST['num_telefone_evento'];
    	$Var_inst_chamada_evento = $_POST['inst_chamada_evento'];
    	$Var_nome_pessoa_evento = $_POST['nome_pessoa_evento'];
    	$Var_morada_evento = $_POST['morada_evento'];

			$db->beginTransaction();
			$sql1 = "INSERT INTO processo_socorro VALUES (:n_SOS);";
			$sql2 = "INSERT INTO evento_emergencia VALUES (:n_tel, :inst, :nome, :morada, :n_SOS);";
			try{
				$result1 = $db->prepare($sql1);
				$result2 = $db->prepare($sql2);

				$result1->bindParam(':n_SOS',  $_POST['num_processo_socorro']);
				$result2->bindParam(':n_tlf', $_POST['num_telefone_evento']);
				$result2->bindParam(':inst', $_POST['inst_chamada_evento']);
				$result2->bindParam(':nome', $_POST['nome_pessoa_evento']);
				$result2->bindParam(':morada', $_POST['morada_evento']);
				$result2->bindParam(':n_SOS', $_POST['num_processo_socorro']);

				$result1->execute();
				$result2->execute();
				$db->commit();
				echo("<p>$Var_num_processo_socorro inserido em $VarTabela</p>");
				echo("<p>$Var_num_telefone_even $Var_inst_chamada_evento, $Var_nome_pessoa_evento, $Var_morada_evento, $Var_num_processo_socorro inserido em evento_emergencia</p>");
				echo("<a href='alinea_a.php'>Voltar</a>");
			}
			catch(Exception $e){
				echo("<p>ERROR:<br> Detalhes: <br>Não foi possivel inserir na tabela Processo de Socorro <br><br> {$e->getMessage()}</p>");
			}
	    }
	    else if($VarTabela == "meio"){
	    	$Var_num_meio = $_POST['num_meio'];
	    	$Var_nome_meio = $_POST['nome_meio'];
	    	$Var_nome_entidade = $_POST['nome_entidade_meio'];
	    	$sql = "INSERT INTO meio VALUES (:n_meio, :nome, :nome_ent_meio);";
			try{
				$result = $db->prepare($sql);

				$result->bindParam(':n_meio', $_POST['num_meio']);
				$result->bindParam(':nome', $_POST['nome_meio']);
				$result->bindParam(':nome_ent_meio', $_POST['nome_entidade_meio']);

				$result->execute();
				echo("<p>$Var_num_meio,'$Var_nome_meio','$Var_nome_entidade' inserido em meio</p>");
				echo("<a href='alinea_a.php'>Voltar</a>");
			}
			catch(Exception $e){
				echo("<p>ERROR:<br> Detalhes: <br>Não foi possivel inserir na tabela Meio <br> <br> {$e->getMessage()}</p>");
			}
	    }
	    //Tabela entidade_meio
	    else{
	    	$Var_nome_entidade_meio = $_POST['nome_entidade'];
			$sql = "INSERT INTO entidade_meio VALUES (:nome);";
			try{

				$result = $db->prepare($sql);
				$result->bindParam(':nome', $_POST['nome_entidade']);
				$result->execute();

				echo("<p>$Var_nome_entidade_meio inserido em entidade meio</p>");
				echo("<a href='alinea_a.php'>Voltar</a>");
			}
			catch(Exception $e){
				echo("<p>ERROR:<br> Detalhes: <br>Não foi possivel inserir na tabela Entidade Meio <br> <br> {$e->getMessage()}</p>");
			}
	    }

       	$db = null;
       	session_unset(); //limpa variaves da sessao
        session_destroy();
    }
    catch (PDOException $e){
        echo("<p>ERROR: {$e->getMessage()}</p>");
	}


?>
    </body>

</html>
