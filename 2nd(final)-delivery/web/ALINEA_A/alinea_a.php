<?php
    session_start(); // Session starts here.
?>
<!DOCTYPE html>
<html>

<meta charset="UTF-8">
    <body>
        <h2>Escolha a opção:</h2>
        <form action="inserir_remover.php" method = 'post'>
            <fieldset>
            <legend>Pretende:</legend>
            <select name="inserir_remover">
                <option value="inserir" name = "inserir">Inserir Registo</option>
                <option value="remover" name = "remover">Remover Registo</option>
            </select>
            <input type="submit" name="Submit" value="Submit">
            <a href='../menu_raiz.html'>Voltar</a>
            </fieldset>
        </form>

    </body>
</html>
