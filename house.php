<?php
include('config.php');
include('login.php');
$username=$_SESSION['username'];
$home_id=$_SESSION['home_id'];
?>

<?php
    //Checking if the user has any house or not

    $result = $con->query("SELECT home_id FROM users WHERE username='$username'");
    $row = $result->fetch_array();
    $home_id=$row['home_id'];
    // var_dump($home_id); // vardump is used to print null


    function pre_r($array){
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }


    // if the homeid is null then redirect to homeindex page where anyone can join or create a house
    if(is_null($home_id)){
        header("Location: homeindex.php");
    }


    // Saving all home members names in an array///////
    $sql = "SELECT username FROM users WHERE home_id=$home_id";
    $r = mysqli_query($con,$sql);
    $home_members = [];
    while ($array = mysqli_fetch_array($r)) {
        if ($username!=$array['username']){
        $home_members[] = $array['username'];
        }
    }
    $_SESSION['home_members']=$home_members;   

    // pre_r($home_members);
?>


<!DOCTYPE html>
<html>
    <head>
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <a href="house.php"><h1 class="home">Home</h1></a>
                </div>
                <div class="col-sm-4">
                    <a href="home.php"><h1>Personal</h1></a>
                </div>
                <div class="col-sm-4">
                    <a href="hometodo.php"><h1>Home To Do</h1></a>
                </div>
            </div>
        </div>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="assets/css/house/house.css">
        <script src="https://kit.fontawesome.com/ee60cebb6c.js" crossorigin="anonymous"></script>
        <!-- Chart Script -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Category', 'Amount'],
                <?php
                $query="SELECT category, SUM(amount) AS amount FROM homeexpenses WHERE home_id=$home_id GROUP BY category";
                $exec=mysqli_query($con,$query);
                while($row=mysqli_fetch_array($exec) ){
                    echo "['".$row['category']."',".$row['amount']."],";
                } 
                ?>
            ]);

            var options = {
                backgroundColor: 'transparent',
                // legend:{
                //     position:"none"
                // },
                // pieSliceText: "label",
                // pieSliceBorderColor: "none",
                // pieStartAngle: 100,
                title: 'Personal Expenses Chart',
                pieHole: 0.5,
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        }
        </script>
        </head>

    <body>
        <!-- <?php require_once 'processhome.php'; ?> -->
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
        $result = $con->query("SELECT HExpenseID,username,descr,amount,category,ds,ts FROM homeexpenses WHERE home_id=$home_id ORDER BY HExpenseID DESC");
        if($result->num_rows > 0){

            ?>
            <div class="container">
                <!--Creating data table-->
                <div class="row">
                <table class="infotable col-sm-12">
                </div>
                    <thead>
                        <tr>
                            <!-- <th class="tab-head">ID</th> -->
                            <th class="tab-head">Username</th>
                            <th class="tab-head">Description</th>
                            <th class="tab-head">Amount</th>
                            <th class="tab-head">Category</th>
                            <th class="tab-head">Date</th>
                            <th class="tab-head">Time</th>
                            <th class="tab-head" colspan='2'>Action</th>
                        </tr>
                    </thead>
                    <!--Loop to see the fetched data table-->

                    <?php
                        while ($row = $result->fetch_assoc()):
                        // $name= $con->query("SELECT CONCAT(firstname,SPACE(1),middlename,SPACE(1),lastname) AS fullname FROM users WHERE username='$row['username']'");
                        // $fullname= $name->fetch_assoc();
                    ?>
                                             
                        <tr>
                            <!-- <td class="tab-items"> <?php echo $row['HExpenseID']; ?> </td> -->
                            <td class="tab-items"> <?php echo $row['username']; ?> </td>
                            <td class="tab-items"> <?php echo $row['descr']; ?> </td>
                            <td class="tab-items"> <?php echo $row['amount']; ?> </td>
                            <td class="tab-items"> <?php echo $row['category']; ?> </td>
                            <td class="tab-items"> <?php echo $row['ds']; ?> </td>
                            <td class="tab-items"> <?php echo $row['ts']; ?> </td>
                            <?php if($row['username']===$username){?>
                                <td class="tab-items">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a href="house.php?edit=<?php echo $row['HExpenseID']; ?>"><i class="fa-solid fa-pen"></i></a>
                                        </div>
                                        <div class="col-sm-6">
                                            <a href="processhome.php?delete=<?php echo $row['HExpenseID']; ?>"><i class="fa-solid fa-trash"></i></a>
                                        </div>
                                    </div>
                                </td>
                            <?php }else{
                                ?>
                                <td><p></p></td>
                           <?php } ?>
                        </tr>
                    
                            
                    <!--Ending the Loop-->
                    <?php endwhile;?>
                </table>
            </div>
        <?php }else{
        echo "<h3>No Expense Added Yet</h3>";
        }?>

        
        <?php
        //function to print fetched array
        // function pre_r($array){
        //     echo '<pre>';
        //     print_r($array);
        //     echo '</pre>';
        // }
        ?>



        <!--ADD Amount Form -->
        <form action="processhome.php" method="POST">
            <div class="container">
                <input type="hidden" name="HExpenseID" value="<?php echo $HExpenseID ?>">
                <!-- <?php 
                    if ($everyone === false){
                ?>
                        <button><a href="house.php?everyone=>">Self</a></button>
                <?php
                    }else{
                ?>
                        <button><a href="house.php?everyone=>">Everyone</a></button>
                <?php } ?> -->
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input class="form-input" type="text" name="descr" value="<?php echo $descr; ?>" required>
                            <label class="form-label" for="descr">Description</label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input class="form-input" type="number" name="amount" min="0" value="<?php echo $amount; ?>" required>
                            <label class="form-label" for="amount">Amount</label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="dropdown form-group">
                            <input type="text" class="textBox form-input" name="category" readonly>
                            <label class="form-label-cat" for="category">Category</label>
                            <!-- <select name="category">
                                <option value="Food">Food</option>
                                <option value="Transportation">Transportation</option>
                                <option value="Housing">Housing</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Others">Others</option>
                            </select> -->
                            <div class="option">
                                <div onclick="show('Food')">Food</div>
                                <div onclick="show('Transportation')">Transportation</div>
                                <div onclick="show('Housing')">Housing</div>
                                <div onclick="show('Entertainment')">Entertainment</div>
                                <div onclick="show('Others')">Others</div>
                            </div>
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
                        <button class="button add-btn" type="submit" name="addevery"> Add As Everyone Paid</button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        <script>
            function show(anything){
                document.querySelector('.textBox').value=anything;
                document.querySelector('.form-label-cat').classList.add('input-valid');
            }
            let dropdown=document.querySelector('.dropdown');
            dropdown.onclick=function(){
                dropdown.classList.toggle('active');
            }
        </script>
        <div class="container">
            <div id="donutchart" class="col-xs-12 col-sm-6 col-md-4">
                <div id="chart"></div>
                <div id="labelOverlay">
                    <!-- <h3>Total Spent: <?php echo $totalamount['totalamount'] ?></h3> -->
                    <p class="used-size">piechart</p>
                </div>
            </div>
        </div>
    </body>
</html>