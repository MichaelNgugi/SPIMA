<?php 
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";
$message = "";
$update= false;
$name ="";
$exam = $maths= $english= $swahili= $science= $social= $religious= $total ="";


	if (isset($_GET['edit'])) {
		$examid = $_GET['edit'];
		$update = true;
		$record = ($link->query("SELECT * FROM term1 WHERE examid='$examid'"));

        $n = [];

        if ($record->num_rows > 0)
        {
            // fetch all data from db into array
            $n = mysqli_fetch_array($record, MYSQLI_ASSOC);

            $exam = $n['exam_type'];
			$maths = $n['maths'];
            $english = $n['english'];
            $swahili = $n['swahili'];
            $science = $n['science'];
            $social = $n['social'];
            $religious = $n['religious'];
        }

        $query_result = ($link->query("SELECT * from students INNER JOIN term1 ON students.id = term1.id WHERE examid='$examid'"));
        //declare array to store the data of database
        $m_row = [];

        if ($query_result->num_rows > 0)
        {
            // fetch all data from db into array
            $m_row = mysqli_fetch_array($query_result, MYSQLI_ASSOC);
            $id = $m_row["id"];
            $name = $m_row["name"];
        }

        if (isset($_POST['update'])) {
            $maths = $_POST['maths'];
            $english = $_POST['english'];
            $swahili = $_POST['swahili'];
            $science = $_POST['science'];
            $social = $_POST['social'];
            $religious = $_POST['religious'];
        
            $sql_update ="UPDATE term1 SET maths='$maths', english='$english', swahili='$swahili', science='$science', social='$social', religious='$religious' WHERE id=$id AND exam_type='$exam'" ;
            if ($link->query($sql_update) === TRUE) {
                $message = '<div class="alert alert-success"><strong>Succesfully Updated</strong> scores. Proceed to exam Page to view scores</div>';;
            } else {
                echo "Error: ".$link->error;
            }
            
        }
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
    <body>
    <div class="container">
        <h2>Edit Student Score</h2>
        <hr>
        <h4>Fill in the form to edit the score for a student</h4>
        <div class="container">
            <form action="" method="post">
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <?php echo $message; ?>   
                    </div>
                </div>  
                <div class="form-group">
                    <label>Student's Name</label>
                    <input type="text" name="id" value="<?php echo $id; ?>">
                    <input type="text" name="id" value="<?php echo $name; ?>">
                </div>    
                <div class="form-group">
                    <label>Examination Period</label>
                    <input type="text" name="exam-period" value="<?php echo $exam; ?>">
                </div>
                <div class="form-group">
                    <label>Mathematics</label>
                    <input type="number" name="maths" value="<?php echo $maths; ?>" class="form-control" placeholder="Score out of 100" min="0" max="100" required>
                </div>     
                <div class="form-group">
                    <label>English</label>
                    <input type="number" name="english" value="<?php echo $english; ?>" class="form-control" placeholder="Score out of 100" min="0" max="100" required>
                </div>  
                <div class="form-group">
                    <label>Swahili</label>
                    <input type="number" name="swahili" value="<?php echo $swahili; ?>" class="form-control" placeholder="Score out of 100" min="0" max="100" required>
                </div>    
                <div class="form-group">
                    <label>Science</label>
                    <input type="number" name="science" value="<?php echo $science; ?>" class="form-control" placeholder="Score out of 100" min="0" max="100" required>
                </div>       
                <div class="form-group">
                    <label>Social Studies</label>
                    <input type="number" name="social" value="<?php echo $social; ?>" class="form-control" placeholder="Score out of 60" min="0" max="60" required>
                </div>  
                <div class="form-group">
                    <label>Religious Education</label>
                    <input type="number" name="religious" value="<?php echo $religious; ?>" class="form-control" placeholder="Score out of 30" min="0" max="30" required>
                </div>  
                <div class="form-group">
                <?php if ($update == true): ?>
                    <button class="btn btn-primary" type="submit" name="update" >Update</button>
                <?php else: ?>
                    <input type="submit" class="btn btn-primary" value="Submit">
                <?php endif ?>
                </div>
                <a class="btn btn-warning" href="exams.php">Back to Exams page</a>
            </form>
        </div>
    </div>
    </body>
</html>