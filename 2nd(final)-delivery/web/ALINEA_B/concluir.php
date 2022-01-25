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
 
        
        $nome_entidade = $_POST['nome_entidade'];
        $num_meio = $_POST['num_meio'];
          
        echo("<h3>Inserir na Tabela $VarTabela </h3>");
 
        
          
        if($VarTabela == "meio_combate"){
             
            $sql = "INSERT INTO meio_combate VALUES (:Num_meio,:Nome_entidade);";
        }
 
        else if($VarTabela == "meio_apoio"){
             
            $sql = "INSERT INTO meio_apoio VALUES (:Num_meio,:Nome_entidade);";
        }
 
        else if($VarTabela == "meio_socorro"){
             
            $sql = "INSERT INTO meio_socorro VALUES (:Num_meio,:Nome_entidade);";
        }
              
        try{
            
            $result = $db->prepare($sql);
            
            $result->bindParam(':Num_meio', $_POST['num_meio']);
            $result->bindParam(':Nome_entidade', $_POST['nome_entidade']);
            
            $result->execute();
            echo("<p>Meio ($num_meio , $nome_entidade) inserido em $VarTabela</p>");
            echo("<a href='alinea_b.php'>Voltar</a>");
        }
        catch(Exception $e){
            echo("<p>ERROR:<br> Detalhes: <br>Não foi possivel inserir ($num_meio , $nome_entidade) na tabela $VarTabela <br> <br>  {$e->getMessage()}</p>");
            if (strpos($e->getMessage(), 'duplicate key value violates unique constraint') !== false) {
                echo("<p>Registo ($num_meio , $nome_entidade) ja existe na tabela $VarTabela");
            }
            if (strpos($e->getMessage(), 'violates foreign key constraint') !== false) {
                echo("<p>Registo ($num_meio , $nome_entidade) nao existe na tabela meio.</p>");
                echo("<p>Hint: Inserir registo ($num_meio , $nome_entidade) primeiro na tabela meio (alinea_a).</p><br>");
            }

            echo("<a href='alinea_b.php'>Voltar</a>");
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