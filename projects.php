<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet"   href="https://fonts.googleapis.com/css?family=Montserrat">
    </head>
    <body>
        <div class="section-stack" id="projects-container">
        <h1>Projects</h1>
        <form method="post" action="projects.php">
            <label for="search">Search Project By Date Or Title</label>
            <br><br>
            <input type="text" name="search">
            <input type="submit" name="submitted" hidden>
        </form>

<?php 
session_start();
if(!isset($_SESSION['username'])){
    echo "<p>To add new projects or modify exiting ones <a href="."'register.php'".">Register Here</a> if you are not
    a member or <a href="."'login.php'".">Login Here</a> if you are a member</p>";     
}
else{
    $username = $_SESSION['username'];

    $uid = $_SESSION['uid'];

    unset($_SESSION['project']);

    echo "<p> you are now logged in as $username! </p>";

    echo "<a href=" . "'AddProject.php'" . ">Add Project</a><br>";

}

require_once("connectdb.php");

$rows = $db->query("SELECT * FROM projects");


if(isset($_POST['submitted'])){
    

    try{
        $search = $_POST['search'];
        $result = $db->prepare('SELECT * FROM projects WHERE title=? OR start_date=?');
        $result->execute(array($search,$search));

        if($result->rowCount() > 0){
            echo "<table cellpadding='5' id='search-table'><tr><th>Title</th><th>Start Date</th><th>Phase</th><th>Description</th><th></th></tr>";
            foreach($result as $item){
                echo "<tr><td>".$item['title']."</td><td>".$item['start_date']."</td><td>".$item['phase']."</td><td>".$item['description']."</td>
                <td><a href=" . "'projectView.php?proj=" . $item["title"] . "'>View Project</a></td></tr>";
            }   
        }
        else{
            echo "could not find the project you are looking for";
        }

        
    }
    catch(PDOexception $ex){
        echo "Error fetching table";
        echo $ex->getmessage();
    }
    

}
echo "</table><br><br>";
?>

<div class="section" id="table-container">
<table cellpadding = "5" id="projects-table">
    <tr>
        <th>title</th>
        <th>start_date</th>
        <th>description</th>
        <th></th>
    </tr>
    <?php 
    foreach($rows as $row){
        echo "<td>" . $row["title"] . "</a></td>";
        echo "<td>" . $row["start_date"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td><a href=" . "'projectView.php?proj=" . $row["title"] . "'>View Project</a></td></tr>";
    }   
    ?>
</table>    
</div>
<br>
<a href="logout.php">Logout</a>
</div>
</body>
</html>



















