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
.preview-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 90vh;
}
.card.preview-card {
    width: 100%;
    max-width: 900px;
    border-radius: 1.5rem;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
    padding: 2.5rem 2.5rem 2rem 2.5rem;
    margin: 2rem 0;
    background: #fff;
    border: none;
    animation: fadeIn 1s;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}
.preview-card .card-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c5364;
    margin-bottom: 0.5rem;
    text-align: center;
}
.vehicle-img {
    width: 100%;
    max-width: 400px;
    border-radius: 1.2rem;
    box-shadow: 0 4px 16px rgba(44,83,100,0.10);
    margin-bottom: 1.5rem;
}
.vehicle-details {
    font-size: 1.1rem;
    color: #2c5364;
    margin-bottom: 1.2rem;
}
.mission-form textarea {
    border-radius: 1.2rem;
    border: 1.5px solid #b0bec5;
    font-size: 1.1rem;
    background-color: #f8fafc;
    box-shadow: 0 2px 8px rgba(44,83,100,0.04);
    margin-bottom: 1.2rem;
    width: 100%;
    padding: 0.7rem 1rem;
}
.request-btn {
    min-width: 120px;
    border-radius: 1rem;
    font-weight: 600;
    font-size: 1.1rem;
    margin-top: 0.5rem;
}
</style>
<div class="preview-container">
    <div class="card preview-card">
        <?php
        if (isset($_GET['id'])) {
            $user_id = $_SESSION['id'];
            $id = $_GET['id'];
            $sql = "SELECT * FROM vechicle where vech_id = '$id'";
            $result = mysqli_query($dbh, $sql);
            $resultCheck = mysqli_num_rows($result);
            if ($resultCheck > 0) {
                $umm = mysqli_fetch_assoc($result);
                $vech_name  = $umm['vech_name'];
                $vech_id  = $umm['vech_id'];
                $vech_color  = $umm['vech_color'];
                $category  = $umm['category'];
                $vech_img  = $umm['vech_img'];
                $vech_desc  = $umm['vech_desc'];
                echo '<h1 class="card-title mb-4">'.$vech_name.'</h1>';
                echo '<div class="row align-items-center">';
                echo '<div class="col-md-5 text-center"><img class="vehicle-img" src="images/'.$vech_img.'" alt="Vehicle Image" /></div>';
                echo '<div class="col-md-7">';
                echo '<div class="vehicle-details"><strong>Color:</strong> '.$vech_color.'</div>';
                echo '<div class="vehicle-details"><strong>Category:</strong> '.$category.'</div>';
                echo '<div class="vehicle-details"><strong>Description:</strong> '.$vech_desc.'</div>';
                echo '</div></div>';
                echo '<hr/>';
                echo '<form class="mission-form" action="userrequest.php" method="post">';
                echo '<input type="hidden" name="a" value="'.$user_id.'">';
                echo '<input type="hidden" name="id" value="'.$id.'">';
                echo '<label for="mission" class="profile-label">State Mission</label>';
                echo '<textarea name="mission" id="mission" rows="4" class="form-control" placeholder="State mission" required></textarea>';
                echo '<button type="submit" name="submit" class="btn btn-secondary request-btn">Request</button>';
                echo '</form>';
            } else {
                echo '<div class="text-center my-3 py-2"><h3 class="mb-3">Vehicle not found</h3></div>';
            }
        }
        ?>
    </div>
</div>
<script src="bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
