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
session_start();

require_once("connectdb.php");

if(!isset($_POST['submitted'])){
    $_SESSION['project'] = $_GET['project'];
}

//gets the id of the user who made the project stored in the database
$uiddb = $db->prepare("SELECT uid FROM projects WHERE title = ?");
$uiddb->execute(array($_SESSION['project']));
$uiddb = $uiddb->fetch()[0];

$uid = $_SESSION['uid'];

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
else{
    $username = $_SESSION['username'];

    if($uiddb != $uid){  
        //header("Location: login.php");
        exit();
    }

    $rows = $db->prepare("SELECT * FROM projects WHERE title=?");
    $rows->execute(array($_SESSION['project']));


    echo "<form method='post' action = 'editProject.php'>";

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
    

    if(isset($_POST['submitted'])){
        $title = $_POST['title'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $phase = $_POST['phase'];
        $description = $_POST['description'];

        try{
            $projectedit = $db->prepare("UPDATE projects SET title=?, start_date=?, end_date=?, phase=?, description=? WHERE pid = ?");
            //$projectedit = $db->prepare("UPDATE projects SET `title`=? WHERE pid=?");
            $projectedit->execute(array($title, $startDate, $endDate, $phase, $description, $pid));
            //$projectedit->execute(array($title,$pid));

            header("Location:projects.php");
        }
        catch(PDOexception $ex){
            echo "error has occured " . $ex->getmessage();
        }
    }



}
?>
</div>
</body>
</html>