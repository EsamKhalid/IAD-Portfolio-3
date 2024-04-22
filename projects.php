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
            <label for="titleSearch">Search Project By Title</label>
            <br><br>
            <input type="text" name="titleSearch">
            <input type="submit" value="search"><br><br>
            <input type="hidden" name="titleSubmitted" value="true">
        </form>
        <form method="post" action="projects.php">
            <label for="dateSearch">Search Project By Date</label>
            <br><br>
            <input type="date" name="dateSearch">
            <input type="submit" value="search"><br><br>
            <input type="hidden" name="dateSubmitted" value="true">
        </form>
<?php 
//starts session
session_start();

//checks for username
if(!isset($_SESSION['username'])){
    echo "<p>To add new projects or modify exiting ones <a href="."'register.php'".">Register Here</a> if you are not
    a member or <a href="."'login.php'".">Login Here</a> if you are a member</p>";     
}
else{
    //sets session variables
    $username = $_SESSION['username'];

    $uid = $_SESSION['uid'];

    unset($_SESSION['project']);

    echo "<p> you are now logged in as $username! </p>";

    echo "<a href=" . "'AddProject.php'" . ">Add Project</a><br>";

}

//connects to database
require_once("connectdb.php");

//queries all rows from projects using prepare statement to prevent sql injection
$rows = $db->prepare("SELECT * FROM projects");
//executes the query
$rows->execute();

//checks if form has been submitted
if(isset($_POST['titleSubmitted'])){

    try{
        //search using title
        $titleSearch = $_POST['titleSearch'];
        $titleResult = $db->prepare('SELECT * FROM projects WHERE title=?');
        $titleResult->execute(array($titleSearch));



        //title and date search are separate due to error occuring when string used to query date column
    
        //if a row using title search is found
        if($titleResult->rowCount() > 0){
            //title row of table
            echo "<table cellpadding='5' id='search-table'><tr><th>Title</th><th>Start Date</th><th>Phase</th><th>Description</th><th></th></tr>";
            //loops through row and echos into table
            foreach($titleResult as $item){
                echo "<tr><td>".$item['title']."</td><td>".$item['start_date']."</td><td>".$item['phase']."</td><td>".$item['description']."</td>
                <td><a href=" . "'projectView.php?proj=" . $item["title"] . "'>View Project</a></td></tr>";
            }   
        }
 
        //if no row is found
        else{
            echo "could not find the project you are looking for";
        }
    }
    //if a database error occurs
    catch(PDOexception $ex){
        echo "Error fetching table";
        echo $ex->getmessage();
    }
    

}

if(isset($_POST['dateSubmitted'])){

		try{
        	//search using date
        	$dateSearch = $_POST['dateSearch'];
        	$dateResult = $db->prepare('SELECT * FROM projects WHERE start_date=?');
        	$dateResult->execute(array($dateSearch));
        
        	//if a row using date search is found
        	if($dateResult->rowCount() > 0){
            	echo "<table cellpadding='5' id='search-table'><tr><th>Title</th><th>Start Date</th><th>Phase</th><th>Description</th><th></th></tr>";
            	foreach($dateResult as $item){
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
//starts the projects table
echo "</table><br><br>";
?>

<div class="section" id="table-container">
<table cellpadding = "5" id="projects-table">
    <tr>
        <th>Title</th>
        <th>Start Date<br>YYYY/MM/DD</th>
        <th>Description</th>
        <th></th>
    </tr>
    <?php 
    //loops through the specified rows in the project database and echos them into the table
    foreach($rows as $row){
        echo "<td>" . $row["title"] . "</a></td>";
        echo "<td>" . $row["start_date"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        //uses the title and query strings to generate a link to the project to view in detail on another page
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



















