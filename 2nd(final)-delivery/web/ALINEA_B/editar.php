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

        $num_meio = $_REQUEST['numMeio'];
        $nome_entidade = $_REQUEST['nomeEntidade'];
 
        echo("<h3>Editar da Tabela $VarTabela ($num_meio,$nome_entidade) para:</h3>");
         

        if($VarTabela == 'meio_combate'){
            $sql = "SELECT num_meio, nome_entidade FROM meio EXCEPT SELECT * FROM meio_combate;";
        }
        else if($VarTabela == 'meio_socorro'){
            //\$sql = "DELETE FROM meio_socorro WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            //\$sql1 = "INSERT INTO meio_socorro VALUES (:Num_meio,:Nome_entidade);";
            $sql = "SELECT num_meio, nome_entidade FROM meio EXCEPT SELECT * FROM meio_socorro;";
        }
        else if($VarTabela == 'meio_apoio'){
            //\$sql = "DELETE FROM meio_apoio WHERE num_meio = :num_meio AND nome_entidade = :nome_entidade;";
            //\$sql1 = "INSERT INTO meio_apoio VALUES (:Num_meio,:Nome_entidade);";
            $sql = "SELECT num_meio, nome_entidade FROM meio EXCEPT SELECT * FROM meio_apoio;";
        }
        try{
            $result = $db->prepare($sql);
            $result = $db->prepare($sql);
            $result->execute();
            echo("<table border=\"1\">\n");
            echo("<tr><td>numero de meio</td><td>nome da entidade</td></tr>");
            foreach($result as $row){
                    echo("<tr><td>");
                    echo($row['num_meio']);
                    echo("</td><td>");
                    echo($row['nome_entidade']);
                    echo("</td><td>");
                    echo("<a href='done.php?newMeio={$row['num_meio']}&newEntidade={$row['nome_entidade']}&numMeio={$num_meio}&nomeEntidade={$nome_entidade}'>select</a>");
                    echo("</td></tr>");
                }
                echo("</table>\n");  
            
            
        }
        catch(Exception $e){
            echo("<p>ERROR:<br> Detalhes: <br>  Não foi possivel editar registo ($num_meio , $nome_entidade) da tabela $VarTabela <br> <br>  {$e->getMessage()}</p>");
        }  
        echo("<a href='alinea_b.php'>Voltar</a>");

    }
    catch (PDOException $e){
        echo("<p>ERROR: {$e->getMessage()}</p>");
        echo("<a href='alinea_b.php'>Voltar</a>");
    }
     
    ?>
</body>
</html>