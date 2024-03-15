<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$activity_name = "";
$activity_name_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate activity name
    if(empty(trim($_POST["activity_name"]))){
        $activity_name_err = "Please enter an activity name.";
    } else{
        $activity_name = trim($_POST["activity_name"]);
    }
    
    // Check input errors before inserting in database
    if(empty($activity_name_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO activities (activity_name) VALUES (?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_activity_name);
            
            // Set parameters
            $param_activity_name = $activity_name;
            
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