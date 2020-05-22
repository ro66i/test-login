<?php
    //initialize the session
    session_start();

    //checking if the user is logged in, if yes then redirect the user to welcome page.
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
    {
        header("location: welcome.php");
        exit;
    }

    //include config file
    require_once "config.php";

    //define variables and intialize with empty values
    $username = $password = "";
    $username_err = $password_err = "";

    //processing form data when data is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //check if username is empty
        if(empty(trim($_POST["username"])))
        {
            $username_err="Please enter username.";
        }
        else
        {
            $username = trim($_POST["username"]);
        }

        //check if password is empty
        if(empty(trim($_POST["password"])))
        {
            $password_err="Please enter password";
        }
        else
        {
            $password = trim($_POST["password"]);
        }

        //validate credentials
        if(empty($username_err) && empty($password_err))
        {
            //prepare a select statement
            $sql = "SELECT id, username, password FROM users WHERE username =?";

            if($stmt = mysqli_prepare($link, $sql))
            {
                //bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt,"s",$param_username);

                //Set Parameters
                $param_username = $username;

                //Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    //store result
                    mysqli_stmt_store_result($stmt);

                    //check if username exists, if yes then verify password
                    if(mysqli_stmt_num_rows($stmt) == 1)
                    {
                        //bind result variables
                        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

                        if(mysqli_stmt_fetch($stmt))
                        {
                            if(password_verify($password, $hashed_password))
                            {
                                //password is correct, so start new session
                                session_start();

                                //store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;

                                //redirect to welcome page
                                header("location: welcome.php");
                            }
                            else
                            {
                                //Display error message if password is not valid
                                $password_err="the password you entered is not valid";
                            }
                        }
                        
                    }
                    else
                    {
                        //Display an error message if username doesn't exist
                        $username_err="No account found with that username";
                    }
                    
                }
                else
                {
                    echo"OOPs! Something went wrong. Please try again later";
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
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

        <style type="text/css">
            body
            {
                font: 14px san-sarif;

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
                <h2>Login</h2>
                <p>Please fill in your credentials to login.</p>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    
                    <div class="form-group <?php echo(!empty($username_err))? 'has-error' : '';?>">
                        
                        <label>Username</label>
                        
                        <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">

                        <span class="help-block"><?php echo $username_err; ?></span>
                    </div>

                    <div class="form-group <?php echo(!empty($password_err))? 'has-error' : '';?>">

                        <label>Password</label>

                        <input type="text" name="password" class="form-control">

                        <span class="help-block"><?php echo $password_err;?></span>
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="login">
                    </div>

                    <p>Don't have an account? <a href="register.php">Sign Up now</a>.</p>

                </form>

            </div>

    </body>

</html>