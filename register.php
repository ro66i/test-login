<?php
    require_once "config.php";

    // Define variables and intialize with empty values
    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";

    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // VALIDATE USERNAME
        if(empty(trim($_POST["username"])))
        {
            $username_err="Please enter username";
        }
        else
        {
            //prepare a select statement
            $sql="SELECT id FROM users WHERE username = ?";

            if($stmt=mysqli_prepare($link, $sql))
            {
                //Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt,"s",$param_username);

                //set parameters
                $param_username = trim($_POST["username"]);

                //Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    // store result
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt) == 1)
                    {
                        $username_err="this username is already taken";
                    }
                    else
                    {
                        $username=trim($_POST["username"]);
                    }
                }
                else
                {
                    echo"OOPs something went wrong. Please try again later";
                }

                //close statement
                mysqli_stmt_close($stmt);
            }
        }

        //validating password
        if(empty(trim($_POST["password"])))
        {
            $password_err="Please enter the password";
        }
        elseif(strlen(trim($_POST["password"])) < 6)
        {
            $password_err="password must have atleast 6 characters";
        }
        else
        {
            $password=trim($_POST["password"]);
        }
        
        //validate confirm password
        if(empty(trim($_POST["confirm_password"])))
        {
            $confirm_password_error="please comfirm password";
        }
        else
        {
            $confirm_password=trim($_POST["confirm_password"]);
            if(empty($confirm_password) && ($password != $confirm_password))
            {
                $confirm_password_err="password did not match";
            }
        }

        
        //check inout errors before inserting in DB
        if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
        {
            //prepare insert statement
            $sql="INSERT into users(username,password) values(?,?)";

            if($stmt = mysqli_prepare($link, $sql))
            {
                //bind variables to the prepared statements
                mysqli_stmt_bind_param($stmt,"ss", $param_username, $param_password);

                //set paramters
                $param_username=$username;
                $param_password=password_hash($password,PASSWORD_DEFAULT);//CREATE PASSWORD HASH

                //Attempt to execute prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    
                    //Redirect to login
                    header("location:login.php");
                }
                else
                {
                    echo "Something went wrong.Please try agai later.";
                }

                //close statement
                mysqli_stmt_close($stmt);
            }
        }

        //close connection
        mysqli_close($link);

    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sign Up</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <style type="text/css">
            body
            {
                font:14px sans-serif;

            }
            .wrapper
            {
                width: 350px;
                padding: 20px;
            }
        </style>
    </head>
    <body>
            <div class="wrapper">
                <h2>Sign Up</h2>
                <p>Please fill this form to create an account.</p>
            
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                
                    <div class="form-group <?php echo(!empty($username_err))? 'has-error':'';?>">
                    
                        <label>Username</label>
                        
                        <input type="text" name="username" class="form-control" value="<?php echo $username;?>">

                            <span class="help-block"><?php echo $username_err; ?></span>

                    </div>

                    <div class="form-group <?php echo(!empty($password_err))? 'has-error':'';?>">
                    
                        <label>Password</label>

                        <input type="text" name="password" class="form-control" value="<?php echo $password?>">

                            <span class="help-block"><?php echo $password_err; ?></span>

                    </div>

                    <div class="form-group <?php echo(!empty($confirm_password_err))? 'has-error':'';?>">
                        
                        <label>Confirm Password</label>
                        
                        <input type="text" name="confirm_password" class="form-control" value="<?php echo $confirm_password?>">

                            <span class="help-block"><?php echo $confirm_password_err; ?></span>

                    </div>

                    <div class="form-group">

                        <input type="submit" class="btn btn-primary" value="submit">
                        
                        <input type="reset" class="btn btn-default" value="reset">

                        <p>Already have an Account?<a href="login.php">Login here</a>
                    
                    </div>

                </form>

            </div>
    </body>
</html>
