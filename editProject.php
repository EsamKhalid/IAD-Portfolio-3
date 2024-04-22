<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet"   href="https://fonts.googleapis.com/css?family=Montserrat">
    </head>
    <body>
        <div class="section-stack" id="edit-container">
        <h1>Edit Project</h1>



<?php 
//starts the session
session_start();

//connects the database
require_once("connectdb.php");

//if the form hasnt been submitted (query strings reset after form reset)
if(!isset($_POST['submitted'])){
    //sets the session variable to the query string stored in the url
    $_SESSION['project'] = $_GET['project'];
}

//gets the id of the user who made the project stored in the database
$uiddb = $db->prepare("SELECT uid FROM projects WHERE title = ?");
$uiddb->execute(array($_SESSION['project']));
$uiddb = $uiddb->fetch()[0];



//checks if there is a user logged in 
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
else{
    //gets the session variables
    $uid = $_SESSION['uid'];

    $username = $_SESSION['username'];

    //if the logged in user doesnt match the project owner, the user is sent back to the projects page
    if($uiddb != $uid){  
        header("Location: projects.php");
        exit();
    }

    //access the project which matches the title stored in the session using prepare to prevent sql injection
    $rows = $db->prepare("SELECT * FROM projects WHERE title=?");
    $rows->execute(array($_SESSION['project']));


    echo "<form method='post' action = 'editProject.php'>";

    //loops through the database values to pre fill the form inputs using the project details
    foreach($rows as $row){
        echo "<label>Title</label><br><input type='text' name='title' value='" . $row['title']. "'><br><br>";
        echo "<label>Start Date</label><br><input type='date' name='startDate' value='" . $row['start_date']. "'><br><br>";
        echo "<label>End Date</label><br><input type='date' name='endDate' value='" . $row['end_date']. "'><br><br>";
        echo "<label></label><select name='phase' value='".$row['phase']. "'>
        <option value='design'>Design</option>
        <option value='development'>Development</option>
        <option value='testing'>Testing</option>
        <option value='deployment'>Deployment</option>
        <option value='complete'>Complete</option>
            </select><br><br>";
        echo "<label>Description</label><br><input type='text' name='description' value='" . $row['description']. "'><br><br>";
        $pid = $row['pid'];
    }    

    echo "<input type='submit'> <input type='hidden' name='submitted' value='true'>";
    echo "</form>";
    
    //checks if form has been submitted
    if(isset($_POST['submitted'])){
        //gets all the form inputs
        $title = $_POST['title'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $phase = $_POST['phase'];
        $description = $_POST['description'];

        try{
            //uses prepare statements to insert data into the database to prevent sql injections
            $projectedit = $db->prepare("UPDATE projects SET title=?, start_date=?, end_date=?, phase=?, description=? WHERE pid = ?");
            $projectedit->execute(array($title, $startDate, $endDate, $phase, $description, $pid));

            //sends the user back to the projects page after the project has been sucessfully edited
            header("Location:projects.php");
        }
        catch(PDOexception $ex){
            //if a database error occurs
            echo "error has occured " . $ex->getmessage();
        }
    }
}
?>
</div>
</body>
</html>