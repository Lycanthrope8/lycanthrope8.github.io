<?php
include('config.php');
include('login.php');
$username=$_SESSION['username'];
?>

<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="assets/css/homeindex/homeindex.css">
    </head>
    <body>
        <h1>You have not joined any home yet!</h1>
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <h2><a href="joinhome.php">Join Home</a></h2>
                </div>
                <div class="col-sm-6">
                    <h2><a href="createhome.php">Create Home</a></h2>
                </div>
            </div>
        </div>
    </body>
</html>