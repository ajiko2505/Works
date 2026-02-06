<?php
include("header.php");
include("database.php");
?>
 <div class="container px-4 px-lg-5">
            
                    <?php
                    $date = Date('Y-m-d');
                            $sql = "SELECT * FROM request WHERE '$date' > return_date";
                            $result = mysqli_query($dbh, $sql);
                            $resultCheck = mysqli_num_rows($result);
                            if ($resultCheck > 0) {
                                echo '
                                <div class="my-3 py-2 text-center">
                <div class=""><h3 class="m-0" >OVERDUE USERS</h3></div>
                ';
                
                echo'
            </div>
            <!-- Content Row-->
            <div class="row gx-4 gx-lg-5">
                <div class="col-md-2 mb-5">
                
                </div>
                <div class="col-md-8 mb-7 table-responsive">
                <table class="table table-bordered ">
                                <tr>
                                    <th>Driver Name</th>
                                    <th>Vechicle ID</th>
                                    <th>Overdue Date</th>
                                </tr>
                                ';
                                $umm = mysqli_fetch_assoc($result);
                                    		
                                $user  = $umm['username'];
                                  $vech_id  = $umm['vech_id'];
                                  $return_date  = $umm['return_date'];
                                  echo '
                                  <tr>
                                  <td>'.$user.'</td>
                                  <td>'.$vech_id.'</td>
                                   <td>'.$return_date.'</td>
                                </tr></table>
                                  ';
                                
                            }else {
                                echo '
                                <div class="my-3 py-2 text-center">
                                <div class=""><h3 class="m-0" >NO OVERDUE USERS</h3>
                                ';
                                
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
