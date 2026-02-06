<?php
include("header.php");
include("database.php");
?>

                 
               
<?php
if (isset($_SESSION['user_type'])) {
	$user_id = $_SESSION['id'];
    $sql = "SELECT * FROM vech_allocated WHERE user_id ='$user_id'";
    $result = mysqli_query($dbh, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        if ($rows = mysqli_fetch_assoc($result)) {
           
                # code...
                $duedate = $rows['due_date'];
                // $status = $rows['status'];
                // if ($status == "approved") {
                   $date = Date('Y-m-d');
                if ($date>$duedate  ) {
                    header("Location: chk.php");
                // } 
                }
         
        }
    }
           
                               
    $sql = "SELECT * FROM vechicle WHERE status ='free'";
    $result = mysqli_query($dbh, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        echo '
        <div class="my-3 py-2 text-center">
<div class=""><h2 class="m-0" >Available Vechicles</h2></div>
';
if (isset($_GET['user'])) {
$error = $_GET['user'];
if ($error =="request") {
echo '
<div class="danger text-danger"><h1 class="m-0" >User have a pending request</h1></div>
';
}elseif ($error=="success") {
echo '
<div class="success text-success"><h1 class="m-0" >User request is accepted and waiting Admininstrative action, you will receive email notification</h1></div>
';
}elseif ($error=="admin") {
echo '
<div class="success text-danger"><h1 class="m-0" >Admin can not make request</h1></div>
';
}
}
echo '
</div>
        <div class="row gx-4 gx-lg-5">
        <div class="col-md-2 mb-5">
      
        </div>
        
<div class="col-md-8 mb-7">
<table class="table table-bordered table-responsive text-center">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Color</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        ';
        while ($umm = mysqli_fetch_assoc($result)) {
        $id = $umm['id'];    		
        $vech_name  = $umm['vech_name'];
          $vech_id  = $umm['vech_id'];
          $vech_color  = $umm['vech_color'];
          $category  = $umm['category'];
          echo '
          <tr>
          <td>'.$vech_id.'</td>
          <td>'.$vech_name.'</td>
          <td>'.$vech_color.'</td>
          <td>'.$category.'</td>
          <td><a class="btn btn-success btn-lg" href="userrequest.php?id='.$id.'&&a='.$user_id.'">Request</a></td>
        </tr>
          ';
        }
    }else {
        echo '
        <div class="my-3 py-2 text-center">
        <div class=""><h3 class="m-0" >No Available Vars</h3></div>
    </div>
        ';
    }

                            
                        
}else {
	echo '
	<div id="allTheThings">
			<div id="member">
				<a href="login.php">
					<img src="driver.png" width="250px" height="auto"/><br />
					&nbsp; <h2>Driver Login </h2>
				</a>
			</div>
			<div id="verticalLine">
				<div id="librarian">
					<a id="admin-link" href="login.php">
						<img src="admin.png" width="220px" height="220" /><br />
						&nbsp;&nbsp;&nbsp;<h2>Admin Login</h2>
					</a>
				</div>
			</div>
		</div>';
	
}
?>
		

        <script src="bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>







