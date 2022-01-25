<html>
    <body>
    <h3>Processos de Socorro</h3>
<?php
    try{
        $host = "db.ist.utl.pt";
        $user ="ist186466";
        $password = "1234";
        $dbname = $user;
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //$db->beginTransaction();
        $sql1 = "SELECT num_processo_socorro FROM processo_socorro;";
        $sql2 = "SELECT num_meio,nome_meio,nome_entidade FROM meio;";
        //echo("<p>$sql</p>");

        $result1 = $db->prepare($sql1);
        $result2 = $db->prepare($sql2);
        $result1->execute();
        $result2->execute();

        //$db->commit();

        echo("<table border=\"0\" cellspacing=\"25\">");
        echo("<tr>\n");
        echo("<th>num_processo_socorro</th>\n");
        echo("</tr>\n");
        foreach($result1 as $row1)
        {

            echo("<tr>\n");
            echo("<td>{$row1['num_processo_socorro']}</td>\n");
            echo("</tr>\n");

        }
        echo("</tr>\n");
        echo("</table>\n");


        echo ("<h3> Meio </h3>\n");
        echo("<table border=\"0\" cellspacing=\"25\">");
        echo("<tr>\n");
        echo("<th>num_meio</th>\n");
        echo("<th>nome_meio</th> \n");
        echo("<th>nome_entidade</th>\n");
        echo("</tr>\n");
        foreach($result2 as $row2)
        {
            echo("<tr>\n");
            echo("<td>{$row2['num_meio']}</td>\n");
            echo("<td>{$row2['nome_meio']}</td>\n");
            echo("<td>{$row2['nome_entidade']}</td>\n");
            echo("</tr>\n");

        }
        echo("</tr>\n");
        echo("</table>\n");
        echo("<a href='../menu_raiz.html'>Voltar</a>");

        $db = null;
    }
    catch (PDOException $e)
    {
        $db->rollBack();
        echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
    </body>
</html>
