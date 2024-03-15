<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$activity_id = "";
$activity_id_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate activity id
    if(empty(trim($_POST["activity_id"]))){
        $activity_id_err = "Please enter an activity id.";
    } else{
        $activity_id = trim($_POST["activity_id"]);
    }
    
    // Check input errors before inserting in database
    if(empty($activity_id_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO registrations (user_id, activity_id) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ii", $param_user_id, $param_activity_id);
            
            // Set parameters
            $param_user_id = $_SESSION["id"];
            $param_activity_id = $activity_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to index page
                header("location: index.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>