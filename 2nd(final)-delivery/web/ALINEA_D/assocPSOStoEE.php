<html>
    <body>
<?php
    $num_processo_socorro = $_REQUEST['num_processo_socorro'];
    $num_telefone = $_REQUEST['num_telefone'];
    $instante_chamada = $_REQUEST['instante_chamada'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist186466";
        $password = "1234";
        $dbname = $user;


        //A ------------ 1                 A ------ 1
        //B ------------ 2                 B ------ 1

        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT num_processo_socorro FROM evento_emergencia WHERE num_telefone = :num_telefone AND instante_chamada = :instante_chamada";

        $result = $db->prepare($sql);
        $result->execute(['num_telefone' => $num_telefone, ':instante_chamada' => $instante_chamada]);


        $sql = "SELECT * FROM evento_emergencia WHERE num_processo_socorro = :row";
        $result = $db->prepare($sql);
        $result->execute([':row' => $row[0]]);


        if($result->rowCount() == 1){
            echo("<p> Associacao falhada: \n");
            echo("<p> O processo so tem um evento. </p>");
        }else if ($result->rowCount() == 0){
            echo("<p> Associacao falhada:\n");
            echo("<p> Nao existe nenhum evento com esse instante de chamada e ou numero de telefone. </p>");            
        }else{
            $sql = "UPDATE evento_emergencia SET num_processo_socorro = :num_processo_socorro WHERE  num_telefone = :num_telefone AND instante_chamada = :instante_chamada";

            $result = $db->prepare($sql);
            $result->execute([':num_processo_socorro' => $num_processo_socorro, ':num_telefone' => $num_telefone, ':instante_chamada' => $instante_chamada]);
        }

        $db = null;
        echo("<a href='alinea_d.php'>Voltar</a>");
    }
    catch (PDOException $e)
    {
        echo("<p>ERROR: {$e->getMessage()}</p>");
        echo("<a href='alinea_d.php'>Voltar</a>");
    }
?>
    </body>
</html>
