<?php
 
    session_start();
    $_SESSION['inserir_remover'] = $_POST['inserir_remover']; //accao de inserir, remover ou editar escolhida
    $VarInserirRemover = $_SESSION['inserir_remover'];
     
?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
 <body >
 
    <h2 >Escolha a opcao:</h2>
     
        <form action="do.php" method = 'post'>
            <fieldset>
             
            <?php echo("<legend>Pretende $VarInserirRemover em: </legend>");?>
            <select name="tabela_escolhida">
                <option value="meio_combate">Meio de Combate</option>
                <option value="meio_socorro">Meio de Socorro</option>
                <option value="meio_apoio">Meio de Apoio</option>
            </select>
            <input type="submit" name="submit" value="Submit">
            <a href='alinea_b.php'>Voltar</a>
            </fieldset>
        </form>
        
    </body> 
</html>