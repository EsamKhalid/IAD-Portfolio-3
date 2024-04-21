<?php
	session_start();
	session_destroy();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet"   href="https://fonts.googleapis.com/css?family=Montserrat">
    </head>
    <body>
        <div class="section-stack" id="projectView-container">
			<H2> Logged out now! </H2> 
			<a href="login.php">Log in</a>
			<br><br>
			<a href="projects.php">Return to project view</a>
		</div>
	</body>
</html>



