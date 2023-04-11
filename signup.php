<!-- LINK : https://www.youtube.com/watch?v=00Thb3oPJ74&list=PLS1QulWo1RIZc4GM_E04HCPEd_xpcaQgg&index=35-->

<?php
include('config.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Registration</title>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="assets/css/signup/signup.css">
    </head>  
<body>
    <div>
        <?php
        if (isset($_POST['register'])){           // register came from input type submit
            $firstname = $_POST['firstname'];     // $attributeName = $_POST['input_name']
            $middlename = $_POST['middlename'];   // Sending Data to the Users table
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $username = $_POST['username'];
            $pass = $_POST['pass'];
            
            $sql = "INSERT INTO users (firstname,middlename,lastname,email,phone,username,pass) VALUES(?,?,?,?,?,?,?)";
            $stmtinsert = $con->prepare($sql);     // ???????????????????????
            $result = $stmtinsert->execute([$firstname,$middlename,$lastname,$email,$phone,$username,$pass]);   // ???????????????????????
            
            //echo $firstname ." ". $middlename ." ". $lastname . " " . $email . " " . $phone . " " . $pass; 
            
            if ($result){
                $collect = $con->query("SELECT * FROM users WHERE username='$username'");
                $row = $collect->fetch_assoc();
                $user_id=$row['user_id'];
                
                $_SESSION['user_id']=$user_id;
                $_SESSION['username']=$username;
                // $User_Pexpenses=$username.'_Pexpenses';
                // $User_Owed=$username.'_Owed';
                // $User_Debt=$username.'_Debt';

                ///Creating data table for Personal Expenses
                // $create = "CREATE TABLE $User_Pexpenses (PexpenseID int NOT NULL AUTO_INCREMENT UNIQUE KEY, descr varchar(100),category varchar(100), amount int,DS DATE,TS TIME)";
                // $create = $con->prepare($create);
                // $create->execute();
                ///Creating data table for Home Expenses
                // $create = "CREATE TABLE $User_Hexpenses (HexpenseID int NOT NULL AUTO_INCREMENT UNIQUE KEY, descr varchar(100), amount int,DS DATE,TS TIME)";
                // $create = $con->prepare($create);
                // $create->execute();
                header("Location: index.php?=Account created successfully") ;
            }else{
                echo "Couldn't Create The Account. Please Try again later." ; 
            }

        }
        ?>
    </div>
    <div>
        <form action="signup.php" method="POST">
            <div class="container-fluid">
                <h1>Welcome to Bachelor Expense Management</h1>
                <h1>Registration</h1>
                <p>Fill up the form with correct values</p>
            </div>
            <div class="container">

                <!-- For First Name-->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group fn">
                            <input class="form-input" type="text" name="firstname" required>
                            <label class="form-label" for="firstname">First Name</label>
                        </div>
                    </div>
                

                <!-- For Middle Name-->
                    <div class="col-sm-6">
                        <div class="form-group mn">
                            <input class="form-input" type="text" name="middlename">
                            <label class="form-label" for="middlename">Middle Name</label>
                        </div>
                    </div>
                

                <!-- For Last Name-->
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input class="form-input" type="text" name="lastname" required>
                            <label class="form-label" for="lastname">Last Name</label>
                        </div>
                    </div>
                    

                    <!-- For Email-->
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input class="form-input" type="email" name="email" required>
                            <label class="form-label" for="email">Email Address</label>
                        </div>
                    </div>
                    

                    <!-- For Phone number-->
                    <div class="com-sm-12">
                        <div class="form-group">
                            <input class="form-input" type="text" name="phone" required>
                            <label class="form-label" for="phone">Phone Number</label>
                        </div>
                    </div>
                    

                    <!-- For User Name-->
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input class="form-input" type="text" name="username" required>
                            <label class="form-label" for="username">User Name</label>
                        </div>
                    </div>
                    

                    <!-- For Password-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input class="form-input" type="password" name="pass" required>
                            <label class="form-label" for="pass">Password</label>
                        </div>
                    </div>

                    <!-- For Password-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input class="form-input" type="password" name="confirmpass" required>
                            <label class="form-label" for="confirmpass">Confirm pass</label>
                        </div>
                    </div>
                    <input class="form-submit" type="submit" id="register" name="register" value="Sign Up">
                </div>
            </div>
        </form>
        <div class="login">
        <button class="form-submit" type="submit"><a href="index.php">Login</a></button>
        </div>
    </div>
    <script>
        $('.form-input').hover(function(){
            $(this).addClass('input-active');
        });
    </script>
</body>
</html>