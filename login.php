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
//checks if the form has been submitted
if(isset($_POST["submitted"])){
    //checks if there is a username and password found
    if(!isset($_POST['username'], $_POST['password'])){
        exit("no username or password found");
    }

    // connects the database    
    require_once("connectdb.php");

    try{
        //uses prepare statement to get the password from users table to prevent sql injection
        $sth = $db->prepare('SELECT password FROM users WHERE username = ?');
        $sth->execute(array($_POST['username']));

        //checks if there is any row with the entered username
        if($sth->rowCount()>0){
            //fetches the rows
            $rows = $sth->fetch();

            //uses the verify function for check the plaintext password entered in the form to the hashed password found in the db
            if(password_verify($_POST['password'],$rows['password'])){
                //starts the session
                session_start();
                //sets the session variable
                $_SESSION["username"] = $_POST['username'];
                //uses prepare statement to insert the login details to prevent sql injection
                $uid = $db->prepare("SELECT uid FROM users WHERE username=?");
                $uid->execute(array($_POST['username']));
                //fetches the uid to set in the session variable
                $uid = $uid->fetch()[0];
                $_SESSION["uid"] = $uid;
                //redirects to the projects(main) page
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