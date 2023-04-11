<?php
include('config.php');
?>

<!DOCTYPE html>
<html>
    <h1>Connected</h1>
</html>

<?php


  // For Partial Payment of debtors
  if(isset($_POST['partialpay'])){
    $debt_id = $_POST['debt_id'];
    $partial = $_POST['partial'];
   
    $check = $con->query("SELECT amount FROM userdebtsurplus WHERE debt_id=$debt_id");
    $row = $check->fetch_array();
    if ($row['amount']>=$partial){ 
        $partialpay = "UPDATE userdebtsurplus SET partial_pay=? WHERE debt_id=?";
        $stmt = $con->prepare($partialpay);     // ???????????????????????
        $result = $stmt->execute([$partial,$debt_id]);
        header("Location: home.php");
      }else{
        $_SESSION['message'] = "Invalid Amount";
        header("Location: home.php?=Unseccessful");
      }

  }

  // For Full Payment of debtors
  if(isset($_POST['fullpay'])){
    $debt_id=$_POST['debt_id'];
    $fullpay = "UPDATE userdebtsurplus SET paid=? WHERE debt_id=?";
    $stmt = $con->prepare($fullpay);     // ???????????????????????
    $result = $stmt->execute([1,$debt_id]);
    header("Location: home.php");
  }

  // For Full Confirmation of the Creditor
  if(isset($_POST['fullyes'])){
    $debt_id=$_POST['debt_id'];

///////////////
/// Debt pay is inserting in userexpenses
    $debtinfo = $con->query("SELECT creditor, debtor,descr, amount FROM userdebtsurplus WHERE debt_id=$debt_id");
    $debtinfo = $debtinfo->fetch_assoc();

    $creditor=$debtinfo['creditor'];
    $debtor=$debtinfo['debtor'];
    $amounttoadd=$debtinfo['amount'];
    $descr='Debt pay to '.$creditor.' for '.$debtinfo['descr'];
    $category='Debt';
    
    $addex ="INSERT INTO userexpenses (user_id, username, descr, category, amount) VALUES ((SELECT user_id FROM users WHERE username=?),?,?,?,? )";
    $stmt = $con->prepare($addex);
    $result = $stmt->execute([$debtor,$debtor,$descr,$category,$amounttoadd]);
////////////////////

    $fullyes = "UPDATE userdebtsurplus SET descr=?, amount=? WHERE debt_id=?";
    $stmt = $con->prepare($fullyes);     // ???????????????????????
    $result = $stmt->execute([NULL,0,$debt_id]);

    $fullyes = "UPDATE userdebtsurplus SET paid=? WHERE debt_id=?";
    $stmt = $con->prepare($fullyes);     // ???????????????????????
    $result = $stmt->execute([NULL,$debt_id]);



    header("Location: home.php");

  }

  // For No Confirmation of the Creditor
  if(isset($_POST['fullno'])){
    $debt_id=$_POST['debt_id'];
    $fullno = "UPDATE userdebtsurplus SET paid=? WHERE debt_id=?";
    $stmt = $con->prepare($fullno);     // ???????????????????????
    $result = $stmt->execute([NULL,$debt_id]);
    header("Location: home.php");

  }
  
  // For Partial Confirmation of the Creditor
  if(isset($_POST['partialyes'])){
    $debt_id=$_POST['debt_id'];
    $partial_pay=$_POST['partial_pay'];

    ///////////////
    /// Debt pay is inserting in userexpenses
    $debtinfo = $con->query("SELECT creditor, debtor,descr, amount FROM userdebtsurplus WHERE debt_id=$debt_id");
    $debtinfo = $debtinfo->fetch_assoc();

    $creditor=$debtinfo['creditor'];
    $debtor=$debtinfo['debtor'];
    $amounttoadd=$partial_pay;
    $descr='Partial Debt pay to '.$creditor.' for '.$debtinfo['descr'];
    $category='Debt';

    $addex ="INSERT INTO userexpenses (user_id, username, descr, category, amount) VALUES ((SELECT user_id FROM users WHERE username=?),?,?,?,? )";
    $stmt = $con->prepare($addex);
    $result = $stmt->execute([$debtor,$debtor,$descr,$category,$amounttoadd]);
    ////////////////////

    ///Updating Amount
    $partialyes = "UPDATE userdebtsurplus SET amount=amount-$partial_pay WHERE debt_id=?";
    $stmt = $con->prepare($partialyes);     // ???????????????????????
    $result = $stmt->execute([$debt_id]);

    ///Setting Partial pay to NULL
    $partialyes = "UPDATE userdebtsurplus SET partial_pay=? WHERE debt_id=?";
    $stmt = $con->prepare($partialyes);     // ???????????????????????
    $result = $stmt->execute([NULL,$debt_id]);

    header("Location: home.php");
    
  }

  // For Partial No Confirmation of the Creditor
  if(isset($_POST['partialno'])){
    $debt_id=$_POST['debt_id'];
    $partialno = "UPDATE userdebtsurplus SET partial_pay=? WHERE debt_id=?";
    $stmt = $con->prepare($partialno);     // ???????????????????????
    $result = $stmt->execute([NULL,$debt_id]);

    header("Location: home.php");
  }
  
?>