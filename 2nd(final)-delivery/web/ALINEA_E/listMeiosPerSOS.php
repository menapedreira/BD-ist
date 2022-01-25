<html>
    <body>
    <!--ê-->
<?php
    $num_processo_socorro = $_REQUEST['num_processo_socorro'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist186466";
        $password = "1234";
        $dbname = $user;

        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT num_meio, nome_entidade FROM acciona WHERE num_processo_socorro = :num_processo_socorro ;";

        $result = $db->prepare($sql);
        $result->execute([':num_processo_socorro' => $num_processo_socorro]);

          echo("<h3>Listar os Meios accionados no processo de socorro $num_processo_socorro</h3>");
        echo("<table border=\"0\" cellspacing=\"5\">\n");
        echo("<tr>\n");
        echo("<td>Numero meio</td>\n");//ú
        echo("<td>Nome entidade</td>\n");
        foreach($result as $row)
        {
            echo("<tr>\n");
            echo("<td>{$row[0]}</td>\n");
            echo("<td>{$row[1]}</td>\n");

            //echo("<td><a href=\"balance.php?account_number={$row['account_number']}\">Change balance</a></td>\n");
            echo("</tr>\n");
        }
        echo("</table>\n");
        echo("<a href='listMeiosPerSOSRequest.php'>Voltar</a>");

        $db = null;
    }
    catch (PDOException $e)
    {
        echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
    </body>
</html>
