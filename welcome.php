<?php
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>SPIMA</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="myStyle.css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body id="welcome" data-spy="scroll" data-target=".navbar" data-offset="60">

        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>                        
                </button>
                <a class="navbar-brand" href="#welcome">
                  <div style="display:inline-block;vertical-align:text-top;">
                 </div>
                   <div style="display: inline-block;vertical-align:top;">
                    SPIMA
                   </div>
                </a>
              </div>
              <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                  <li><a href="student.php">STUDENTS</a></li>
                  <li><a href="exams.php">EXAMS</a></li>
                  <li><a href="logout.php">Sign Out</a></li>
                </ul>
              </div>
            </div>
          </nav>
          
          <div class="container-fluid text-center">
            <h1>Welcome
              <?php echo htmlspecialchars($_SESSION["username"]); ?> 
            </h1>
          </div>

          <div class="container text-center">
            <h2>Outcome entry</h2>
            <h2>Year: 2022</h2>
            <p>Insert Scores of each student here:</p>
            <a class="btn btn-primary" href="addScore.php" role="button">Term 1</a>
          </div>

          <div class="container text-center">
            <h2>Grade</h2>
            <div class="row slideanim">
              <div class="col-sm-4">
                <h3 style="text-decoration:underline ;">Grade 6</h3>
                <h5><a class="btn btn-primary" href="grade6score.php" role="button">View Scores</a></h5>
                <h5><a class="btn btn-primary" href="scoregrade6.php" role="button" target="blank" rel="noopener noreferrer">Performance Reports</a></h5>
              </div>
              <div class="col-sm-4">
                <h3 style="text-decoration:underline ;">Grade 7</h3>
                <h5><a class="btn btn-primary" href="grade7score.php" role="button">View Scores</a></h5>
                <h5><a class="btn btn-primary" href="scoregrade7.php" role="button" target="blank" rel="noopener noreferrer">Performance Reports</a></h5>
              </div>
              <div class="col-sm-4">
                <h3 style="text-decoration:underline ;">Grade 8</h3>
                <h5><a class="btn btn-primary" href="grade8score.php" role="button">View Scores</a></h5>
                <h5><a class="btn btn-primary" href="scoregrade8.php" role="button" target="blank" rel="noopener noreferrer">Performance Reports</a></h5>
              </div>
            </div>
          </div>
    </body>
</html>