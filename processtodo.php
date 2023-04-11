<?php
include('config.php');
include('login.php');
$username=$_SESSION['username'];
$user_id=$_SESSION['user_id'];
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
    $addsql = "INSERT INTO usertodo (user_id,descr,ds,ts) VALUES(?,?,?,?)";
    $stmtadd = $con->prepare($addsql);     // ???????????????????????
    $result = $stmtadd->execute([$user_id,$descr,$date,$time]); 
    
    $_SESSION['message'] = "Record Has Been Saved";
    $_SESSION['msg_type'] = "Success";
    header("Location: todo.php?=Succesfully Added");
}

if(isset($_GET['delete'])){
    $todo_id = $_GET['delete'];

    $con->query("DELETE FROM usertodo where todo_id=$todo_id");

    $_SESSION['message'] = "Record Has Been Deleted";
    $_SESSION['msg_type'] = "Danger";
    header("Location: todo.php?=Succesfully Deleted");
}


if(isset($_GET['done'])){
    $todo_id = $_GET['done'];

    $con->query("UPDATE usertodo SET completed=1 WHERE todo_id=$todo_id");

    $_SESSION['message'] = "Task Completed";
    $_SESSION['msg_type'] = "Danger";
    header("Location: todo.php?=Succesfully task completed");
}

if(isset($_GET['edit'])){
    
    $todo_id = $_GET['edit'];
    $result = $con->query("SELECT descr,ds,ts FROM usertodo WHERE todo_id=$todo_id");
    $update=true;
    $row = $result->fetch_array();
    $descr = $row['descr'];
    $date = $row['date'];
    $time = $row['time'];
}


if (isset($_POST['update'])){
    $todo_id = $_POST['todo_id'];
    $descr = $_POST['descr'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $updatesql = "UPDATE usertodo SET descr=?,ds=?, ts=? WHERE todo_id=?";
    $stmtadd = $con->prepare($updatesql);     // ???????????????????????
    $result = $stmtadd->execute([$descr,$date,$time,$todo_id]); 
    $_SESSION['message'] = "Record has been updated";
    $_SESSION['msg_type'] = 'warning';
    header("Location: todo.php?=Successfully Updated");
}
?>