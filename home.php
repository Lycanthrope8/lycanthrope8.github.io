
<?php

include('config.php');
include('login.php');

$username=$_SESSION['username'];
$user_id=$_SESSION['user_id'];
$home_id=$_SESSION['home_id'];
?>

<!DOCTYPE html>
<html>
    <head>
        <h3>Welcome, <?php echo $username; ?>!</h3>
        <a href="logout.php" class="logout-btn">Logout</a>
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <a href="house.php"><h1>Home</h1></a>
                </div>
                
                <div class="col-sm-4">
                    <a href="home.php"><h1 class="personal">Personal</h1></a>
                </div>
                <div class="col-sm-4">
                    <a href="todo.php"><h1>To Do</h1></a>
                </div>
            </div>
        </div>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="assets/css/home/home.css">
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
                $query="SELECT category, SUM(amount)  AS amount FROM userexpenses WHERE user_id=$user_id GROUP BY category";
                $exec=mysqli_query($con,$query);
                while($row=mysqli_fetch_array($exec) ){
                    echo "['".$row['category']."',".$row['amount']."],";
                } 
                ?>
            ]);

            var options = {
                backgroundColor: 'transparent',
                // legend: "none",
                // pieSliceText: "label",
                // pieSliceBorderColor: "none",
                pieStartAngle: 100,
                title: 'Personal Expenses Chart',
                pieHole: 0.5,
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        }
        </script>
    </head>

    <body>
        <!-- <?php require_once 'process.php'; ?> -->
        <!-- Session message. Div is for design purpose -->
        <?php if(isset($_SESSION['message'])):?>
        <div> 
            <?php echo $_SESSION['message']; 
                unset($_SESSION['message']);
            ?>
        </div>
        <?php endif; ?>
        
        <!-- User Surplus Details -->
        
        <?php
        $surplus = "SELECT debt_id,debtor,creditor,amount,paid,partial_pay FROM userdebtsurplus WHERE creditor='$username'";
        $surplusresult = $con->query($surplus);
        if($surplusresult->num_rows > 0){
            echo "<h3>Surplus</h3>";
            while($row=$surplusresult->fetch_assoc()){
                $name= $con->query("SELECT CONCAT(firstname,SPACE(1),middlename,SPACE(1),lastname) AS fullname FROM users WHERE username='$row[debtor]'");
                $fullname= $name->fetch_assoc();
                $debt_id=$row['debt_id'];
                $statement="";
                if($row['amount']!=0){
                    if($row['paid']==1){
                        $statement="  (Did ".$fullname['fullname']." Paid You TK ".$row['amount']."?) "; 
                        echo "<b>".$fullname['fullname']."</b>"." : "."<p>".$row['amount'].$statement."</p>";
                        ?>
                        <form action="debtprocess.php" method="POST">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <input type="hidden" id="debt_id" name="debt_id" value="<?php echo $debt_id; ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn-yes" type="submit" name="fullyes">YES</button>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn-no" type="submit" name="fullno">NO</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    
                            
                                <?php
                                }
                                elseif($row['partial_pay']!=NULL){
                                    $partial_pay=$row['partial_pay'];
                                    $statement=" (Did ".$fullname['fullname']." Paid You TK ".$row['partial_pay']."?) ";
                                    echo "<b>".$fullname['fullname']."</b>"." : "."<p>".$row['amount'].$statement."</p>";
                                    ?>
                            <div style="display:inline-block; width:45%; margin:auto;">
                            <form action="debtprocess.php" method="POST">
                            <!-- <div class="container"> -->
                                <!-- <div class="row"> -->
                                <input class="form-input" type="hidden" id="debt_id" name="debt_id" value="<?php echo $debt_id; ?>">
                                <!-- <div> -->
                                    <input class="form-input" type="hidden" id="partial_pay" name="partial_pay" value="<?php echo $partial_pay; ?>">
                                <!-- </div> -->
                                    <button class="btn-yes" type="submit" name="partialyes">YES</button>
                                    <button class="btn-no" type="submit" name="partialno">NO</button>
                                <!-- </div> -->
                             <!-- </div> -->
                            </form>
                            </div>
                        <?php
                    }
                    else{
                        echo "<b>".$fullname['fullname']."</b>"." : "."<p>".$row['amount'].$statement." Tk</p>";
                    }
                }
            }
        }else{
            echo "<h3>No Surpluses</h3>";
        }
        ?>

        <!-- End of Surplus Details -->

        <!-- User Debt Details -->

        <?php
        $debt = "SELECT debt_id,debtor,creditor,descr,amount,paid,partial_pay FROM userdebtsurplus WHERE debtor='$username'";
        $debtresult = $con->query($debt);
        if($debtresult->num_rows > 0){
            echo "<h3>Debts</h3>";
            while($row=$debtresult->fetch_assoc()){
                if($row['amount']!=0){
                    $name= $con->query("SELECT CONCAT(firstname,SPACE(1),middlename,SPACE(1),lastname) AS fullname FROM users WHERE username='$row[creditor]'");
                    $fullname= $name->fetch_assoc();
                    $debt_id=$row['debt_id'];
                    $statement="";
                    if($row['paid']==1){
                        $statement= " (Full Pay TK ".$row['amount']." Pending Confirmation)";
                        echo "<b>".$fullname['fullname']."</b>"." : "."<p>".$row['amount'].$statement."</p>";
                        }
                    elseif($row['partial_pay']!=NULL){
                        $statement= " (Partial Pay TK ". $row['partial_pay']." Pending Confirmation)";
                        echo "<b>".$fullname['fullname']."</b>"." : "."<p>".$row['amount'].$statement."</p>";
                        }
                    else{
                        echo "<b>".$fullname['fullname']."</b>"." : "."<p>".$row['amount']." (".$row['descr']." )</p>";
                        ?>  
                        <form class="payform" action="debtprocess.php" method='POST'>
                            <div class="container">
                                <div class="row">
                                    <input class="form-input" type="hidden" id="debt_id" name="debt_id" value="<?php echo $debt_id; ?>">
                                    <div class="col-sm-4">
                                        <input class="form-input" type="number" name="partial">

                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn-pay" type="submit" name="partialpay">Pay</button>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn-fullpay" type="submit" name="fullpay">Full Pay</button>

                                    </div>
                                </div>
                            </div>
                        </form>

                    <?php
                    }
                }
            }
        }else{
            echo "<h3>No Debts</h3>";
        }
        ?>
        <!-- End of Debt Details  -->


        <!-- Query to show whole data table of expenses -->
        <?php
        $result = $con->query("SELECT UExpenseID,HExpenseID,descr,amount,category,ds,ts FROM userexpenses WHERE user_id=$user_id ORDER BY UExpenseID DESC");
        if($result->num_rows > 0){
            ?>
            <div class="container">
                <!--Creating data table-->
                <h2>Personal Expenses</h2>
                <div class="row">
                    <table class="infotable col-sm-12">
                </div>
                    <thead>
                        <tr>
                            <!-- <th class="tab-head">ID</th> -->
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
                    ?>
                                             
                        <tr>
                            <!-- <td class="tab-items"> <?php echo $row['UExpenseID']; ?> </td> -->
                            <td class="tab-items"> <?php echo $row['descr']; ?> </td>
                            <td class="tab-items"> <?php echo $row['amount']; ?> </td>
                            <td class="tab-items"> <?php echo $row['category']; ?> </td>
                            <td class="tab-items"> <?php echo $row['ds']; ?> </td>
                            <td class="tab-items"> <?php echo $row['ts']; ?> </td>
                            <td class="tab-items">
                                <div class="row">
                                    <?php if($row['HExpenseID']==NULL){?>
                                    <div class="col-sm-6">
                                        <a href="home.php?edit=<?php echo $row['UExpenseID']; ?>"><i class="fa-solid fa-pen"></i></a>
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="process.php?delete=<?php echo $row['UExpenseID']; ?>"><i class="fa-solid fa-trash"></i></a> 
                                    </div>  
                                    <?php } ?>    
                                </div>
                            </td>
                        </tr>
                    
                            
                    <!--Ending the Loop-->
                    <?php endwhile; ?>
                </table>
            </div>
        <?php }else{
            echo "<h3>No Expense Added Yet</h3>";
        }?>

        <?php
             $total = $con->query("SELECT SUM(amount) as totalamount FROM userexpenses WHERE user_id=$user_id");
             $totalamount = $total->fetch_assoc();
        ?>
        <h3>Total Spent: <?php echo $totalamount['totalamount'] ?></h3>
        
        
        <?php
        //function to print fetched array
        function pre_r($array){
            echo '<pre>';
            print_r($array);
            echo '</pre>';
        }
        ?>



<!--ADD Amount Form -->
<form action="process.php" method="POST">
    <div class="container">
        <input type="hidden" name="UExpenseID" value="<?php echo $UExpenseID ?>">
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
                    <!-- <div class="col-sm-2">
                        <div class="form-group">
                            <input class="form-input" type="text" name="category" value="<?php echo $category; ?>" required>
                            <label class="form-label" for="category">Category</label>
                        </div>
                    </div> -->
                    <div class="col-sm-4">
                        <div class="dropdown form-group">
                            <!-- <label class="form-label" for="category">Category</label> -->
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
                                <div class="options" onclick="show('Food')">Food</div>
                                <div class="options" onclick="show('Transportation')">Transportation</div>
                                <div class="options" onclick="show('Medical')">Medical</div>
                                <div class="options" onclick="show('Education')">Education</div>
                                <div class="options" onclick="show('Entertainment')">Entertainment</div>
                                <div class="options" onclick="show('Clothings')">Clothings</div>
                                <div class="options" onclick="show('Others')">Others</div>
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
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        <script>
            // let options = document.querySelectorAll('.option > .options');
            // for (let elem of options) {
                //     elem.onclick=function(){
                    //         document.querySelector('.form-label-cat').classList.add('input-valid');
                    //         function show(anything){
                        //             document.querySelector('.textBox').value=anything;
                        //         }
            //     }
            // }
            function show(anything){
                document.querySelector('.textBox').value=anything;
                document.querySelector('.form-label-cat').classList.add('input-valid');
            }
            
            document.querySelector('.dropdown').onclick=function(){
                document.querySelector('.dropdown').classList.toggle('active');
            }
            
            </script>
            <div class="graph container">
                <div id="donutchart" class="col-xs-12 col-sm-6 col-md-4">
                    <div id="chart"></div>
                    <div id="labelOverlay"><h3>Total Spent</h3></div>
                </div>
            </div>
    </body>
    </html>