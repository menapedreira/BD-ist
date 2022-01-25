<?php
 
    $VarEE_Meio = $_REQUEST['EE_Meio'];
  
    if($VarEE_Meio == 'meio'){
        echo("        <h3>Listar todos os meios accionados num processo de socorro </h3>
        <form action='assocPSOStoMeio.php' method='post'>
            <p>Processo de Socorro: <input type='text' name='num_processo_socorro'/></p>
            <p>Nome Entidade: <input type='text' name='nome_entidade'/></p>
            <p>Numero do Meio: <input type='text' name='num_meio'/></p>

            <p><input type='submit' value='Submit'/></p>
        </form>");
        echo("<a href='alinea_d.php'>Voltar</a>");
    }else{
        echo("<h3>Listar todos os meios accionados num processo de socorro </h3>
                    <form action='assocPSOStoEE.php' method='post'>
                        <p>Processo de Socorro: <input type='text' name='num_processo_socorro'/></p>
                        <p>Numero de Telefone: <input type='text' name='num_telefone'/></p>
                        <p>Instante Chamada: <input type='text' name='instante_chamada'/></p>

                        <p><input type='submit' value='Submit'/></p>
                    </form>"); 
        echo("<a href='alinea_d.php'>Voltar</a>");  
    }
/*"<html>
                <body>
                    <h3>Listar todos os meios accionados num processo de socorro </h3>
                    <form action="assocPSOStoMeio.php" method="post">
                        <p>Processo de Socorro: <input type="text" name="num_processo_socorro"/></p>
                        <p>Numero de Telefone: <input type="text" name="num_telefone"/></p>
                        <p>Instante Chamada: <input type="text" name="instante_chamada"/></p>

                        <p><input type="submit" value="Submit"/></p>
                    </form>
                </body>
            </html>"*/
?>

