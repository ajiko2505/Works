<?php
include("header.php");
include("database.php");
?>
            <?php
         
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $user_id = $_GET['a'];
                $sql = "SELECT * FROM vechicle where vech_id = '$id'";
                $result = mysqli_query($dbh, $sql);
                $resultCheck = mysqli_num_rows($result);
                if ($resultCheck > 0) {
                    while ($umm = mysqli_fetch_assoc($result)) {
                        $vech_name  = $umm['vech_name'];
                        $vech_id  = $umm['vech_id'];
                        $vech_color  = $umm['vech_color'];
                        $category  = $umm['category'];
                        $vech_img  = $umm['vech_img'];
                        $vech_desc  = $umm['vech_desc'];
                      
                      
                    }
                    $sql = "SELECT * FROM users where id = '$user_id'";
                    $result = mysqli_query($dbh, $sql);
                    $resultCheck = mysqli_num_rows($result);
                    if ($resultCheck > 0) {
                        while ($umm = mysqli_fetch_assoc($result)) {
                            $Full_name  = $umm['Full_name'];
                            $rank  = $umm['rank'];
                          
                          
                        }
                    }
                        $sql = "SELECT * FROM request where user_id = '$user_id'";
                    $result = mysqli_query($dbh, $sql);
                    $resultCheck = mysqli_num_rows($result);
                    if ($resultCheck > 0) {
                        while ($umm = mysqli_fetch_assoc($result)) {
                            $mission  = $umm['mission'];
                          
                          
                        }
                    }

                    echo '
                   
                      <div class="container-fluid px-4 px-lg-5">
        <div class="card text-white bg-secondary my-5 py-2 text-center">
                      <div class="card-body"><p class="text-white m-0">'.$vech_name.'</p><p>'.$vech_desc.'</p></div>
            </div>
                      <div class="row gx-4 gx-lg-5 align-items-center my-5">
                <div class="col-lg-5">
                <img class="img-fluid rounded mb-4 mb-lg-0" src="images/'.$vech_img.'" alt="..." />
                </div>
                <div class="col-lg-7">
                    <h1 class="font-weight-light text-center">Information about User about Request </h1>
                    <form action="reqapp.php" method="post">
                    <table class="table table-bordered table-responsive text-center">
                    <tr>
                        <th>Driver name</th>
                        <th>Rank</th>
                        <th>Mission</th>
                        <th>Day allocate</th>
                        <th colspan="2">Action</th>
                        
                    </tr>
                    <input type="text" name="a" value='.$user_id.' hidden>
                    <input type="text" name="id" value='.$id.' hidden>
                    <tr>
                      <td>'.$Full_name.'</td>
                      <td>'.$rank.'</td>
                      <td>'.$mission.'</td>
                      <td>
                      <input type="number" class="form-control" name="day" placeholder="enter number of days" value="1" required>
                      </td>
                      <td><button type="submit" name="submit" class="btn btn-success">Approve</button></td>
                      <td><button type="submit" name="submitc" class="btn btn-danger">Cancel</button></td>
                      </tr>
                     
                      ';
            }
            }
        // }
           
                ?>
            
            <div class="col-lg-6">
            <!-- <form action="userrequest.php" method="post">
                <input type="text" name="a" value="<?= $user_id?>" hidden>
                <input type="text" name="id" value="<?= $id?>" hidden>
            
            </div>
                    <button type="submit" name="submit" class="btn btn-secondary">Request</button>
                </form> -->
            </div>
            </div>
            </div>
        </div>
        <script src="bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
    
</html>
