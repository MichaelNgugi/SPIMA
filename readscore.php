<?php
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

$stud_id = $_GET["id"];
$name ="";
$grade= "";

$query_result = ($link->query("SELECT * from students WHERE id='$stud_id'"));
//declare array to store the data of database
$m_row = [];

if ($query_result->num_rows > 0)
  {
    // fetch all data from db into array
    $m_row = mysqli_fetch_array($query_result, MYSQLI_ASSOC);
    $name = $m_row["name"];
      $grade = $m_row["grade"];
  }

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    
  $sql = "SELECT * from students INNER JOIN term1 ON students.id = term1.id WHERE students.id = '$stud_id'";

  $result = ($link->query($sql));
  //declare array to store the data of database
  $row = [];

  if ($result->num_rows > 0)
    {
      // fetch all data from db into array
      $row = $result->fetch_all(MYSQLI_ASSOC);      
    }

  //sql for storing graph data to Json file
  $output = ($link->query("SELECT exam_type, total from students INNER JOIN term1 ON students.id = term1.id WHERE students.id = '$stud_id'"));
  $data = array();
  while ($res = mysqli_fetch_assoc($output)) { 
    $data[] = $res;
  }

  //write to json file
  $fp = fopen('data.json', 'w');
  fwrite($fp, json_encode($data));
  fclose($fp);

  }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $name; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="myStyle.css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    </head>
    <body>
    <div class="container-fluid text-center">
                <h2 >Score Report for <?php echo $name; ?></h2>
                <div class="row">
                </div>
            </div>
          <br>
        <div class="container" id="myscore">
        <h3><center>SPIMA Performance Report</center></h3>
        <h3><center>Year 2022</center></h3><br>
          <div class="row text-center">
                    <div class="col-sm" style="border: 1px solid black;"><h3>Student ID:</h3> <h3><?php echo $stud_id; ?></h3></div>
                    <div class="col-sm" style="border: 1px solid black;"><h3>Name:</h3> <h3 style="letter-spacing: 0.01px;"><?php echo $name; ?></h3></div>
                    <div class="col-sm" style="border: 1px solid black;"><h3>Grade:</h3> <h3><?php echo $grade; ?></h3></div>
            </div>
            <hr>
            <table class="table table-bordered table-hover">
              <thead class="thead-dark">
                <tr><th colspan="3">Term 1 2022</th></tr>
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
                    <th scope="col">Comments</th>
                  </tr>
                </thead>
                <tbody id="data">
                <?php
                if(!empty($row)){
                    foreach($row as $rows)
                    {
                      ?>
                      <tr>
                          <td><?php echo $rows['exam_type']; ?></td>
                          <td><?php echo $rows['maths']; ?></td>
                          <td><?php echo $rows['english']; ?></td>
                          <td><?php echo $rows['swahili']; ?></td>
                          <td><?php echo $rows['science']; ?></td>
                          <td><?php echo $rows['social']; ?></td>
                          <td><?php echo $rows['religious']; ?></td>
                          <td><?php echo $rows['total']; ?></td>
                          <td>
                            <script>
                              var total = <?php echo $rows['total']; ?>;
                              var comment = "";
                              if(total > 390 ) {
                                comment = "Excellent";
                              }
                              else if(total > 340) {
                                comment = "Good";
                              }
                              else if(total > 300) {
                                comment = "Average";
                              }
                              else if(total > 0){
                                comment = "Fail";
                              }
                              document.write(comment);
                            </script>
                          </td>
                      </tr>
                      <?php
                    }
                  }else {
                    ?>
                        <tr>
                        <td colspan="4">No Record Found</td>
                        </tr>
                      <?php
                  }
                  ?> 
                </tbody>
              </table>

              <table class="table table-bordered table-hover" style="letter-spacing: 0.01px;">
              <thead class="thead-dark">
                <tr><th colspan="4">Outcomes compared with Class Average</th></tr>
              </thead>
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Exam Period</th>
                    <th scope="col">Class Average Score</th>
                  </tr>
                </thead>
                <tbody>
                      <tr>
                        <td>Opener</td>
                          <td>
                            <?php 
                              $avgtotal = $link->query("SELECT AVG(total) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='opener' AND students.grade='$grade'");
                              //display data on web page
                              while($exc = mysqli_fetch_array($avgtotal)){
                                  echo  $exc['AVG(total)'];
                                  $avrg1 = $exc['AVG(total)'];
                              }
                            ?>
                          </td>
                        </tr>
                        <tr>
                        <td>Mid Term</td>
                          <td>
                            <?php 
                              $avgtotal = $link->query("SELECT AVG(total) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type = 'mid-term' AND students.grade='$grade'");
                              //display data on web page
                              while($exc = mysqli_fetch_array($avgtotal)){
                                  echo  $exc['AVG(total)'];
                                  $avrg2 = $exc['AVG(total)'];
                              }
                            ?>
                          </td>
                        </tr>
                        <tr>
                        <td>End Term</td>
                          <td>
                            <?php 
                              $avgtotal = $link->query("SELECT AVG(total) FROM term1 INNER JOIN students ON students.id = term1.id WHERE term1.exam_type ='end-term' AND students.grade='$grade'");
                              //display data on web page
                              while($exc = mysqli_fetch_array($avgtotal)){
                                  echo  $exc['AVG(total)'];
                                  $avrg3 = $exc['AVG(total)'];
                              }
                            ?>
                          </td>
                        </tr>
                </tbody>
              </table>
              <hr>
              <div id="chart-container" style="width: 80%;  margin: auto;">
                <canvas id="mycanvas"></canvas>
              </div>
              <hr>
          </div>
          <div class="container text-center">
            <button id="download" class="btn btn-success">Export to PDF</button>
            <hr>
                <div class="card text-center" style="width: 100%;">
                <h5 class="card-header">Send Report through Email</h5>
                <div class="card-body justify-content-center">
                <form enctype="multipart/form-data" method="POST" action="email.php" >
                  <div class="form-group">
                      <input class="form-control" type="text" name="sender_name" placeholder="Your Name" required/>
                  </div>
                  <div class="form-group">
                      <input class="form-control" type="email" name="sender_email" placeholder="Recipient's Email Address" required/>
                  </div>
                  <div class="form-group">
                      <input class="form-control" type="text" name="subject" placeholder="Subject"/>
                  </div>
                  <div class="form-group">
                      <textarea class="form-control" name="message" placeholder="Message"></textarea>
                  </div>
                  <div class="form-group">
                      <input class="form-control" type="file" name="attachment" id="attach"  required/>
                  </div>
                  <div class="form-group">
                      <input class="btn btn-primary" type="submit" name="button" value="Submit" />
                  </div>           
                </form>
                </div>
              </div>
          </div>
            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
      </body>
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <!-- Graph Visualization  -->
    <script type="text/javascript">
      $(document).ready(function(){
          $.ajax({
            url: "data.json",
            method: "GET",
            success: function(data) {
              console.log(data);
              var exam = [];
              var total = [];

              for(var i in data) {
                exam.push(data[i].exam_type +" exam");
                total.push(data[i].total);
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
                            datasets: [{
                              label: 'Exam Score',
                              order: 2,
                                backgroundColor:['#1D8348', '#0E6655', '#21618C'],
                                data: total
                            },
                            {
                              type: 'line',
                              order: 1,
                              fill: false,
                              pointStyle: 'circle',
                              pointRadius: 10,
                              pointHoverRadius: 15,
                              label: 'Average Class Score',
                              backgroundColor: '#000000',
                              borderColor: '#000000', 
                              data: [<?php echo $avrg1;?>, <?php echo $avrg2;?>, <?php echo $avrg3;?>]
                           }]
                        },
                        options: {
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
                            text: "<?php echo $name?> Score Performance for Term1"
                        },
                        scales: {
                              yAxes: [{
                                  ticks: {
                                    stepSize: 50,
                                    max: 500,
                                    beginAtZero: true,},
                              }],
                              xAxes: [{
                                barPercentage: 0.4
                            }]
                          }
    
    
                    }
                    });

            },
            error: function(data) {
              var jsDataPlaceholder = <?= json_encode($data); ?>;
              console.log(jsDataPlaceholder);
            }
          });
        });
    </script>
    <script>
       var doc = new jspdf.jsPDF({orientation:'p', unit:'pt', format: 'a4'});
          margin = 30; // narrow margin - 12.7 mm
          let srcwidth = document.getElementById('myscore').scrollWidth;
          let scale = (595.28 - margin * 2) / srcwidth; // a4 pageSize 595.28
      function downloadPDFWithjsPDF() {

          doc.html(document.querySelector('#myscore'), {
            html2canvas: {
                  scale: scale, // default is window.devicePixelRatio,
                  letterRendering:true 
            },
            callback: function (doc) {
              doc.save('<?php echo $name ?> Score Report.pdf');
            },
            x: margin,
            y: margin,
          });
        }

        document.querySelector('#download').addEventListener('click', downloadPDFWithjsPDF);
    </script>
</html>
