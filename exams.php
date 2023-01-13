<?php
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";
$sql = "SELECT * from students INNER JOIN term1 ON students.id = term1.id";
$result = ($link->query($sql));
//declare array to store the data of database
$row = [];

if ($result->num_rows > 0)
  {
    // fetch all data from db into array
    $row = $result->fetch_all(MYSQLI_ASSOC);
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
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
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
                <a class="navbar-brand" href="welcome.php">
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
                <h2>Exam Result sheets</h2>
                <div class="row">
                    <div class="col-md-7">
                        <form action="" method="get">
                            <div class="input-group mb-4">
                                <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control" placeholder="Student's name">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-25">
                      <a class="btn btn-success" href="addScore.php"><i class="fa fa-plus"></i>  Add New Score</a>
                    </div>
                </div>
                <div class="row">
                    <h4>Year 2022</h4>
                    <div class="col-sm-4">
                    <button type="button" class="btn btn-secondary btn-lg btn-block">Term 1</button>
                    </div>
                </div>
            </div>
          <br>
        <div class="container">
            <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Student ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Exam type</th>
                    <th scope="col">Maths</th>
                    <th scope="col">English</th>
                    <th scope="col">Swahili</th>
                    <th scope="col">Science</th>
                    <th scope="col">Social Studies</th>
                    <th scope="col">Religious Education</th>
                    <th scope="col">Total Score</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                require_once "config.php";
                if(isset($_GET['search']))
                {
                  $filtervalues = $_GET['search'];
                  $query = "SELECT * from students INNER JOIN term1 ON students.id = term1.id WHERE CONCAT(name,grade) LIKE '%$filtervalues%' ";
                  $query_run = mysqli_query($link, $query);

                  if(mysqli_num_rows($query_run) > 0)
                  {
                    foreach($query_run as $items)
                    {
                      ?>
                        <tr>   
                          <td><?= $items['examid']; ?></td>
                          <td><?= $items['id']; ?></td>
                          <td><?= $items['name']; ?></td>
                          <td><?= $items['grade']; ?></td>
                          <td><?= $items['exam_type']; ?></td>
                          <td><?= $items['maths']; ?></td>
                          <td><?= $items['english']; ?></td>
                          <td><?= $items['swahili']; ?></td>
                          <td><?= $items['science']; ?></td>
                          <td><?= $items['social']; ?></td>
                          <td><?= $items['religious']; ?></td>
                          <td><?= $items['total']; ?></td>
                          <td>
                            <a href="editscore.php?edit=<?php echo $items['examid']; ?>" class="edit_btn" >Edit</a>
                          </td>
                          </tr>
                          <?php
                     }
                  }
                  else
                  {
                    ?>
                      <tr>
                      <td colspan="4">No Record Found</td>
                      </tr>
                    <?php
                  }
                }else{
                if(!empty($row))
                    foreach($row as $rows)
                    {
                      ?>
                      <tr>
                          <td><?php echo $rows['examid']; ?></td>
                          <td><?php echo $rows['id']; ?></td>
                          <td><?php echo $rows['name']; ?></td>
                          <td><?php echo $rows['grade']; ?></td>
                          <td><?php echo $rows['exam_type']; ?></td>
                          <td><?php echo $rows['maths']; ?></td>
                          <td><?php echo $rows['english']; ?></td>
                          <td><?php echo $rows['swahili']; ?></td>
                          <td><?php echo $rows['science']; ?></td>
                          <td><?php echo $rows['social']; ?></td>
                          <td><?php echo $rows['religious']; ?></td>
                          <td><?php echo $rows['total']; ?></td>
                          <td>
                            <!-- Gets the Student ID to use for editing the score -->
                            <a href="editscore.php?edit=<?php echo $rows['examid']; ?>" class="edit_btn" >Edit</a>
                          </td>
                      </tr>
                      <?php
                    }
                  }
                  ?> 
                </tbody>
              </table>
          </div>
    </body>
</html>