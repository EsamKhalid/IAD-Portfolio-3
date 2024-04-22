<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet"   href="https://fonts.googleapis.com/css?family=Montserrat">
    </head>
    <body>
        <div class="section-stack" id="add-container">
        <h1>Add Project</h1>
        <form method="post" action="AddProject.php">
            <label for="username">Title</label>
            <br>
            <input type="text" name="title" id="input-box">
            <br><br>
            <label for="startDate">Start Date</label>
            <br>
            <input type="date" name="startDate" id="input-box">
            <br><br>
            <label for="endDate">End Date</label>
            <br>
            <input type="date" name="endDate" id="input-box">
            <br><br>
            <label for="phase">Phase</label>
            <br>
            <select name="phase" id="input-box">
                <option value="design">Design</option>
                <option value="development">Development</option>
                <option value="testing">Testing</option>
                <option value="deployment">Deployment</option>
                <option value="complete">Complete</option>
            </select>
            <br><br>
            <label for="description">Description</label>
            <br>
            <input type="text" name="description" id="input-box">
            <br><br>
            <input type="submit">
            <input type="hidden" name="submitted" value="true">
        </form>
        <br>
        <a href="projects.php">Return to project view</a>
        <br><br>


<?php 
//starts the session
session_start();
//if the user is not logged in it redirects them to the login page
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

//connects to the db
require_once("connectdb.php");

//gets the session username
$username = $_SESSION['username'];



//checks if the title field has been filled in or not
if(isset($_POST['submitted'])){

    //gets all the projects with the title that has been entered
    $titleCheck = $db->prepare("SELECT title FROM projects WHERE title = ?");
    $titleCheck->execute(array($_POST['title']));
    
    //checks for title
    if(!empty($_POST['title'])){
        $title = $_POST['title'];
    }
    else{
        echo "please enter a title <br>";
        exit();
    }
    
    //checks if there is a project with the title already
    if($titleCheck->rowCount()>0){
        echo "there is already a project with this name, please choose a different name";
        exit();
    }

    //checks if the start date has been entered
    if(!empty($_POST['startDate'])){
        $startDate = $_POST['startDate'];
    }
    else{
        echo "please enter a start date <br>";
        exit();
    }



    //checks if the end date has been entered
    if(!empty($_POST['endDate'])){
        $endDate = $_POST['endDate'];
    }
    else{
        echo "please enter an end date <br>";
        exit();
    }

    if($startDate >= $endDate){
        echo "the start date cannot be after or on the same day as the end date";
        exit();
    }

    //checks if the phase has been entered
    if(!empty($_POST['phase'])){
        $phase = $_POST['phase'];
    }
    else{
        echo "please enter a phase <br>";
        exit();
    }

    //checks if the description has been entered
    if(!empty($_POST['description'])){
        $description = $_POST['description'];
    }
    else{
        echo "please enter a description <br>";
        exit();
    }

    //gets the uid of the user that was logged in when the project is being made
    $uid = $db->query("SELECT uid FROM users WHERE username='".$username."'")->fetch()[0];

    try{
        //prepares the statement to prevent sql injections
        $proj = $db->prepare("INSERT INTO projects VALUES(DEFAULT,?,?,?,?,?,?)");
        $proj->execute(array($title, $startDate, $endDate, $phase, $description, $uid));
        //redirects to the project page when the project is successfully added
        header("Location:projects.php");
    }
    catch(PDOexception $ex){
        //returns an error if something goes wrong
        echo "error has occured adding project <br>";
        echo $ex->getMessage();

    }
}
?>
<br>

</div>
    </body>
    <br>
</html>