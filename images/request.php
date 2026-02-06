<?php
include("header.php");
include("database.php");
?>
 <div class="container px-4 px-lg-5">
            
                    <?php
                            $sql = "SELECT * FROM request WHERE status = 'pending'";
                            $result = mysqli_query($dbh, $sql);
                            $resultCheck = mysqli_num_rows($result);
                            if ($resultCheck > 0) {
                                echo '
                                <div class="my-3 py-2 text-center">
                <div class=""><h3 class="m-0" >USER REQUEST FOR VECHICLES </h3></div>';
                if (isset($_GET['req'])) {
                    $error = $_GET['req'];
                    if ($error =="msg") {
                        echo '
                        <div class="success text-success"><h1 class="m-0" >Driver request approved, emailed sent to driver  </h1></div>
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
                                    <th>ID</th>
                                    <th>Vname</th>
                                    <th>Driver</th>
                                    <th>Mission</th>
                                    <th>Action</th>
                                    
                                </tr>
                                ';
                                while ($umm = mysqli_fetch_assoc($result)) {		
                                    $vech_name  = $umm['vech_name'];
                                    $vech_id  = $umm['vech_id'];
                                    $user_id = $umm['user_id'];
                                    $username = $umm['username'];
                                    $mission = $umm['mission'];
                                  echo '
                                  <tr>
                                  <td>'.$vech_id.'</td>
                                  <td>'.$vech_name.'</td>
                                  <td>'.$username.'</td>
                                  <td>'.$mission.'</td>
                                  <td><a class="btn btn-success btn-lg" href="reqapp.php?id='.$vech_id.'&&a='.$user_id.'">Approve</a></td>
                                </tr>
                                  ';
                                }
                            }else {
                                echo '
                                <div class="my-3 py-2 text-center">
                                <div class=""><h3 class="m-0" >NO VECHICLE REQUEST</h3></div>';
                                if (isset($_GET['req'])) {
                                    $error = $_GET['req'];
                                    if ($error =="msg") {
                                        echo '
                                        <div class="success text-success"><h1 class="m-0" >Driver request approved, emailed sent to driver  </h1></div>
                                        ';
                                    }
                                }
                           echo ' </div>
                                ';
                            }
                        
                            
                               

                            
                            ?>
                            </table>
                </div>
              
                
                
                </div>
            </div>
        </div>
        <script src="bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
