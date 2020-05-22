<?php
    define('db_server','localhost');
    define('db_username','root');
    define('db_password','');
    define('db_name','userlist');

    $link= mysqli_connect(db_server, db_username, db_password, db_name);
    if($link === false)
    {
        die("Error: could not connect".mysqli_connect_error());
    }
?>