<html>
    <body>
        <?php

        try{
            $db = new PDO("mysql:dbname=IAD3;host=localhost", "root","");
        } catch(PDOexception $ex){
            ?>
            <p> database error occured </p>
            <p>(details: <?= $ex->getMessage() ?>)</p>
        <?php
        }
        ?>
    </body>
</html>