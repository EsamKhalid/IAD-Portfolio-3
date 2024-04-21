<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet"   href="https://fonts.googleapis.com/css?family=Montserrat">
    </head>
    <body>
        <div class="section-borderless-stack" id="login-container">
            <div class="section-stack" id="login-stack">
                <form method="post" action="login.php">
                    <label for="username">Username</label>
                    <br>
                    <input type="text" name="username"><br>
                    <br>
                    <label for="password">Password</label>
                    <br>
                    <input type="password" name="password"><br>
                    <br>
                    <input type="submit">
                    <input type="hidden" name="submitted" value="true">
                </form>
                <p> Not a member? <a href="register.php">Register Here</a></p>
            </div>
            <br>



<?php
if(isset($_POST["submitted"])){
    if(!isset($_POST['username'], $_POST['password'])){
        exit("no username or password found");
    }

    // connects the database    
    require_once("connectdb.php");

    try{
        $sth = $db->prepare('SELECT password FROM users WHERE username = ?');
        $sth->execute(array($_POST['username']));

        


        if($sth->rowCount()>0){
            $rows = $sth->fetch();

            if(password_verify($_POST['password'],$rows['password'])){
                session_start();
                $_SESSION["username"] = $_POST['username'];
                $uid = $db->prepare("SELECT uid FROM users WHERE username=?");
                $uid->execute(array($_POST['username']));
                $uid = $uid->fetch()[0];
                $_SESSION["uid"] = $uid;
                header('Location:projects.php');
                exit();
            }
            else{
                echo "password is incorrect";   
                exit();
            }
        }
        else{
            echo "username is not correct";
        }
    }
    catch(PDOexception $ex){
        echo "error";
    }

    
}
?>
</div>
</body>
</html>