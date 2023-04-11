<?php   
session_start(); 
include "config.php";

if (isset($_POST['username']) && isset($_POST['pass'])) {

    function validate($data){

       $data = trim($data);

       $data = stripslashes($data);

       $data = htmlspecialchars($data);

       return $data;

    }

    $username = validate($_POST['username']);

    $pass = validate($_POST['pass']);

    if (empty($username)) {

        header("Location: index.php?error=Email address is required");

        exit();

    }else if(empty($pass)){

        header("Location: index.php?error=Password is required");

        exit();

    }else{

        $sql = "SELECT user_id,username,pass,home_id FROM users where username = '$username' and pass = '$pass'";  
        $result = mysqli_query($con, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);

        if($count === 1){  
            echo "<h1><center> Login successful </center></h1>";

            $user_id=$row['user_id'];
            $username=$row['username'];
            $home_id=$row['home_id'];
            $_SESSION['user_id']=$user_id;
            $_SESSION['username']=$username;
            $_SESSION['home_id']=$home_id;
            header("Location: home.php");
            exit();
        }else{  
            // echo "<h1> Login failed. Invalid username or pass.</h1>";  
            $_SESSION['error']="Incorrect username or PAssword";
            header("Location: index.php?error=Invalid username or password!");
            exit();
                }
        }
}