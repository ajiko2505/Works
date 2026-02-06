<?php
// header("Refresh:5 url='myrequest.php'");
include("header.php");
include("database.php");

$user_id = $_SESSION['id'];
    // $sql = "SELECT * FROM vech_allocated WHERE user_id ='$user_id'";
    // $result = mysqli_query($dbh, $sql);
    // $resultCheck = mysqli_num_rows($result);
    // if ($resultCheck > 0) {
    //     if ($rows = mysqli_fetch_assoc($result)) {
           
    //             # code...
    //             $duedate = $rows['due_date'];
    //             // $status = $rows['status'];
    //             // if ($status == "approved") {
    //                $date = Date('Y-m-d');
    //             // $msg = 'your due date is: ';
    //             if ($date > $duedate  ) {
    //                 header("Location: chk.php");
    //             // } 
    //             }
                
                
              
            
            
    //     }
    // }
?>
 <div class="container px-4 px-lg-5">
            
                    <?php
                    $user_id = $_SESSION['id'];
                            $sql = "SELECT * FROM request WHERE user_id ='$user_id'";
                            $result = mysqli_query($dbh, $sql);
                            $resultCheck = mysqli_num_rows($result);
                            if ($resultCheck > 0) {
                                echo '
                                <div class="my-3 py-2 text-center">
                <div class=""><h3 class="m-0" >MY VECHICLE REQUEST</h3></div>
                ';
                if (isset($_GET['req'])) {
                    $error = $_GET['req'];
                    if ($error =="none") {
                        echo '
                        <div class="danger text-danger"><h1 class="m-0" >Driver request is pending and can only cancel</h1></div>
                        ';
                    }elseif ($error =="success") {
                        echo '
                        <div class="success text-success"><h1 class="m-0" >Driver return request success  </h1></div>
                        ';
                    }else if ($error =="app") {
                        echo '
                        <div class="danger text-danger"><h1 class="m-0" >Driver can not cancel approved request, please return  </h1></div>
                        ';
                    }else if ($error =="msg") {
                        echo '
                        <div class="danger text-danger"><h1 class="m-0" >Driver approved request is overdue please return  </h1></div>
                        ';
                    }
                }
                
                echo'
            </div>
            <!-- Content Row-->
            <div class="row gx-4 gx-lg-5">
                <div class="col-md-2 mb-5">
                
                </div>
                <div class="col-md-8 mb-7">
                <table class="table table-bordered table-responsive">
                                <tr>
                                    <th> ID</th>
                                    <th>Name</th>
                                    <th>Color</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Return</th>
                                </tr>
                                ';
                                $umm = mysqli_fetch_assoc($result);
                                    		
                                $user_id  = $umm['user_id'];
                                  $vech_id  = $umm['vech_id'];
                                  $time = $umm['time'];
                                //   $sql = "SELECT * FROM vechicle WHERE vech_id ='$vech_id'";
                                //   $result = mysqli_query($dbh, $sql);
                                //   $resultCheck = mysqli_num_rows($result);
                                //   $umm = mysqli_fetch_assoc($result);
                                  $vec_id  = $umm['vech_id'];
                                  $vech_name  = $umm['vech_name'];
                                  $vech_color  = $umm['vech_col'];
                                  $category  = $umm['vech_cat'];
                                  $status  = $umm['status'];
                                  $return_date  = $umm['return_date'];
                                  echo '
                                  <tr>
                                  <td>'.$vec_id.'</td>
                                  <td>'.$vech_name.'</td>
                                  <td>'.$vech_color.'</td>
                                  <td>'.$category.'</td>
                                   <td>'.$status.'</td>
                                   <td>'.$return_date.'</td>
                                </tr></table>
                                <a class="btn btn-danger btn-lg" href="canc.php?id='.$vech_id.'&&a=s" >Cancel</a>
                                <a class="btn btn-success btn-lg" href="review.php?id='.$vech_id.'&&a=s">return</a>
                                  ';
                                
                            }else {
                                echo '
                                <div class="my-3 py-2 text-center">
                                <div class=""><h3 class="m-0" >NO VECHICLE REQUEST</h3>
                                ';
                                if (isset($_GET['req'])) {
                                    $error = $_GET['req'];
                                    if ($error =="success") {
                                        
                                    //     echo'
                                    //     <script type="text/javascript"> alert("Request  successfully cancelled");</script>
                                    //    ';
                                       echo '
                                        <div class="success text-success"><h1 class="m-0" >Driver vechicle request was successful  </h1></div>
                                        ';
                                    }else if ($error =="cancel") {
                                        echo '
                                        <div class="success text-success"><h1 class="m-0" >Driver cancelled request  </h1></div>
                                        ';
                                    }
                                }
                                echo'
                                </div>
                            </div>
                                ';
                            }
                        
                            
                               

                            
                            ?>
                            
                </div>
              
                
                
                </div>
            </div>
        </div>
        <script src="bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
