<?php
// header("Refresh:5 url='myrequest.php'");
include("header.php");
include("database.php");
$user_id = $_SESSION['id'];
?>
<style>
body {
    min-height: 100vh;
    background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
    background-attachment: fixed;
}
.request-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 90vh;
}
.card.request-card {
    width: 100%;
    max-width: 700px;
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
.request-card .card-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c5364;
    margin-bottom: 0.5rem;
}
.table {
    background: #f8fafc;
    border-radius: 1rem;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.table th, .table td {
    vertical-align: middle;
    text-align: center;
    font-size: 1.1rem;
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
.request-btn {
    min-width: 120px;
    border-radius: 1rem;
    font-weight: 600;
    font-size: 1.1rem;
    margin: 0 0.5rem;
}
</style>
<div class="request-container">
    <div class="card request-card">
        <h1 class="card-title text-center mb-3">My Vehicle Request</h1>
        <?php
        $sql = "SELECT * FROM request WHERE user_id ='$user_id'";
        $result = mysqli_query($dbh, $sql);
        $resultCheck = mysqli_num_rows($result);
        if ($resultCheck > 0) {
            if (isset($_GET['req'])) {
                $error = $_GET['req'];
                if ($error == "none") {
                    echo '<div class="danger text-danger">Driver request is pending and can only cancel</div>';
                } elseif ($error == "success") {
                    echo '<div class="success text-success">Driver return request success</div>';
                } elseif ($error == "app") {
                    echo '<div class="danger text-danger">Driver cannot cancel approved request, please return</div>';
                } elseif ($error == "msg") {
                    echo '<div class="danger text-danger">Driver approved request is overdue, please return</div>';
                }
            }
            echo '<div class="table-responsive"><table class="table table-bordered">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Color</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Return</th>
                    <th>Actions</th>
                </tr>
                </thead><tbody>';
            while ($umm = mysqli_fetch_assoc($result)) {
                $vec_id  = $umm['vech_id'];
                $vech_name  = $umm['vech_name'];
                $vech_color  = $umm['vech_col'];
                $category  = $umm['vech_cat'];
                $status  = $umm['status'];
                $return_date  = $umm['return_date'];
                echo '<tr>';
                echo '<td>'.$vec_id.'</td>';
                echo '<td>'.$vech_name.'</td>';
                echo '<td>'.$vech_color.'</td>';
                echo '<td>'.$category.'</td>';
                echo '<td>'.$status.'</td>';
                echo '<td>'.$return_date.'</td>';
                echo '<td>';
                echo '<a class="btn btn-danger request-btn" href="canc.php?id='.$vec_id.'&a=s">Cancel</a>';
                echo '<a class="btn btn-success request-btn" href="review.php?id='.$vec_id.'&a=s">Return</a>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div>';
        } else {
            echo '<div class="text-center my-3 py-2">';
            echo '<h3 class="mb-3">No Vehicle Request</h3>';
            if (isset($_GET['req'])) {
                $error = $_GET['req'];
                if ($error == "success") {
                    echo '<div class="success text-success">Driver vehicle request was successful</div>';
                } elseif ($error == "cancel") {
                    echo '<div class="success text-success">Driver cancelled request</div>';
                }
            }
            echo '</div>';
        }
        ?>
    </div>
</div>
<script src="bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
