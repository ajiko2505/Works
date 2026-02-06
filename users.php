<?php
include("header.php");
include("database.php");
$sql = "SELECT * FROM users WHERE user_type ='user'";
$result = mysqli_query($dbh, $sql);
$resultCheck = mysqli_num_rows($result);
?>
<style>
body {
    min-height: 100vh;
    background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
    background-attachment: fixed;
}
                            .users-container {
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                min-height: 90vh;
                            }
                            .card.users-card {
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
                            .users-card .card-title {
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
                            </style>
                            <div class="users-container">
                                <div class="card users-card">
                                    <h1 class="card-title mb-4">Available Drivers</h1>
                                    <?php
                                    $sql = "SELECT * FROM users WHERE user_type ='user'";
                                    $result = mysqli_query($dbh, $sql);
                                    $resultCheck = mysqli_num_rows($result);
                                    if ($resultCheck > 0) {
                                        echo '<div class="table-responsive"><table class="table table-bordered">
                                            <thead class="table-light">
                                            <tr>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Username</th>
                                            </tr>
                                            </thead><tbody>';
                                        while ($umm = mysqli_fetch_assoc($result)) {
                                            $Full_name  = $umm['Full_name'];
                                            $username  = $umm['username'];
                                            $email = $umm['email'];
                                            echo '<tr>';
                                            echo '<td>'.$Full_name.'</td>';
                                            echo '<td>'.$email.'</td>';
                                            echo '<td>'.$username.'</td>';
                                            echo '</tr>';
                                        }
                                        echo '</tbody></table></div>';
                                    } else {
                                        echo '<div class="text-center my-3 py-2">';
                                        echo '<h3 class="mb-3">No Driver Available</h3>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <script src="bootstrap.bundle.min.js"></script>
                            <script src="js/scripts.js"></script>
