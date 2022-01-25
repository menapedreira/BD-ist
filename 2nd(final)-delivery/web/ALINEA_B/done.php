<?php
    session_start();
    $VarTabela = $_SESSION['tabela'];

?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<body>
    <?php
     try{
        $host = "db.ist.utl.pt";
        $user ="ist186466";
        $password = "1234";
        $dbname = $user;
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->beginTransaction();

        $num_meio = $_REQUEST['numMeio'];
        $nome_entidade = $_REQUEST['nomeEntidade'];
        $new_meio = $_REQUEST['newMeio'];
        $new_entidade = $_REQUEST['newEntidade'];

        if($VarTabela == 'meio_combate'){
            $sql = "UPDATE meio_combate SET num_meio = :new_meio, nome_entidade = :new_entidade WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
        }
        //EDITAR TAMBEM ALOCA E TRANSPORTA
        else if($VarTabela == 'meio_socorro'){
            $sql_select = "SELECT num_vitimas, num_processo_socorro FROM transporta WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            $sql = "UPDATE meio_socorro SET num_meio = :new_meio, nome_entidade = :new_entidade WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            //$sql1 = "UPDATE transporta SET num_meio = :new_meio, nome_entidade = :new_entidade WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
        }
        else if($VarTabela == 'meio_apoio'){
            $sql_select = "SELECT num_horas, num_processo_socorro FROM alocado WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            $sql = "UPDATE meio_apoio SET num_meio = :new_meio, nome_entidade = :new_entidade WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            //$sql1 = "DELETE FROM alocado WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";

        }
        try{
          $result = $db->prepare($sql);
          $result->bindParam(':num_meio', $_REQUEST['numMeio']);
          $result->bindParam(':new_meio', $_REQUEST['newMeio']);
          $result->bindParam(':nome_entidade', $_REQUEST['nomeEntidade']);
          $result->bindParam(':new_entidade', $_REQUEST['newEntidade']);
            if($VarTabela == 'meio_socorro' or $VarTabela == 'meio_apoio'){

                $result1 = $db->prepare($sql_select);
                $result1->bindParam(':num_meio', $_REQUEST['numMeio']);
                $result1->bindParam(':nome_entidade', $_REQUEST['nomeEntidade']);

                $result1->execute();//select all

                if($VarTabela == 'meio_socorro'){
                    $sql_delete = "DELETE FROM transporta WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
                    $sql_insert = "INSERT INTO transporta VALUES(:new_meio,:new_entidade, :num_vitimas, :num_proc_sos);";
                }
                else if($VarTabela == 'meio_apoio'){
                    $sql_delete = "DELETE FROM alocado WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
                    $sql_insert = "INSERT INTO alocado VALUES(:new_meio,:new_entidade, :num_horas, :num_proc_sos);";
                }

                $result2 = $db->prepare($sql_delete);
                $result2->bindParam(':num_meio', $_REQUEST['numMeio']);
                $result2->bindParam(':nome_entidade', $_REQUEST['nomeEntidade']);

                $result2->execute(); //delete all
                $result->execute(); //update

                foreach($result1 as $row){
                    $result3 = $db->prepare($sql_insert);
                    $result3->bindParam(':new_meio', $_REQUEST['newMeio']);
                    $result3->bindParam(':new_entidade', $_REQUEST['newEntidade']);
                    if($VarTabela == 'meio_apoio'){
                        $result3->bindParam(':num_horas', $row['num_horas']);
                    }
                    else{
                        $result3->bindParam(':num_vitimas', $row['num_vitimas']);
                    }
                    $num_PSOS = $row['num_processo_socorro'];
                    $result3->bindParam(':num_proc_sos', $row['num_processo_socorro']);
                    $result3->execute();

                }
            }
            else{
                $result->execute();
            }

            echo("<p>Registo ($num_meio , $nome_entidade) alterado para ($new_meio , $new_entidade) na tabela $VarTabela</p>");


        }
        catch(Exception $e){
            echo("<p>ERROR:<br> Detalhes: <br>  Não foi possivel editar o registo ($num_meio , $nome_entidade) para ($new_meio , $new_entidade) na tabela $VarTabela <br> <br>  {$e->getMessage()}</p>");
        }
        echo("<a href='alinea_b.php'>Voltar</a>");

        $db->commit();
        $db = null;
        session_unset(); //limpa variaves da sessao
        session_destroy();
    }
    catch (PDOException $e){
        echo("<p>ERROR: {$e->getMessage()}</p>");
        echo("<a href='alinea_b.php'>Voltar</a>");
    }

    ?>
</body>
</html>
