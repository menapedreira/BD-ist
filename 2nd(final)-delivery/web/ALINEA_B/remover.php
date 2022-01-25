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
 
        echo("<h3>Remover da Tabela $VarTabela </h3>");

        if($VarTabela == 'meio_combate'){
            $sql = "DELETE FROM meio_combate WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
        }
        else if($VarTabela == 'meio_socorro'){
            $sql = "DELETE FROM meio_socorro WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            $sql1 = "DELETE FROM transporta WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
        }
        else if($VarTabela == 'meio_apoio'){
            $sql = "DELETE FROM meio_apoio WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            $sql1 = "DELETE FROM alocado WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
        }
        try{
            echo($num_meio);
            if($VarTabela == 'meio_socorro' or $VarTabela == 'meio_apoio'){
                $result1 = $db->prepare($sql1);
                $result1->bindParam(':num_meio', $num_meio);
                $result1->bindParam(':nome_entidade', $nome_entidade);
                $result1->execute();
            }
            $result = $db->prepare($sql);
            $result->bindParam(':num_meio', $num_meio);
            $result->bindParam(':nome_entidade', $nome_entidade);
            $result->execute();
            echo("<p>Meio ($num_meio , $nome_entidade) removido da tabela $VarTabela</p>");

            
        }
        catch(Exception $e){
            echo("<p>ERROR:<br> Detalhes: <br>  Não foi possivel remover da tabela $VarTabela <br> <br>  {$e->getMessage()}</p>");
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