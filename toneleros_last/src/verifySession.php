
<?php

session_start();
error_reporting(0);
$varsession = $_SESSION['username'];
if($varsession == null || $varsession == ''){
    header("location:index.php");
    die();
}
?>