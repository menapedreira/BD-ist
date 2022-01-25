<?php
    session_start();
    $VarTabela = $_SESSION['tabela'];

?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
    <?php
     try{

         $host = "db.ist.utl.pt";
         $user ="ist186466";
         $password = "1234";
         $dbname = $user;


         $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
         $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

         echo("<h3>Remover da Tabela $VarTabela </h3>");

        if($VarTabela == 'local'){

            $morada = $_REQUEST['morada'];
            $sql = "DELETE FROM local WHERE morada_local = :morada;";
            try{
                $result = $db->prepare($sql);
                $result->bindParam(':morada', $_REQUEST['morada']);
                $result->execute();
                echo("<p>local ($morada) removido da tabela </p>");

            }
            catch(Exception $e){
                echo("<p>ERROR:<br> Detalhes: <br>  Não foi possivel remover da tabela Local <br> <br>  {$e->getMessage()}</p>");
            }
            echo("<a href='alinea_a.php'>Voltar</a>");
        }
        else if ($VarTabela == 'evento_emergencia'){
            $numtelefone = $_REQUEST['numtelefone'];
            $instchamada = $_REQUEST['instchamada'];
            $sql = "DELETE FROM evento_emergencia
                    WHERE num_telefone = :num_telefone
                    AND instante_chamada= :instchamada;";
            try{
                $result = $db->prepare($sql);

                $result->bindParam(':num_telefone', $_REQUEST['numtelefone']);
                $result->bindParam(':instchamada', $_REQUEST['instchamada']);

                $result->execute();
                echo("<p>Evento de emergência ($numtelefone, $instchamada)eliminado da tabela</p>");

            }
            catch(Exception $e){
                echo("<p>ERROR:<br> Detalhes: <br>Não foi possivel remover da tabela Evento de emergência <br> <br> Dica: Elimine primeiro o Processo de Socorro<br> <br> {$e->getMessage()}</p>");
            }
            echo("<a href='alinea_a.php'>Voltar</a>");

        }
        else if ($VarTabela == 'processo_socorro'){ //tenho de eliminar os eventos de emergência tambem
            $numSOS = $_REQUEST['numSOS'];          // RI - todos os processos de socorro estao ligados a um E.E e vice versa
            echo("<p>$numSOS</p>");
            $sql1 = "DELETE FROM processo_socorro WHERE num_processo_socorro = :numSOS;";
            $sql2= "DELETE FROM evento_emergencia WHERE num_processo_socorro = :numSOS;";
            $db->beginTransaction();
            try{
                $result1 = $db->prepare($sql1);
                $result2 = $db->prepare($sql2);

                $result1->bindParam(':numSOS',  $_REQUEST['numSOS']);
                $result2->bindParam(':numSOS',  $_REQUEST['numSOS']);

                $result2->execute();
                $result1->execute();


                $db->commit();

                echo("<p> Processo de Socorro ($numSOS) removido da tabela</p>");
                echo("<p>Todos os Eventos de emergência de com numero de processo de socorro $numSOS foram removidos da tabela Evento de emergência</p>");

            }
            catch(Exception $e){
                echo("<p>ERROR:<br> Detalhes: <br>Não foi possivel remover da tabela Processo de Socorro <br> <br>{$e->getMessage()}</p>");
            }
            echo("<a href='alinea_a.php'>Voltar</a>");
        }
        else if ($VarTabela== 'meio'){
            $num_meio = $_REQUEST['num_meio'];
            $nome_entidade = $_REQUEST['nome_entidade'];
            $sql = "DELETE FROM meio WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            $sql_TIRA_MEIO_COMBATE = "DELETE FROM meio_combate WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            $sql_TIRA_MEIO_APOIO = "DELETE FROM meio_apoio WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            $sql_TIRA_MEIO_SOCORRO = "DELETE FROM meio_socorro WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            $sql_TIRA_ACCIONA = "DELETE FROM acciona WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            $sql_TIRA_TRANSPORTA= "DELETE FROM transporta WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            $sql_TIRA_ALOCADO = "DELETE FROM alocado WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";

            try{
                $db->beginTransaction();

                //retira da tabela transporta
                $result = $db->prepare($sql_TIRA_TRANSPORTA);
                $result->bindParam(':num_meio', $_REQUEST['num_meio']);
                $result->bindParam(':nome_entidade', $_REQUEST['nome_entidade']);
                $result->execute();
                //retira da tabela alocado
                $result = $db->prepare($sql_TIRA_ALOCADO);
                $result->bindParam(':num_meio', $_REQUEST['num_meio']);
                $result->bindParam(':nome_entidade', $_REQUEST['nome_entidade']);
                $result->execute();
                //retira da tabela acciona
                $result = $db->prepare($sql_TIRA_ACCIONA);
                $result->bindParam(':num_meio', $_REQUEST['num_meio']);
                $result->bindParam(':nome_entidade', $_REQUEST['nome_entidade']);
                $result->execute();
                //retira da tabela meio combate
                $result = $db->prepare($sql_TIRA_MEIO_COMBATE);
                $result->bindParam(':num_meio', $_REQUEST['num_meio']);
                $result->bindParam(':nome_entidade', $_REQUEST['nome_entidade']);
                $result->execute();
                //retira da tabela meio apoio
                $result = $db->prepare($sql_TIRA_MEIO_APOIO);
                $result->bindParam(':num_meio', $_REQUEST['num_meio']);
                $result->bindParam(':nome_entidade', $_REQUEST['nome_entidade']);
                $result->execute();
                //retira da tabela meio socorro
                $result = $db->prepare($sql_TIRA_MEIO_SOCORRO);
                $result->bindParam(':num_meio', $_REQUEST['num_meio']);
                $result->bindParam(':nome_entidade', $_REQUEST['nome_entidade']);
                $result->execute();

                //tira tabela meio
                $result = $db->prepare($sql);
                $result->bindParam(':num_meio', $_REQUEST['num_meio']);
                $result->bindParam(':nome_entidade', $_REQUEST['nome_entidade']);
                $result->execute();

                $db->commit();
                echo("<p>meio ($num_meio, '$nome_entidade') removido da tabela <br> E das tabelas ao qual estava associado<br></p>");

            }
            catch(Exception $e){
                echo("<p>ERROR:<br> Detalhes: <br>Não foi possivel remover da tabela Meio <br> <br>{$e->getMessage()}</p>");
            }
            echo("<a href='alinea_a.php'>Voltar</a>");

        }
        else if ($VarTabela == 'entidade'){
            $nome_entidade = $_REQUEST['nome_entidade'];
            $sql = "DELETE FROM entidade_meio WHERE nome_entidade = :nome_entidade;";
            try{

                $result = $db->prepare($sql);
                $result->bindParam(':nome_entidade', $_REQUEST['nome_entidade']);
                $result->execute();

                echo("<p>entidade meio ($nome_entidade) removido da tabela </p>");

            }
            catch(Exception $e){
                echo("<p>ERROR:<br> Detalhes: <br>Não foi possivel remover da tabela Entidade Meio <br> <br>{$e->getMessage()}</p>");
            }
            echo("<a href='alinea_a.php'>Voltar</a>");

        }
        $db = null;
        session_unset(); //limpa variaves da sessao
        session_destroy();
    }
    catch (PDOException $e){
        echo("<p>ERROR: {$e->getMessage()}</p>");
    }

    ?>
</html>
