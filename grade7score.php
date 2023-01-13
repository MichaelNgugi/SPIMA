<?php
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

  require_once "config.php";
  $filtervalues = "";
  if(isset($_POST['exam-period'])){
    $filtervalues = $_POST['exam-period'];
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
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
                <h2>Grade 7 Exam Result sheets</h2>
                <div class="row">
                </div>
            </div>
          <br>
        <div class="container">
            <h2>Term 1 Scores</h2>
            <div class="col-md-7">
              <form onsubmit="" method="post">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <label class="input-group-text" for="exam-type">Choose Examination Period </label>
                  </div>
                  <select name="exam-period" id="exam-type">
                    <option value="Select Exam period">Select Period...</option>
                      <option value="opener">Opener</option>
                      <option value="mid-term">Mid-term</option>
                      <option value="end-term">End-term</option>
                  </select>
                  <button type="submit" class="btn btn-primary">  Search</button>
                </div>
              </form>
            </div>

            <table class="table table-bordered table-hover" id="classcore">
            <thead class="thead-dark">
                <tr><th colspan="3">Grade 7 Term 1 2022 <?php echo $filtervalues;?></th></tr>
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Student ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Maths</th>
                    <th scope="col">English</th>
                    <th scope="col">Swahili</th>
                    <th scope="col">Science</th>
                    <th scope="col">Social Studies</th>
                    <th scope="col">Religious Education</th>
                    <th scope="col">Total Score</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                if(isset($_POST['exam-period']))
                {
                  $filtervalues = $_POST['exam-period'];
                  $query = "SELECT students.id, students.name, students.grade,
                  term1.exam_type, term1.maths, term1.english, term1.swahili,
                  term1.science, term1.social, term1.religious, term1.total from students INNER JOIN term1 ON students.id = term1.id WHERE students.grade = 7 AND term1.exam_type = '$filtervalues' ORDER BY term1.total DESC";
                  $query_run = mysqli_query($link, $query);

                  if(mysqli_num_rows($query_run) > 0)
                  {
                    foreach($query_run as $items)
                    {
                      ?>
                        <tr>   
                          <td><?= $items['id']; ?></td>
                          <td><?= $items['name']; ?></td>
                          <td><?= $items['maths']; ?></td>
                          <td><?= $items['english']; ?></td>
                          <td><?= $items['swahili']; ?></td>
                          <td><?= $items['science']; ?></td>
                          <td><?= $items['social']; ?></td>
                          <td><?= $items['religious']; ?></td>
                          <td><?= $items['total']; ?></td>
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
                }?> 
                </tbody>
                <thead class="thead-light">
                  <tr>
                    <th colspan="2">Class Average Score</th>
                    <th scope="col">
                        <?php
                        $result = $link->query("SELECT AVG(maths) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filtervalues' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(maths)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(english) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filtervalues' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(english)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(swahili) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filtervalues' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(swahili)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(science) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filtervalues' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(science)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(social) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filtervalues' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(social)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(religious) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filtervalues' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(religious)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(total) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filtervalues' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(total)'];
                        }
                        ?>
                    </th>
                  </tr>
                </thead>
              </table>
              <div class="container text-center">
                <button id="download" class="btn btn-success">Download/Print Score</button>
              </div>
          </div>
          <script>
              function downloadPDFWithjsPDF() {
                  var doc = new jspdf.jsPDF({orientation:'p', unit:'pt', format: 'a4'});
                  margin = 30; // narrow margin - 12.7 mm
                  let srcwidth = document.getElementById('classcore').scrollWidth;
                  let scale = (595.28 - margin * 2) / srcwidth; // a4 pageSize 595.28

                  doc.html(document.querySelector('#classcore'), {
                    html2canvas: {
                          scale: scale, // default is window.devicePixelRatio,
                          letterRendering:true 
                    },
                    callback: function (doc) {
                      doc.save('Grade 7 <?php echo $filtervalues ?> 2022 Score Report.pdf');
                    },
                    x: margin,
                    y: margin,
                  });
                }

                document.querySelector('#download').addEventListener('click', downloadPDFWithjsPDF);
            </script>
    </body>
</html>
