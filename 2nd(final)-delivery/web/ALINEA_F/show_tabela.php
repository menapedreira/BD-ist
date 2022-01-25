<!DOCTYPE html>
<html>
<meta charset="UTF-8">
    <body >

<?php
    $morada_local = $_REQUEST['morada_local'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist186466";
        $password = "1234";
        $dbname = $user;
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo("<h2>Meios de Socorro em $morada_local</h2>");

        $sql = "SELECT num_meio ,nome_entidade
                FROM meio_socorro NATURAL JOIN acciona NATURAL JOIN evento_emergencia
                WHERE morada_local = :morada_local;";

        $result = $db->prepare($sql);
        $result->execute([':morada_local'=> $morada_local]);

        echo("<table border=\"0\" >\n");
        echo("<tr><th>num_meio</th><th>nome_entidade</th>");
        foreach($result as $row)
        {
            echo("<tr>\n");
            echo("<td>{$row['num_meio']}</td>\n");
            echo("<td>{$row['nome_entidade']}</td>\n");
            echo("</tr>\n");

        }

        echo("</tr>\n");
        echo("</table>\n");

        $db = null;
        echo("<a href='form_morada.php'>Voltar</a>");
    }
    catch (PDOException $e)
    {
        $db->rollBack();
        echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
    </body>
</html>
