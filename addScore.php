<?php
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
    require_once "config.php";
    $exam_err = $success = "";
  
    $sql = "SELECT * FROM students";
    $all_student = mysqli_query($link,$sql);

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $id = mysqli_real_escape_string($link,$_POST['Student']);
        $exam = $_POST['exam-period'];
        $maths = $_POST['maths'];
        $english = $_POST['english'];
        $swahili = $_POST['swahili'];
        $science = $_POST['science'];
        $social = $_POST['social'];
        $religious = $_POST['religious'];

         // Validate examination period to prevent duplicates
            // Prepare a select statement
            $sql_check = "SELECT * FROM term1 WHERE id= ? AND exam_type =?";
            
            if($stmt_2 = mysqli_prepare($link, $sql_check)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt_2, "is", $param_id, $param_exam);
                
                // Set parameters
                $param_id = $id;
                $param_exam = $_POST['exam-period'];
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt_2)){
                    /* store result */
                    mysqli_stmt_store_result($stmt_2);
                    
                    if(mysqli_stmt_num_rows($stmt_2) == 1){
                        $exam_err = "This student has already taken this exam.";
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt_2);
            }

        if(empty($exam_err)){
            $sql_insert = "INSERT INTO term1 (id, exam_type, maths, english, swahili, science, social, religious) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            if($stmt = mysqli_prepare($link, $sql_insert)){
                mysqli_stmt_bind_param($stmt, "isiiiiii", $id, $exam, $maths, $english, $swahili, $science, $social, $religious);

                if(mysqli_stmt_execute($stmt))
                {
                    $success = '<div class="alert alert-success"><strong>Succesfully</strong> added scores. Proceed to exam Page to view scores</div>';
                }else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt);
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
        <h2>Add New Student Score</h2>
        <hr>
        <h4>Fill in the form to add score for a student</h4>
                        

        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <?php echo $success; ?>   
                    </div>
                </div>  
                <div class="form-group">
                    <label>Student's Name</label>
                    <select name="Student">
                        <?php
                            while ($stud = mysqli_fetch_array(
                                    $all_student,MYSQLI_ASSOC)):;
                        ?>
                            <option value="<?php echo $stud["id"];
                                // The value we usually set is the primary key
                            ?>">
                                <?php echo $stud["name"];
                                    // To show the category name to the user
                                ?>
                            </option>
                        <?php
                            endwhile;
                        ?>
                    </select>
                </div>    
                <div class="form-group">
                    <label>Examination Period</label>
                    <select name="exam-period" id="exam-type">
                        <option value="opener">Opener</option>
                        <option value="mid-term">Mid-term</option>
                        <option value="end-term">End-term</option>
                    </select>
                    <span class="invalid-feedback"><?php echo $exam_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Mathematics</label>
                    <input type="number" name="maths" class="form-control" placeholder="Score out of 100" min="0" max="100" required>
                </div>     
                <div class="form-group">
                    <label>English</label>
                    <input type="number" name="english" class="form-control" placeholder="Score out of 100" min="0" max="100" required>
                </div>  
                <div class="form-group">
                    <label>Swahili</label>
                    <input type="number" name="swahili" class="form-control" placeholder="Score out of 100" min="0" max="100" required>
                </div>    
                <div class="form-group">
                    <label>Science</label>
                    <input type="number" name="science" class="form-control" placeholder="Score out of 100" min="0" max="100" required>
                </div>       
                <div class="form-group">
                    <label>Social Studies</label>
                    <input type="number" name="social" class="form-control" placeholder="Score out of 60" min="0" max="60" required>
                </div>  
                <div class="form-group">
                    <label>Religious Education</label>
                    <input type="number" name="religious" class="form-control" placeholder="Score out of 30" min="0" max="30" required>
                </div>  
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
                <a class="btn btn-warning" href="exams.php">Back to Exams page</a>
            </form>
        </div>
    </div>
    </body>
</html>