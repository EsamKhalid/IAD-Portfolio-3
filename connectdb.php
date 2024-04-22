<html>
    <body>
        <?php

        $dbname = "u_230022805_iad3";
        $dbhost = "localhost";
        $username = "u-230022805";
        $password = "TAVajmPfxCwu98D";

        try{
            $db = new PDO("mysql:host=$dbhost;dbname=$dbname", "$username","$password"); 
        } catch(PDOexception $ex){
            ?>
            <p> database error occured </p>
            <p>(details: <?= $ex->getMessage() ?>)</p>
        <?php
        }
        ?>
    </body>
</html>