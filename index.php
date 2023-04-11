<!DOCTYPE html>

<html>

<head>

    <title>LOGIN</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/index/index.css">

</head>

<body>

     <form action="login.php" method="POST">
                <h1>Welcome to Bachelor Expense Management</h1>
                <h1>LOGIN</h1>

                <?php if (isset($_GET['error'])) { ?>

                    <p class="error"><?php echo $_GET['error']; ?></p>

                <?php } ?>

                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input class="form-input" type="text" name="username" required>
                                <label class="form-label" for="username">Username</label>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input class="form-input" type="password" name="pass" required> 
                                <label class="form-label" for="pass">Password</label>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <button class="button" type="submit">Login</button>
                        </div>                
                    </div>
                </div>
    </form>
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-6">
                <button class="button" type="submit"><a href="signup.php">Sign Up</a></button>
            </div>
            <div class="col-sm-6">
                <button class="button" type="submit"><a href="forgetpassword.php">Forgot your password?</a></button>
            </div>
        </div>
    </div>
</body>

</html>