<?php
    //intialize session
    session_start();

    //check if the user is logged in, if not redirect the user to login page.
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php");

        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Welcome</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        
        <style type="text/css">

            body
            {
                font: 14px sans-sarif;
                text-align: center;
            }
            
        </style>
    </head>
    <body>
            <div class="page-header">
            
                <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]);?></b>. Welcome to our website.</h1>
            
                <p>
                    <a href="reset-password.php" class="btn btn-warning">Reset your Password</a>

                    <a href="logout.php" class="btn btn-danger">Sign out of your account</a>

                </p>

            </div>
    </body>
</html>