echo '
echo '
echo '
echo '

<?php
include("header.php");
include("database.php");
?>
<style>
body {
    min-height: 100vh;
    background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
    background-attachment: fixed;
}
.main-section {
    padding: 2.5rem 0 2rem 0;
}
.vehicle-card {
    border-radius: 1.5rem;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
    transition: transform 0.18s, box-shadow 0.18s;
    border: none;
    background: #fff;
    margin-bottom: 2rem;
}
.vehicle-card:hover {
    transform: translateY(-6px) scale(1.03);
    box-shadow: 0 16px 40px 0 rgba(44,83,100,0.18);
}
.vehicle-img {
    width: 100%;
    max-width: 220px;
    height: 140px;
    object-fit: cover;
    border-radius: 1rem;
    margin: 0 auto 1rem auto;
    display: block;
    box-shadow: 0 2px 8px rgba(44,83,100,0.10);
}
.vehicle-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2c5364;
    margin-bottom: 0.5rem;
}
.vehicle-btn {
    background: linear-gradient(90deg, #0f2027 0%, #2c5364 100%);
    color: #fff;
    border-radius: 1.2rem;
    font-weight: 600;
    font-size: 1.1rem;
    padding: 0.5rem 1.5rem;
    border: none;
    transition: background 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(44,83,100,0.08);
}
.vehicle-btn:hover {
    background: linear-gradient(90deg, #2c5364 0%, #0f2027 100%);
    box-shadow: 0 4px 16px rgba(44,83,100,0.12);
}
.danger.text-danger, .success.text-success {
    background: #ffeaea;
    border-radius: 0.7rem;
    padding: 0.7rem 1rem;
    margin-bottom: 1rem;
    color: #c0392b;
    font-weight: 500;
    text-align: center;
}
.success.text-success {
    background: #eaffea;
    color: #27ae60;
}
.main-logo {
    display: block;
    margin: 0 auto 1.2rem auto;
    width: 90px;
    height: 90px;
    object-fit: contain;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(44,83,100,0.10);
}
.login-cards {
    display: flex;
    justify-content: center;
    gap: 3rem;
    margin-top: 3rem;
}
.login-card {
    background: #fff;
    border-radius: 1.5rem;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.12);
    padding: 2rem 2.5rem 1.5rem 2.5rem;
    text-align: center;
    transition: transform 0.18s, box-shadow 0.18s;
    border: none;
}
.login-card:hover {
    transform: translateY(-6px) scale(1.03);
    box-shadow: 0 16px 40px 0 rgba(44,83,100,0.18);
}
.login-card img {
    width: 120px;
    height: 120px;
    object-fit: contain;
    border-radius: 50%;
    margin-bottom: 1rem;
}
.login-card h2 {
    font-size: 1.3rem;
    color: #2c5364;
    font-weight: 700;
}
</style>
<div class="main-section container">
  <img src="naf.png" alt="Logo" class="main-logo" />
  <?php
  if (isset($_SESSION['user_type'])) {
    $user_id = $_SESSION['id'];
    $sql = "SELECT * FROM vech_allocated WHERE user_id ='$user_id'";
    $result = mysqli_query($dbh, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        if ($rows = mysqli_fetch_assoc($result)) {
            $duedate = $rows['due_date'];
            $date = Date('Y-m-d');
            if ($date  > $duedate  ) {
                header("Location: chk.php");
            }
        }
    }
    $sql = "SELECT * FROM vechicle WHERE status ='free'";
    $result = mysqli_query($dbh, $sql);
    $resultCheck = mysqli_num_rows($result);
    echo '<div class="text-center mb-4"><h2 class="m-0" >Available Vehicles</h2></div>';
    if (isset($_GET['user'])) {
      $error = $_GET['user'];
      if ($error =="request") {
        echo '<div class="danger text-danger">User has a pending request</div>';
      } elseif ($error=="success") {
        echo '<div class="success text-success">User request is accepted and waiting Administrative action, you will receive email notification</div>';
      } elseif ($error=="admin") {
        echo '<div class="danger text-danger">Admin cannot make request</div>';
      }
    }
    echo '<div class="row justify-content-center">';
    if ($resultCheck > 0) {
      while ($umm = mysqli_fetch_assoc($result)) {
        $id = $umm['id'];
        $vech_name  = $umm['vech_name'];
        $vech_id  = $umm['vech_id'];
        $vech_color  = $umm['vech_color'];
        $category  = $umm['category'];
        $vech_img  = $umm['vech_img'];
        echo '<div class="col-md-4 d-flex align-items-stretch">';
        echo '<div class="card vehicle-card w-100">';
        echo '<div class="card-body text-center">';
        echo '<h5 class="vehicle-title">'.$vech_name.'</h5>';
        echo '<img class="vehicle-img" src="images/'.$vech_img.'" alt="'.$vech_name.'" />';
        echo '<div class="mt-3"><a class="vehicle-btn btn" href="preview.php?id='.$vech_id.'">PREVIEW</a></div>';
        echo '</div></div></div>';
      }
    } else {
      echo '<div class="my-3 py-2 text-center w-100"><h3 class="m-0">No Available Vehicles</h3></div>';
    }
    echo '</div>';
  } else {
    echo '<div class="login-cards">';
    echo '<div class="login-card">';
    echo '<a href="login.php"><img src="driver.png" alt="Driver Login" /><h2>Driver Login</h2></a>';
    echo '</div>';
    echo '<div class="login-card">';
    echo '<a id="admin-link" href="login.php"><img src="admin.png" alt="Admin Login" /><h2>Admin Login</h2></a>';
    echo '</div>';
    echo '</div>';
  }
  ?>
</div>
<script src="bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
    </body>
</html>







