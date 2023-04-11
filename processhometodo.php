<?php
include('config.php');
include('login.php');
$username=$_SESSION['username'];
$user_id=$_SESSION['user_id'];
$home_id=$_SESSION['home_id'];
$update=false;
$descr = '';
$date = '';
$time = '';
?>

<?php

if(isset($_POST['add'])){
    $descr = $_POST['descr'];
    $date = $_POST['date'];
    $time = $_POST['time'];


    


    // $con->query("INSERT INTO $user_pexpenses (descr,amount,ds,ts) VALUES($descr,$amount,getdate(),getdate())");
    $addsql = "INSERT INTO hometodo (home_id,user_id,descr,ds,ts) VALUES(?,?,?,?,?)";
    $stmtadd = $con->prepare($addsql);     // ???????????????????????
    $result = $stmtadd->execute([$home_id,$user_id,$descr,$date,$time]); 
    
    $_SESSION['message'] = "Record Has Been Saved";
    $_SESSION['msg_type'] = "Success";
    header("Location: hometodo.php?=Succesfully Added");
}

if(isset($_GET['delete'])){
    $htodo_id = $_GET['delete'];

    $con->query("DELETE FROM hometodo where htodo_id=$htodo_id");

    $_SESSION['message'] = "Record Has Been Deleted";
    $_SESSION['msg_type'] = "Danger";
    header("Location: hometodo.php?=Succesfully Deleted");
}


if(isset($_GET['done'])){
    $htodo_id = $_GET['done'];

    $con->query("UPDATE hometodo SET completed=1 WHERE htodo_id=$htodo_id");

    $_SESSION['message'] = "Task Completed";
    $_SESSION['msg_type'] = "Danger";
    header("Location: hometodo.php?=Succesfully task completed");
}

if(isset($_GET['edit'])){
    
    $htodo_id = $_GET['edit'];
    $result = $con->query("SELECT descr,ds,ts FROM hometodo WHERE htodo_id=$htodo_id");
    $update=true;
    $row = $result->fetch_array();
    $descr = $row['descr'];
    $date = $row['date'];
    $time = $row['time'];
}


if (isset($_POST['update'])){
    $htodo_id = $_POST['htodo_id'];
    $descr = $_POST['descr'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $updatesql = "UPDATE hometodo SET descr=?,ds=?, ts=? WHERE htodo_id=?";
    $stmtadd = $con->prepare($updatesql);     // ???????????????????????
    $result = $stmtadd->execute([$descr,$date,$time,$htodo_id]); 
    $_SESSION['message'] = "Record has been updated";
    $_SESSION['msg_type'] = 'warning';
    header("Location: hometodo.php?=Successfully Updated");
}
?>