<?php


include('config.php');
include('login.php');
$username=$_SESSION['username'];
$user_id=$_SESSION['user_id'];
$UExpenseID=0;
$update=false;
$descr = '';
$amount = '';
$category = '';

if(isset($_POST['add'])){
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    
    // $con->query("INSERT INTO $user_pexpenses (descr,amount,ds,ts) VALUES($descr,$amount,getdate(),getdate())");
    $addsql = "INSERT INTO userexpenses (user_id,username,descr,amount,category) VALUES(?,?,?,?,?)";
    $stmtadd = $con->prepare($addsql);     // ???????????????????????
    $result = $stmtadd->execute([$user_id,$username,$descr,$amount,$category]); 
    
    $_SESSION['message'] = "Record Has Been Saved";
    $_SESSION['msg_type'] = "Success";
    header("Location: home.php?=Succesfully Added");
}

if(isset($_GET['delete'])){
    $UExpenseID = $_GET['delete'];

    $con->query("DELETE FROM userexpenses where UExpenseID=$UExpenseID");

    $_SESSION['message'] = "Record Has Been Deleted";
    $_SESSION['msg_type'] = "Danger";
    header("Location: home.php?=Succesfully Deleted");
}

if(isset($_GET['edit'])){
    
    $UExpenseID = $_GET['edit'];
    $result = $con->query("SELECT descr,amount,category FROM userexpenses WHERE UExpenseID=$UExpenseID");
    $update=true;
    $row = $result->fetch_array();
    $descr = $row['descr'];
    $amount = $row['amount'];
    $category = $row['category'];
    // if (count($result)==1){
    //     $row = $result->fetch_array();
    //     $descr = $row['descr'];
    //     $amount = $row['amount'];
    // }

}

if (isset($_POST['update'])){
    $UExpenseID = $_POST['UExpenseID'];
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];

    $result = $con->query("UPDATE userexpenses SET descr='$descr',amount=$amount, category='$category' WHERE UExpenseID=$UExpenseID");
    $_SESSION['message'] = "Record has been updated";
    $_SESSION['msg_type'] = 'warning';
    header("Location: home.php?=Successfully Updated");
}
?>
