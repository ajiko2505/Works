<?php
include("header.php");
include("database.php");
$id = $_SESSION['id'];
$sql = "SELECT * FROM users where id = '$id'";
$result = mysqli_query($dbh, $sql);
$resultCheck = mysqli_num_rows($result);
if ($resultCheck > 0) {
    $umm = mysqli_fetch_assoc($result);
    $Full_name  = $umm['Full_name'];
    $rank  = $umm['rank'];
    $snumber  = $umm['snumber'];
    $email  = $umm['email'];
    $user  = $umm['username'];
}
                            $email  = $umm['email'];
                            $user  = $umm['username'];
                        // removed stray closing brace
                        ?>
                        <style>
                        body {
                            min-height: 100vh;
                            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
                            background-attachment: fixed;
                        }
                        .profile-container {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            min-height: 90vh;
                        }
                        .card.profile-card {
                            width: 100%;
                            max-width: 520px;
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
                        .profile-card .card-title {
                            font-size: 2rem;
                            font-weight: 700;
                            color: #2c5364;
                            margin-bottom: 0.5rem;
                            text-align: center;
                        }
                        .profile-field {
                            margin-bottom: 1.5rem;
                        }
                        .profile-label {
                            font-size: 1.1rem;
                            color: #2c5364;
                            font-weight: 600;
                            margin-bottom: 0.3rem;
                            display: block;
                        }
                        .profile-input {
                            width: 100%;
                            border-radius: 1.2rem;
                            border: 1.5px solid #b0bec5;
                            font-size: 1.1rem;
                            background-color: #f8fafc;
                            box-shadow: 0 2px 8px rgba(44,83,100,0.04);
                            padding: 0.7rem 1rem;
                            color: #2c5364;
                            font-weight: 500;
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
                        <div class="profile-container">
                            <div class="card profile-card">
                                <h1 class="card-title mb-4">User Profile</h1>
                                <?php
                                if (isset($_GET['usser'])) {
                                    $error = $_GET['usser'];
                                    if ($error == "exist") {
                                        echo '<div class="danger text-danger">User already registered</div>';
                                    } elseif ($error == "pwdmatch") {
                                        echo '<div class="danger text-danger">Password does not match</div>';
                                    }
                                }
                                ?>
                                <form method="POST" action="profup.php" autocomplete="off">
                                    <div class="profile-field">
                                        <label class="profile-label" for="fname">Full Name</label>
                                        <input class="profile-input" id="fname" type="text" name="fname" value="<?=$Full_name?>" required disabled />
                                    </div>
                                    <div class="profile-field">
                                        <label class="profile-label" for="rank">Rank</label>
                                        <input class="profile-input" id="rank" type="text" name="rank" value="<?=$rank?>" required disabled />
                                    </div>
                                    <div class="profile-field">
                                        <label class="profile-label" for="snumber">Service Number</label>
                                        <input class="profile-input" id="snumber" type="text" name="snumber" value="<?=$snumber?>" required disabled />
                                    </div>
                                    <div class="profile-field">
                                        <label class="profile-label" for="email">Email</label>
                                        <input class="profile-input" id="email" type="email" name="mail" value="<?=$email?>" required disabled />
                                    </div>
                                    <div class="profile-field">
                                        <label class="profile-label" for="uname">Username</label>
                                        <input class="profile-input" id="uname" type="text" name="uname" value="<?=$user?>" required disabled />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <script src="bootstrap.bundle.min.js"></script>
                        <script src="js/scripts.js"></script>
