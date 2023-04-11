<?php
include('config.php');
include('login.php');
$username=$_SESSION['username'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Create Home</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/createhome/createhome.css">
    </head>  
<body>
    <div>
        <?php
        ///Creating home for the user
        if (isset($_POST['create'])){           // create came from input type submit
            $owner = $_POST['owner'];     // $attributeName = $_POST['input_name']
            $homename = $_POST['homename'];   
            $securitycode = $_POST['securitycode'];
            $address = $_POST['address'];
            
            $sql = "INSERT INTO homes (owner,homename,address,securitycode) VALUES(?,?,?,?)";
            $stmtinsert = $con->prepare($sql);     // ???????????????????????
            $result = $stmtinsert->execute([$owner,$homename,$address,$securitycode]);   // ???????????????????????
            
        ///Saving home_id in users data table to access their home db 
            $result = $con->query("SELECT * FROM homes WHERE homename='$homename'");
            $row = $result->fetch_assoc();
            $home_id=$row['home_id'];
            $addhome = $con->query("UPDATE users SET home_id=$home_id WHERE username='$username'");
            $_SESSION['home_id']=$home_id;
            header("Location: house.php");

        }
        ?>



    </div>
    <div>
        <form action="createhome.php" method="POST">
            <div class="container">
                <h1>Create Home</h1>
                <p>Fill up the form with correct values</p>

                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- For Home Owner-->
                            <div class="form-group fn">
                                <input class="form-input" type="text" name="owner" required>
                                <label class="form-label" for="owner">Owner Name</label>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- For Home Name (Unique)-->
                            <div class="form-group">
                                <input class="form-input" type="text" name="homename" required>
                                <label class="form-label" for="homename">Home Name (Unique)</label>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- For Address-->
                            <div class="form-group">
                                <input class="form-input" type="text" name="address" required>
                                <label class="form-label" for="address"> Address</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- For Security code-->
                            <div class="form-group">
                                <input class="form-input" type="password" name="securitycode" required>
                                <label class="form-label" for="securitycode">Security Code</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- For security code confirmation-->
                            <div class="form-group">
                                <input class="form-input" type="password" name="confirmsecuritycode" required>
                                <label class="form-label" for="confirmsecuritycode">Confirm Security Code</label>
                            </div>
                        </div>
                    </div>
                </div>
                <input class="button" type="submit" id="create" name="create" value="Create">
            </div>
        </form>
    </div>
</body>
</html>