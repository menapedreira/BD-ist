<?php

    session_start();
    $_SESSION['inserir_remover'] = $_POST['inserir_remover']; //opcao de inserir ou remover escolhida
    $VarInserirRemover = $_SESSION['inserir_remover'];
    ?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
 <body >

    <h2 >Escolha a opcao:</h2>

        <form action="inserir_remover_tabelas.php" method = 'post'>
            <fieldset>

            <?php echo("<legend>Pretende $VarInserirRemover em: </legend>");?>
            <select name="tabela_escolhida">
                <option value="local" name = "local">Local</option>
                <option value="evento_emergencia" name = "evento_emergencia">Evento de emergência</option>
                <option value="processo_socorro" name = "processo_socorro">Processo de Socorro</option>
                <option value="meio" name = "meio">Meio</option>
                <option value="entidade" name = "entidade">Entidade</option>
            </select>
            <input type="submit" name="submit" value="Submit">
            <a href='alinea_a.php'>Voltar</a>
            </fieldset>
        </form>

    </body>
</html>
