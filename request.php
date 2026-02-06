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
.request-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 90vh;
}
.card.request-card {
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
                    .request-card .card-title {
                        font-size: 2rem;
                        font-weight: 700;
                        color: #2c5364;
                        margin-bottom: 0.5rem;
                        text-align: center;
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
                            <h1 class="card-title mb-4">User Requests for Vehicles</h1>
                            <?php
                            $sql = "SELECT * FROM request WHERE status = 'pending'";
                            $result = mysqli_query($dbh, $sql);
                            $resultCheck = mysqli_num_rows($result);
                            if ($resultCheck > 0) {
                                if (isset($_GET['req'])) {
                                    $error = $_GET['req'];
                                    if ($error == "msg") {
                                        echo '<div class="success text-success">Driver request approved, email sent to driver</div>';
                                    } elseif ($error == "msgf") {
                                        echo '<div class="danger text-danger">Failed to approve, check network connection</div>';
                                    }
                                }
                                echo '<div class="table-responsive"><table class="table table-bordered">
                                    <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Vehicle Name</th>
                                        <th>Driver</th>
                                        <th>Mission</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead><tbody>';
                                while ($umm = mysqli_fetch_assoc($result)) {
                                    $vech_name  = $umm['vech_name'];
                                    $vech_id  = $umm['vech_id'];
                                    $user_id = $umm['user_id'];
                                    $username = $umm['username'];
                                    $mission = $umm['mission'];
                                    echo '<tr>';
                                    echo '<td>'.$vech_id.'</td>';
                                    echo '<td>'.$vech_name.'</td>';
                                    echo '<td>'.$username.'</td>';
                                    echo '<td>'.$mission.'</td>';
                                    echo '<td><a class="btn btn-success request-btn" href="reqpre.php?id='.$vech_id.'&a='.$user_id.'">View</a></td>';
                                    echo '</tr>';
                                }
                                echo '</tbody></table></div>';
                            } else {
                                echo '<div class="text-center my-3 py-2">';
                                echo '<h3 class="mb-3">No Vehicle Request</h3>';
                                if (isset($_GET['req'])) {
                                    $error = $_GET['req'];
                                    if ($error == "msgp") {
                                        echo '<div class="success text-success">Driver request approved, email sent to driver</div>';
                                    } elseif ($error == "cancel") {
                                        echo '<div class="success text-success">Request was Cancelled</div>';
                                    }
                                }
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <script src="bootstrap.bundle.min.js"></script>
                    <script src="js/scripts.js"></script>
