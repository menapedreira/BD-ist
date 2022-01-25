<?php
        session_start();

        $_SESSION['tabela'] = $_POST['tabela_escolhida']; //opcao de inserir ou remover escolhida
        $VarTabela = $_SESSION['tabela']; //tabela escolhida para editar
        $VarInserirRemover = $_SESSION['inserir_remover']; //pretende inserir ou remover


?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
    <?php
        //modo de inserir na tabela
        if($VarInserirRemover == 'inserir'){

            if($VarTabela == 'local'){
                echo("<h3>Inserir novo Local</h3>
                        <form action='concluir_pedido_inserir_remover.php' method='post' >
                                <fieldset>
                                <legend>Introduza os dados</legend>
                                <p>Morada:<input type='text' name='morada_local'></p>
                                <p><input type='submit' value='Submit' /></p>
                                <a href='alinea_a.php'>Voltar</a>
                                </fieldset>
                        </form>"
                );
            }
            else if ($VarTabela == 'evento_emergencia'){
                echo("<h3>Inserir novo Evento de emergência</h3>
                    <form action='concluir_pedido_inserir_remover.php' method='post'>
                            <fieldset>
                            <legend>Introduza os dados</legend>
                            <p>Numero de Telefone:<input type='number' name='num_telefone_evento'></p>
                            <p>Instante de Chamada:<input type='text' name='inst_chamada_evento'> [Formato: AAAA-MM-DD HH:MM:SS]</p>
                            <p>Nome Pessoa:<input type='text' name='nome_pessoa_evento'></p>
                            <p>Morada:<input type='text' name='morada_evento'></p>
                            <p>Numero do Processo de Socorro:<input type='text' name='num_processo_socorro_evento'></p>
                            <p><input type='submit' value='Submit'/></p>
                            <a href='alinea_a.php'>Voltar</a>
                            </fieldset>
                    </form>"
                );
            }
            //todos os processos de socorro tem de estar associados a um evento_emergencia
                //apresentacao do formulario de processo_de_socorro e evento_emergencia
            else if ($VarTabela == 'processo_socorro'){
                echo("<h3>Inserir novo Processo de Socorro</h3>
                    <form action='concluir_pedido_inserir_remover.php' method='post'>
                        <fieldset>
                        <legend>Introduza os dados </legend>
                        <p>Numero do Processo de Socorro:<input type='number' name='num_processo_socorro'></p>
                        <p>Numero de Telefone:<input type='number' name='num_telefone_evento'></p>
                        <p>Instante de Chamada:<input type='text' name='inst_chamada_evento'> [Formato: AAAA-MM-DD HH:MM:SS]</p>
                        <p>Nome Pessoa:<input type='text' name='nome_pessoa_evento'></p>
                        <p>Morada:<input type='text' name='morada_evento'></p>
                        <p><input type='submit' value='Submit'/></p>
                        <a href='alinea_a.php'>Voltar</a>
                        </fieldset>
                </form>"
                );
            }
            else if ($VarTabela== 'meio'){
                echo("<h3>Inserir novo Meio</h3>
                    <form action='concluir_pedido_inserir_remover.php' method='post'>
                            <fieldset>
                            <legend>Introduza os dados</legend>
                            <p>Numero do Meio:<input type='number' name='num_meio'></p>
                            <p>Nome do Meio:<input type='text' name='nome_meio'></p>
                            <p>Nome da Entidade:<input type='text' name='nome_entidade_meio'></p>
                            <p><input type='submit' value='Submit'/></p>
                            <a href='alinea_a.php'>Voltar</a>
                            </fieldset>
                    </form>"
                );
            }
            else if ($VarTabela == 'entidade'){
                echo("<h3>Inserir nova Entidade</h3>
                    <form action='concluir_pedido_inserir_remover.php' method='post'>
                            <fieldset>
                            <legend>Introduza os dados</legend>
                            <p><input type='text' name='nome_entidade'></p>
                            <p><input type='submit' value='Submit'/></p>
                            <a href='alinea_a.php'>Voltar</a>
                            </fieldset>
                    </form>"
                );
            }
        }
        //modo de remover da tabela
        else{

            try{
                $host = "db.ist.utl.pt";
                $user ="ist186466";
                $password = "1234";
                $dbname = $user;
                $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //primary key (morada local)
                if($VarTabela == 'local'){

                    $sql = "SELECT * FROM local natural join(
                                          SELECT morada_local FROM local EXCEPT (
                                          SELECT morada_local FROM vigia natural join evento_emergencia
                          )) AS T;";
                    $result = $db->prepare($sql);
                    $result->execute();
                    echo("<h2>Local</h2>");
                    echo("<table border=\"1\">\n");
                    echo("<th>morada_local</th>");

                    foreach($result as $row){
                        echo("<tr><td>");
                        echo($row['morada_local']);
                        echo("</td><td>");
                        echo("<a href='delete_from_table.php?morada={$row['morada_local']}'>delete</a>");
                        echo("</td></tr>");
                    }
                    echo("</table>\n");
                    echo("<a href='alinea_a.php'>Voltar</a>");

                }
                //primary key (num_telefone, instante_chamada)
                else if ($VarTabela == 'evento_emergencia'){

                    $sql = "SELECT * FROM evento_emergencia;";
                    $result = $db->prepare($sql);
                    $result->execute();
                    echo("<h2>Evento de emergência</h2>");
                    echo("<table border=\"1\">\n");
                    echo("<tr><td>numero de telefone</td><td>instante de chamada</td><td>nome pessoa</td><td>morada local</td><td>numero processo socorro</td></tr>");
                    foreach($result as $row){
                        echo("<tr><td>");
                        echo($row['num_telefone']);
                        echo("</td><td>");
                        echo($row['instante_chamada']);
                        echo("</td><td>");
                        echo($row['nome_pessoa']);
                        echo("</td><td>");
                        echo($row['morada_local']);
                        echo("</td><td>");
                        echo($row['num_processo_socorro']);
                        echo("</td><td>");
                        echo("<a href='delete_from_table.php?numtelefone={$row['num_telefone']}&instchamada={$row['instante_chamada']}'>delete</a>");
                        echo("</td></tr>");
                    }
                    echo("</table>\n");
                    echo("<a href='alinea_a.php'>Voltar</a>");
                }
                //primary key (num_processo_socorro)
                //AVISAR UTILIZADOR QUE AO ELIMINAR UM PROCESSO ESTA A ELIMINAR TODOS OS EVENTOS DE emergência
                else if ($VarTabela == 'processo_socorro'){
                    $sql = "SELECT * FROM processo_socorro;";
                    $result = $db->prepare($sql);
                    $result->execute();
                    echo("<h2>Processo de Socorro</h2>");
                    echo("<table border=\"1\">\n");
                    echo("<tr><td>numero processo socorro</td></tr>");
                    foreach($result as $row){
                        echo("<tr><td>");
                        echo($row['num_processo_socorro']);
                        echo("</td><td>");
                        echo("<a href='delete_from_table.php?numSOS={$row['num_processo_socorro']}'>delete</a>");
                        echo("</td></tr>");
                    }
                    echo("</table>\n");
                    echo("<a href='alinea_a.php'>Voltar</a>");
                }

                //primary key (num_meio,nome_entidade)
                else if ($VarTabela == 'meio'){
                  //nao quero meios que tenham sido accionados
                    $sql = "SELECT * FROM meio;";
                    $result = $db->prepare($sql);
                    $result->execute();
                    echo("<h2>Meio</h2>");
                    echo("<table border=\"1\">\n");
                    echo("<tr><td>numero meio</td><td>nome meio</td><td>nome entidade</td></tr>");
                    foreach($result as $row){
                        echo("<tr><td>");
                        echo($row['num_meio']);
                        echo("</td><td>");
                        echo($row['nome_meio']);
                        echo("</td><td>");
                        echo($row['nome_entidade']);
                        echo("</td><td>");
                        echo("<a href='delete_from_table.php?num_meio={$row['num_meio']}&nome_entidade={$row['nome_entidade']}'>delete</a>");
                        echo("</td></tr>");
                    }
                    echo("</table>\n");
                    echo("<a href='alinea_a.php'>Voltar</a>");
                }
                //primary key (nome_entidade)
                else if ($VarTabela == 'entidade'){
                    $sql = "SELECT * FROM entidade_meio;";
                    $result = $db->prepare($sql);
                    $result->execute();
                    echo("<h2>Entidade Meio</h2>");
                    echo("<table border=\"1\">\n");
                    echo("<tr><td>nome_entidade</td></tr>");
                    foreach($result as $row){
                        echo("<tr><td>");
                        echo($row['nome_entidade']);
                        echo("</td><td>");
                        echo("<a href='delete_from_table.php?nome_entidade={$row['nome_entidade']}'>delete</a>");
                        echo("</td></tr>");
                    }
                    echo("</table>\n");
                    echo("<a href='alinea_a.php'>Voltar</a>");
                }
                $db = null;
            }
            catch (PDOException $e){
                echo("<p>ERROR: {$e->getMessage()}</p><br><a href=\"a.php\">Back</a>");
            }
        }

    ?>



</html>
