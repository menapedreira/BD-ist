<!DOCTYPE html>
<html>
<meta charset="UTF-8">
    <body>
        <h2>Listar Meios de Socorro: <?=$_REQUEST['morada_local']?></h2>
        <form action="show_tabela.php" method="post">
            
        <fieldset>
        <legend>Morada do Local:</legend
        ><p><input type="text" name="morada_local" value="<?=$_REQUEST['morada_local']?>"/></p>
        <p><input type="submit" value="Submit"/></p>
        <a href='../menu_raiz.html'>Voltar</a>
        </fieldset>
        </form>
    </body>
</html>
