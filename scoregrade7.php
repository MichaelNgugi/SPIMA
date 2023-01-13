<?php
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";
$filtervalues = "opener";
$value = "mid-term";
$filter = "end-term";

    // data for graph 
    $sql_avg ="SELECT term1.exam_type, AVG(maths) 'Maths', AVG(english) 'English', AVG(swahili) 'Swahili', AVG(science) 'Science', AVG(social) 'Social', AVG(religious) 'Religious', AVG(total) 'Total' FROM term1 INNER JOIN students ON students.id = term1.id WHERE students.grade=7 AND term1.exam_type = 'opener'  
            UNION 
            SELECT term1.exam_type, AVG(maths) 'Maths', AVG(english) 'English', AVG(swahili) 'Swahili', AVG(science) 'Science', AVG(social) 'Social', AVG(religious) 'Religious', AVG(total) 'Total' FROM term1 INNER JOIN students ON students.id = term1.id WHERE students.grade=7 AND term1.exam_type = 'mid-term'
            UNION 
            SELECT term1.exam_type, AVG(maths) 'Maths', AVG(english) 'English', AVG(swahili) 'Swahili', AVG(science) 'Science', AVG(social) 'Social', AVG(religious) 'Religious', AVG(total) 'Total' FROM term1 INNER JOIN students ON students.id = term1.id WHERE students.grade=7 AND term1.exam_type = 'end-term'";
    $gradedata = array();
    $row = [];
    $output = $link->query($sql_avg);
    while ($res = mysqli_fetch_assoc($output)) { 
        $gradedata[] = $res;
    }

  //write to json file
  $fp = fopen('grade7data.json', 'w');
  fwrite($fp, json_encode($gradedata));
  fclose($fp);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Grade 7 Performance Report</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="myStyle.css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    </head>
    <body>
    <div class="container-fluid text-center">
                <h2 >Performance Report for Grade 7</h2>
            </div>
          <br>
        <div class="container" id="score">
            <h3><center>SPIMA Performance Report</center></h3>
            <h3><center>Year 2022</center></h3>
            <div class="row text-center">
                    <div class="col-sm" style="border: 1px solid black;"><h3>Grade 7</h3></div>
                </div> <hr>
            <table class="table table-bordered table-hover">
              <thead class="thead-dark">
                <tr><th colspan="3">Term 1 2022 Subject performance</th></tr>
              </thead>
                <thead class="thead-light" style="letter-spacing: 0.01px;">
                  <tr>
                    <th scope="col">Exam Period</th>
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
                <tr>
                    <th scope="col">Opener</th>
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
                  <tr>
                    <th scope="col">Mid Term</th>
                    <th scope="col">
                        <?php
                        $result = $link->query("SELECT AVG(maths) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$value' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(maths)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(english) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$value' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(english)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(swahili) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$value' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(swahili)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(science) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$value' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(science)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(social) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$value' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(social)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(religious) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$value' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(religious)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(total) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$value' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(total)'];
                        }
                        ?>
                    </th>
                  </tr>
                  <tr>
                    <th scope="col">End Term</th>
                    <th scope="col">
                        <?php
                        $result = $link->query("SELECT AVG(maths) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filter' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(maths)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(english) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filter' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(english)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(swahili) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filter' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(swahili)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(science) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filter' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(science)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(social) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filter' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(social)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(religious) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filter' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(religious)'];
                        }
                        ?>
                    </th>
                    <th scope="col">
                    <?php
                        $result = $link->query("SELECT AVG(total) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='$filter' AND students.grade=7");
                        //display data on web page
                        while($row = mysqli_fetch_array($result)){
                            echo  $row['AVG(total)'];
                        }
                        ?>
                    </th>
                  </tr>
                </tbody>
                </table>
                <hr>
                <div class="container" id="chart-container" style="width: 80%;">
                <canvas id="mycanvas"></canvas> <hr>
                <canvas id="mycanvas2"></canvas>
              </div>
          </div>
                
          <div class="container text-center">
            <button id="download" class="btn btn-success">Export to PDF</button>
            <hr>
          </div>
      </body>
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
          $.ajax({
            url: "grade7data.json",
            method: "GET",
            success: function(gradedata) {
              console.log(gradedata);
              var exam = [];
              var maths = [];
              var english = [];
              var swahili = [];
              var science = [];
              var social = [];
              var religious = [];
              var total = [];

              for(var i in gradedata) {
                exam.push(gradedata[i].exam_type +" exam");
                maths.push(gradedata[i].Maths);
                english.push(gradedata[i].English);
                swahili.push(gradedata[i].Swahili);
                science.push(gradedata[i].Science);
                social.push(gradedata[i].Social);
                religious.push(gradedata[i].Religious);
                total.push(gradedata[i].Total);
              }

              var ctx = document.getElementById("mycanvas").getContext('2d');
              // draw background
              var backgroundColor = 'white';
                Chart.plugins.register({
                    beforeDraw: function(c) {
                        var ctx = c.chart.ctx;
                        ctx.fillStyle = backgroundColor;
                        ctx.fillRect(0, 0, c.chart.width, c.chart.height);
                    }
                });

                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: exam ,
                            datasets: [
                            {
                                label: "Total Score",
                                backgroundColor:['#1D8348', '#0E6655', '#21618C'] ,
                                data: total,
                            }
                            ]
                        },
                        options: {
                            responsive: true,
                            legend: {
                            display: true,
                            position: 'bottom',
    
                            labels: {
                                fontColor: '#71748d',
                                fontFamily: 'Circular Std Book',
                                fontSize: 14,
                            }
                        },
                        title: {
                            display: true,
                            text: "Average Class Score Performance Term1"
                        },
                        scales: {
                              yAxes: [{
                                  ticks: {
                                    stepSize: 50,
                                    max: 500,
                                    beginAtZero: true,}
                              }]
                            }
    
    
                    }
                    });
            
                    var ctx2 = document.getElementById("mycanvas2").getContext('2d');
                    var myBarChart = new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: exam ,
                            datasets: [
                            {
                                label: "Maths",
                                backgroundColor: "purple",
                                data: maths,
                            },
                            {
                                label: "English",
                                backgroundColor: "blue",
                                data: english,
                            },
                            {
                                label: "Swahili",
                                backgroundColor: "yellow",
                                data: swahili,
                            },
                            {
                                label: "Science",
                                backgroundColor: "orange",
                                data: science,
                            },
                            {
                                label: "Social",
                                backgroundColor: "green",
                                data: social,
                            },
                            {
                                label: "Religious",
                                backgroundColor: "brown",
                                data: religious,
                            }
                            ]
                        },
                        options: {
                            responsive: true,
                            legend: {
                            display: true,
                            position: 'bottom',
    
                            labels: {
                                fontColor: '#71748d',
                                fontFamily: 'Circular Std Book',
                                fontSize: 14,
                            }
                        },
                        title: {
                            display: true,
                            text: "Average Class Subject Score Performance Term1"
                        },
                        scales: {
                              yAxes: [{
                                  ticks: {
                                    stepSize: 10,
                                    max: 100,
                                    beginAtZero: true,}
                              }]
                            }
    
    
                    }
                    });

            },
            error: function(gradedata) {
              var jsDataPlaceholder = <?= json_encode($gradedata); ?>;
              console.log(jsDataPlaceholder);
            }
          });
        });
    </script>
    <script>
       var doc = new jspdf.jsPDF({orientation:'p', unit:'pt', format: 'a4'});
          margin = 30; // narrow margin - 12.7 mm
          let srcwidth = document.getElementById('score').scrollWidth;
          let scale = (595.28 - margin * 2) / srcwidth; // a4 pageSize 595.28
      function downloadPDFWithjsPDF() {

          doc.html(document.querySelector('#score'), {
            html2canvas: {
                  scale: scale, // default is window.devicePixelRatio,
                  letterRendering:true 
            },
            callback: function (doc) {
              doc.save('Grade 7 Score Report.pdf');
            },
            x: margin,
            y: margin,
          });
        }

        document.querySelector('#download').addEventListener('click', downloadPDFWithjsPDF);
    </script>
</html>