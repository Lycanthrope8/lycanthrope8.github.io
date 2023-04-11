<?php
include('config.php');
include('login.php');
$username=$_SESSION['username'];
$home_id=$_SESSION['home_id'];
?>

<!DOCTYPE html>
<html>
    <head>
    <h3>Welcome, <?php echo $username; ?>!</h3>
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <a href="house.php"><h1>Home</h1></a>
                </div>
                
                <div class="col-sm-4">
                    <a href="home.php"><h1>Personal</h1></a>
                </div>
                <div class="col-sm-4">
                    <a href="todo.php"><h1 class="hometodo">To Do</h1></a>
                </div>
            </div>
        </div>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="assets/css/hometodo/hometodo.css">
        <script src="https://kit.fontawesome.com/ee60cebb6c.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <!-- <?php require_once 'processhometodo.php'; ?> -->
        <!-- Session message. Div is for design purpose -->
        <?php if(isset($_SESSION['message'])):?>
        <div> 
            <?php echo $_SESSION['message']; 
                unset($_SESSION['message']);
            ?>
        </div>
        <?php endif; ?>
        <!-- Query to show whole data table -->
        <?php
        $result = $con->query("SELECT * FROM hometodo WHERE home_id=$home_id AND completed=0 ORDER BY ds,ts ASC");
        if($result->num_rows > 0){
            ?>
            <div>
                <!--Creating data table-->
                <div class="container">
                <div class="row">
                <table class="infotable col-sm-12">
                </div>
                    <thead>
                        <tr>
                            <!-- <th class="tab-head">ID</th> -->
                            <th class="tab-head">Description</th>
                            <th class="tab-head">Date</th>
                            <th class="tab-head">Time</th>
                            <th class="tab-head" colspan='2'>Action</th>
                        </tr>
                    </thead>
                    <!--Loop to see the fetched data table-->

                    <?php
                        while ($row = $result->fetch_assoc()):
                    ?>
                                             
                        <tr>
                            <!-- <td class="tab-items"> <?php echo $row['htodo_id']; ?> </td> -->
                            <td class="tab-items"> <?php echo $row['descr']; ?> </td>
                            <td class="tab-items"> <?php echo $row['ds']; ?> </td>
                            <td class="tab-items"> <?php echo $row['ts']; ?> </td>

                            <td class="tab-items">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <a href="processhometodo.php?done=<?php echo $row['htodo_id']; ?>"><i class="fa-solid fa-check"></i></a>

                                    </div>
                                    <div class="col-sm-4">
                                        <a href="hometodo.php?edit=<?php echo $row['htodo_id']; ?>"><i class="fa-solid fa-pen"></i></a>

                                    </div>
                                    <div class="col-sm-4">
                                        <a href="processhometodo.php?delete=<?php echo $row['htodo_id']; ?>"><i class="fa-solid fa-trash"></i></a>    

                                    </div>
                                </div>
                                <?php if($row['user_id']===$user_id){?>
                                <?php }?>     
                            </td>
                        </tr>
                    
                            
                    <!--Ending the Loop-->
                    <?php endwhile;?>
                </table>
                </div>
            </div>
        <?php }else{
           echo "<h3>No tasks Remaining</h3>";
        } ?>
        
    
    <!--ADD todo Form -->
    <form action="processhometodo.php" method="POST">
    <div class="container">
            <input type="hidden" name="todo_id" value="<?php echo $todo_id ?>">
            <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input class="form-input" type="text" name="descr" value="<?php echo $descr; ?>" required>
                            <label class="form-label" for="descr">Description</label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input class="form-input" type="date" name="date" >
                            <label class="form-label" for="date">DATE</label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input class="form-input" type="time" name="time" >
                            <label class="form-label" for="time">TIME</label>
                        </div>
                    </div>
                    <?php 
                    if ($update == true):
                    ?>
                    <div class="col-sm-2">
                        <button class="button update-btn" type="submit" name="update">Update</button>
                    </div>
                    <?php else: ?>
                    <div class="col-sm-2">
                        <button class="button add-btn" type="submit" name="add">ADD</button>
                    </div>
                    <?php endif; ?>
            </div>
        </div>
    </form>

    <h3>Completed</h3>
    
    <?php
        
        $result = $con->query("SELECT * FROM hometodo WHERE home_id=$home_id AND completed=1 ORDER BY ds,ts ASC");
        if($result->num_rows > 0){
            ?>
            <div>
                <!--Creating data table-->
                
                <div class="container">
                <div class="row">
                <table class="infotable col-sm-12">
                </div>
                    <thead>
                        <tr>
                        <!-- <th class="tab-head">ID</th> -->
                            <th class="tab-head">Description</th>
                            <th class="tab-head">Date</th>
                            <th class="tab-head">Time</th>
                            <th class="tab-head" colspan='2'>Action</th>
                        </tr>
                    </thead>
                    <!--Loop to see the fetched data table-->

                    <?php
                        while ($row = $result->fetch_assoc()):
                    ?>
                                             
                        <tr>
                            <!-- <td class="tab-items"> <?php echo $row['htodo_id']; ?> </td> -->
                            <td class="tab-items"> <?php echo $row['descr']; ?> </td>
                            <td class="tab-items"> <?php echo $row['ds']; ?> </td>
                            <td class="tab-items"> <?php echo $row['ts']; ?> </td>
                            <td class="tab-items">
                                <a href="processhometodo.php?delete=<?php echo $row['htodo_id']; ?>"><i class="fa-solid fa-trash"></i></a>         
                            </td>
                        </tr>
                    
                            
                    <!--Ending the Loop-->
                    <?php endwhile;?>
                </table>
                </div>
            </div>
        <?php }else{
           echo " <h3> No task Completed</h3>";
        } ?>
    </body>

</html>