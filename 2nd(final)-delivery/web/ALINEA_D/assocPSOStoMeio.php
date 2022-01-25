<html>
    <body>
<?php
    $num_processo_socorro = $_REQUEST['num_processo_socorro'];
    $num_meio = $_REQUEST['num_meio'];
    $nome_entidade = $_REQUEST['nome_entidade'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist186466";
        $password = "1234";
        $dbname = $user;

        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql = "INSERT INTO acciona VALUES (:num_meio,:nome_entidade,:num_processo_socorro);";

        $result = $db->prepare($sql);
        $result->execute([ ':num_meio' => $num_meio, ':nome_entidade' => $nome_entidade, ':num_processo_socorro' => $num_processo_socorro]);



        $db = null;
        echo("<p>Concluido</p>");
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
