<?php
    session_start();

    $_SESSION['tabela'] = $_POST['tabela_escolhida'];
    $VarTabela = $_SESSION['tabela']; //tabela escolhida
    $VarInserirRemover = $_SESSION['inserir_remover']; //pretende inserir ou remover


?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<body>
    <?php

        //modo de inserir na tabela
        if($VarInserirRemover == 'inserir'){

            if($VarTabela == 'meio_combate'){
                echo("<h3>Inserir novo Meio de Combate</h3>");
            }
            else if($VarTabela == 'meio_socorro'){
                echo("<h3>Inserir novo Meio de Socorro</h3>");
            }
            else if($VarTabela == 'meio_apoio'){
                echo("<h3>Inserir novo Meio de Apoio</h3>");
            }

            echo("<form action='concluir.php' method='post' >
                        <fieldset>
                        <legend>Introduza os dados</legend>
                        <p>Nome entidade:<input type='text' name='nome_entidade'></p>
                        <p>Numero meio:<input type='text' name='num_meio'></p>
                        <p><input type='submit' value='Submit' /></p>
                        <a href='alinea_b.php'>Voltar</a>
                        </fieldset>
                </form>"
                );

        }
        else if($VarInserirRemover == 'remover'){

            try{
                $host = "db.ist.utl.pt";
                $user ="ist186466";
                $password = "1234";
                $dbname = $user;
                $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                if($VarTabela == 'meio_apoio'){

                    $sql = "SELECT * FROM meio_apoio ;";
                    echo("<h2>Meios de Apoio</h2>");
                }
                else if($VarTabela == 'meio_socorro'){

                    $sql = "SELECT * FROM meio_socorro ;";
                    echo("<h2>Meios de Socorro</h2>");
                }
                else if($VarTabela == 'meio_combate'){
                    $sql = "SELECT * FROM meio_combate ;";
                    echo("<h2>Meios de Combate</h2>");
                }
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
                    echo("<a href='remover.php?numMeio={$row['num_meio']}&nomeEntidade={$row['nome_entidade']}'>delete</a>");
                    echo("</td></tr>");
                }
                echo("</table>\n");
                echo("<a href='alinea_b.php'>Voltar</a>");

                $db = null;
            }
            catch (PDOException $e){
                echo("<p>ERROR: {$e->getMessage()}</p><br><a href=\"a.php\">Back</a>");
            }
        }
            //EDITAR MEIOS ALOCADOS E TRANSPORTA
        else if($VarInserirRemover == 'editar'){
            try{
                $host = "db.ist.utl.pt";
                $user ="ist186466";
                $password = "1234";
                $dbname = $user;
                $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if($VarTabela == 'meio_apoio'){

                    $sql = "SELECT * FROM meio_apoio;";
                    echo("<h2>Meios de Apoio</h2>");
                }
                else if($VarTabela == 'meio_socorro'){

                    $sql = "SELECT * FROM meio_socorro ;";
                    echo("<h2>Meios de Socorro</h2>");
                }
                else if($VarTabela == 'meio_combate'){
                    $sql = "SELECT * FROM meio_combate ;";
                    echo("<h2>Meios de Combate</h2>");
                }
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
                    echo("<a href='editar.php?numMeio={$row['num_meio']}&nomeEntidade={$row['nome_entidade']}'>edit</a>");
                    echo("</td></tr>");
                }
                echo("</table>\n");
                echo("<a href='alinea_b.php'>Voltar</a>");

                $db = null;
            }
            catch (PDOException $e){
                echo("<p>ERROR: {$e->getMessage()}</p><br><a href=\"alinea_b.php\">Back</a>");
                echo("<a href='alinea_b.php'>Voltar</a>");
            }
        }
        ?>
</body>
</html>
