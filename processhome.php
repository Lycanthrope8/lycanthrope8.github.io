<?php


include('config.php');
include('login.php');
$username=$_SESSION['username'];
$user_id=$_SESSION['user_id'];
$home_id=$_SESSION['home_id'];
$home_members=$_SESSION['home_members'];
$HExpenseID=0;
$update=false;
$everyone=false;
$descr = '';
$amount = '';
$category = '';
$member_count=sizeof($home_members)+1;
// function pre_r($array){
//     echo '<pre>';
//     print_r($array);
//     echo '</pre>';
// }


if(isset($_POST['add'])){
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    // $con->query("INSERT INTO $user_pexpenses (descr,amount,ds,ts) VALUES($descr,$amount,getdate(),getdate())");
    $addsql = "INSERT INTO homeexpenses (home_id,user_id,username,descr,amount,category) VALUES(?,?,?,?,?,?)";
    $stmtadd = $con->prepare($addsql);     // ???????????????????????
    $result = $stmtadd->execute([$home_id,$user_id,$username,$descr,$amount,$category]); 
    

    // Adding to the personal expense because money spent in home is also a personal expense

    
    $addsql = "INSERT INTO userexpenses (HExpenseID,user_id,username,descr,amount,category) VALUES(?,?,?,?,?,?)";
    $addtopersonal = $con->prepare($addsql);     // ???????????????????????
    $hometopersonal = $addtopersonal->execute([mysqli_insert_id($con),$user_id,$username,$descr,($amount/$member_count),$category]); 

    // Adding to UserDebtSurplus
    foreach ($home_members as $member) {
        
        $debtsurplus = $con->query("SELECT creditor,debtor FROM userdebtsurplus WHERE debtor='$member' AND creditor='$username'");
        if($debtsurplus->num_rows == 0) {
            $adddebtsurplusquery ="INSERT INTO userdebtsurplus (home_id,creditor,debtor,descr,amount) VALUES (?,?,?,?,?)";
            $adddebtsurplusprepare = $con->prepare($adddebtsurplusquery);
            $adddebtsurplus = $adddebtsurplusprepare->execute([$home_id,$username,$member,$descr,$amount/$member_count]);
        } else {
            $updatedebtsurplusquery = "UPDATE userdebtsurplus SET amount=amount+(?), descr=CONCAT(descr,',',?) WHERE debtor=?";
            $updatedebtsurplusprepare = $con->prepare($updatedebtsurplusquery);
            $updatedebtsurplus = $updatedebtsurplusprepare->execute([$amount/$member_count,$descr,$member]);
        }
    }


    
    
    $_SESSION['message'] = "Record Has Been Saved";
    $_SESSION['msg_type'] = "Success";
    header("Location: house.php?=Succesfully Added");
}

if(isset($_GET['delete'])){
    $HExpenseID = $_GET['delete'];
    
    // Getting Previous amount to update DebtSurplus Table
    //Order of the SQL query is important because data will be changed 
    //Do not set any Queries before this 
    $prev_amount=0;
    $result = $con->query("SELECT amount FROM homeexpenses WHERE HExpenseID=$HExpenseID");
    $row = $result->fetch_array();
    $prev_amount=$row['amount'];
    //New Queries From Here

    // Delete from Home Expenses
    $con->query("DELETE FROM homeexpenses WHERE HExpenseID=$HExpenseID");
    // Delte from User Expenses
    $con->query("DELETE FROM userexpenses WHERE HExpenseID=$HExpenseID");
    
    // Updating the amount of each members debt surplus  
    foreach ($home_members as $member) {
        $result = $con->query("UPDATE userdebtsurplus SET amount=(amount-($prev_amount/$member_count)) WHERE debtor='$member' AND creditor='$username'");
    }

    $_SESSION['message'] = "Record Has Been Deleted";
    $_SESSION['msg_type'] = "Danger";
    header("Location: house.php?=Succesfully Deleted");
}
if(isset($_GET['everyone'])){
    $everyone=true;
}

if(isset($_GET['edit'])){
    
    $HExpenseID = $_GET['edit'];
    $result = $con->query("SELECT descr,amount,category FROM homeexpenses WHERE HExpenseID=$HExpenseID");
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
    $HExpenseID = $_POST['HExpenseID'];
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];

    // Getting Previous amount to update DebtSurplus Table
    //Order of the SQL query is important because data will be changed 
    //Do not set any Queries before this 
    $prev_amount=0;
    $result = $con->query("SELECT amount FROM homeexpenses WHERE HExpenseID=$HExpenseID");
    $row = $result->fetch_array();
    $prev_amount=$row['amount'];
    //Queries after this line 


    // Updating Home Expense Table
    $result = $con->query("UPDATE homeexpenses SET descr='$descr',amount=$amount, category='$category' WHERE HExpenseID=$HExpenseID");

    // Updating userexpense table
    $result = $con->query("UPDATE userexpenses SET descr='$descr',amount=($amount/$member_count), category='$category' WHERE HExpenseID=$HExpenseID");

    // Updating debtsurplus table
    foreach ($home_members as $member) {
        $result = $con->query("UPDATE userdebtsurplus SET amount=(amount-($prev_amount/$member_count)+($amount/$member_count)) WHERE debtor='$member' AND creditor='$username'");
    }
    
    
    
    $_SESSION['message'] = "Record has been updated";
    $_SESSION['msg_type'] = 'warning';
    header("Location: house.php?=Successfully Updated");
}
if(isset($_POST['addevery'])){
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $everyone = "Everyone";
    $addsql = "INSERT INTO homeexpenses (home_id,user_id,username,descr,amount,category) VALUES(?,?,?,?,?,?)";
    $stmtadd = $con->prepare($addsql);     // ???????????????????????
    $result = $stmtadd->execute([$home_id,$user_id,$everyone,$descr,$amount,$category]); 
    ////Adding to personal expense
    $addsql = "INSERT INTO userexpenses (HExpenseID,user_id,username,descr,amount,category) VALUES(?,?,?,?,?,?)";
    $addtopersonal = $con->prepare($addsql);     // ???????????????????????
    $hometopersonal = $addtopersonal->execute([NULL,$user_id,$username,$descr,($amount/$member_count),$category]); 


    ////adding to each members personal expense
    foreach ($home_members as $member) {
        $memberinfo=$con->query("SELECT user_id FROM users WHERE username='$member'");
        $row=$memberinfo->fetch_assoc();
        $user_id=$row['user_id'];

        $addsql = "INSERT INTO userexpenses (HExpenseID,user_id,username,descr,amount,category) VALUES(?,?,?,?,?,?)";
        $addtopersonal = $con->prepare($addsql);     // ???????????????????????
        $hometopersonal = $addtopersonal->execute([NULL,$user_id,$member,$descr,($amount/$member_count),$category]);
        
    }
    

    
    $_SESSION['message'] = "Record Has Been Saved";
    $_SESSION['msg_type'] = "Success";
    header("Location: house.php?=Succesfully Added");
}

?>