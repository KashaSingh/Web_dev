<?php
ob_start();
date_default_timezone_set("Asia/Kolkata");
try
{
    // Developer: $con=new PDO("mysql:dbname=acadgenix;host=localhost","root","");
    $host = 'localhost';
    $db = 'agadg79w_acadgenix';
    $user = 'agadg79w_acadDatabaseUser';
    $pass = 'Boss@DWD';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $con=new PDO($dsn,$user,$pass);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(PDOException $e)
{?>
    <script>
        alert("Connection to Database Failed!");
    </script>
<?php
}
?>