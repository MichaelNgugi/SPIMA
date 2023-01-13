<?php
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";
 
// Define variables and initialize with empty values
$id = $name = $grade = "";
$name_err = $grade_err = $id_err = "";
$success = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if id is empty
    if(empty(trim($_POST["id"]))){
        $id_err = "Please enter Student's id.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM students WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["id"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $id_err = "This student id is already in use.";
                } else{
                    $id = trim($_POST["id"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Check if name is empty
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter the Student's name.";
    } else{
        $name = trim($_POST["name"]);
    }

     // Check if grade is empty
     if(empty(trim($_POST["grade"]))){
        $grade_err = "Please enter the Student's grade.";
    } else{
        $grade = trim($_POST["grade"]);
    }

        // Check input errors before inserting in database
        if(empty($id_err) && empty($name_err) && empty($grade_err)){
        
            // Prepare an insert statement
            $sql = "INSERT INTO students (id, name, grade) VALUES (?, ?, ?)";
             
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "isi", $param_id ,$param_name, $param_grade);
                
                // Set parameters
                $param_id = $id;
                $param_name = $name;
                $param_grade = $grade;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    $success = '<div class="alert alert-success"><strong>Succesfully</strong> added new student. Proceed to Students Page to view </div>';
                    $id = false;
                    $name = false;
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
    
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    
    // Close connection
    mysqli_close($link);
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
        <h2>Add New Student</h2>
        <hr>
        <h4>Fill in the form to add a new student</h4>
                        

        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <?php echo $success; ?>   
                    </div>
                </div>  
                <div class="form-group">
                    <label>Student's ID</label>
                    <input type="text" name="id" maxlength="4" placeholder="Enter 4 digits" class="form-control 
                    <?php echo (!empty($id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $id; ?>" onkeypress="return onlyNumberKey(event)">
                    <span class="invalid-feedback"><?php echo $id_err; ?></span>
                </div>    
                <div class="form-group">
                    <label>Student's Name</label>
                    <input type="text" name="name" placeholder="Enter Students name" class="form-control 
                    <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>" pattern="^[a-zA-Z ]+$">
                    <span class="invalid-feedback"><?php echo $name_err; ?></span>
                </div>
                <div class="form-group">
                <label>Student's Grade: </label>
                    <select name="grade">
                        <option value="Select">Select Grade</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                    </select>
                </div>               
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit" onClick="clearform()">
                </div>
                <a class="btn btn-warning" href="student.php">Back to Student page</a>
            </form>
        </div>
    </div>
    </body>
</html>

<script>
    //checks if input in ID field is a number
        function onlyNumberKey(evt) {
              
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        }
    </script>