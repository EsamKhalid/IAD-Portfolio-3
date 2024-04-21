<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet"   href="https://fonts.googleapis.com/css?family=Montserrat">
    </head>
    <body id="body">
            <div class="section-stack" id="register-stack">
                <h1>Register User</h1>
                <form method="post" action="register.php" id="register-form">
                    <label>Username</label> <br>
                    <input type="text" name="username"/><br><br>    
                    <label>Password</label> <br>
                    <input type="password" name="password"/><br><br>
                    <label>Email</label> <br>
                    <input type="text" name="email"/><br><br>
                    <input type="submit" value="Register"/>
                    <input type="hidden" name="submitted" value="true"/>
                </form>
                <p>Already a user? <a href="login.php">Log in</a></p>

<?php 
if(isset($_POST["submitted"])){
    require_once("connectdb.php");

    //checks if the username is empty or not
    if(!empty($_POST['username'])){
        $username = $_POST['username'];
    }
    else{
        echo "please enter username";
        exit();
    }

    //checks if the password is empty or not then hashes it
    if(!empty(trim($_POST['password']))){
        $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    }
    else{
        echo "please enter a password";
        exit();
    }
    

    //checks if the email is both not empty and in a valid format
    if(!empty(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))){
        $email = $_POST['email'];
    }
    else{
        echo "please enter a valid email";
        exit();
    }

    try{

        $sth = $db->prepare("INSERT INTO users VALUES(DEFAULT ,? , ? ,?)");
        $sth->execute(array($username, $password, $email));

        $id = $db->lastInsertId();

        echo "<div class='section-borderless'><p>congratulations! you have successfully registered. your ID is: " .$id."</p></div>";
    } catch(PDOexception $ex){
        echo "sorry an error has occured";
        echo $ex->getMessage();
    }

}
?>

</div>
    </body>
</html>
