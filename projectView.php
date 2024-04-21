<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet"   href="https://fonts.googleapis.com/css?family=Montserrat">
    </head>
    <body>
        <div class="section-stack" id="projectView-container">
        <h1>Project Overview</h1>

<?php

//starts the session
session_start();

//connects to the database
require_once("connectdb.php");

//gets the project title using query string
$projectTitle = $_GET['proj'];

//gets the id of the user who made the project
$uid = $db->prepare("SELECT uid FROM projects WHERE title = ?");
$uid->execute(array($projectTitle));
$uid = $uid->fetch()[0];

//checks if the user is logged in or not
if(!isset($_SESSION['username'])){
    echo "<p>If you made this project, to modify it <a href="."'login.php'".">Login Here</a></p>";     
}
else{
    $username = $_SESSION['username'];
    
    $_SESSION['project'] = $projectTitle;

    //gets the username of the 
    $projectOwner = $db->prepare("SELECT username FROM users WHERE uid=?");
    $projectOwner->execute(array($uid));
    $projectOwner = $projectOwner->fetch()[0];

    //gets the uid of the currently logged in user
    $uidLogged = $db->prepare("SELECT uid FROM users WHERE username=?");
    $uidLogged->execute(array($username));
    $uidLogged = $uidLogged->fetch()[0];

    if($uidLogged == $uid){
        echo "<p> you are now logged in as, $username! , click"." <a href=" . "'editProject.php?project=" . $projectTitle ."'>Here</a> to edit your project</p>";
    }
    else{
        echo "<p> you are now logged in as, $username! , only the creator of this project can edit it's details</p>";
    }

    
}


//prepares db to get the project details based on the title
$rows = $db->prepare("SELECT * FROM projects WHERE title=?");
$rows->execute(array($projectTitle));

$userEmail = $db->prepare("SELECT email FROM users WHERE uid=?");
$userEmail->execute(array($uid));
$userEmail = $userEmail->fetch()[0];


foreach($rows as $row){
    echo "<h2>Title: </h2>" .$row['title'];
    echo "<h2>Start Date: </h2>" . $row['start_date'];
    echo "<h2>End Date: </h2>" . $row['end_date'];
    echo "<h2>Phase: </h2>" . $row['phase'];
    echo "<h2>Description: </h2>" . $row['description'];
    echo "<h2>Email: </h2>" . $userEmail;
}

?>
<br>
<br>
 <a href="projects.php">Return to project view</a>
</div>
</body>
</html>
