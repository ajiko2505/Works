
<?php
include("header.php");
?>

<style>
body {
    min-height: 100vh;
    background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
    background-attachment: fixed;
}
.signup-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 90vh;
}
.card.signup-card {
    width: 100%;
    max-width: 480px;
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
.signup-card .card-title {
    font-size: 2.1rem;
    font-weight: 700;
    color: #2c5364;
    margin-bottom: 0.5rem;
}
.signup-card .card-text {
    color: #555;
    margin-bottom: 2rem;
}
.form-floating > label {
    color: #2c5364;
    font-weight: 500;
}
.form-control {
    border-radius: 1.2rem;
    border: 1.5px solid #b0bec5;
    font-size: 1.1rem;
    padding-left: 2.5rem;
    background-color: #f8fafc;
    box-shadow: 0 2px 8px rgba(44,83,100,0.04);
    margin-bottom: 1.2rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-control:focus {
    border-color: #2c5364;
    box-shadow: 0 0 0 2px #2c536433;
    background-color: #fff;
}
.signup-btn {
    width: 100%;
    padding: 0.75rem;
    border-radius: 1.2rem;
    background: linear-gradient(90deg, #0f2027 0%, #2c5364 100%);
    color: #fff;
    font-size: 1.2rem;
    font-weight: 600;
    border: none;
    box-shadow: 0 2px 8px rgba(44,83,100,0.08);
    transition: background 0.2s, box-shadow 0.2s;
}
.signup-btn:hover {
    background: linear-gradient(90deg, #2c5364 0%, #0f2027 100%);
    box-shadow: 0 4px 16px rgba(44,83,100,0.12);
}
.input-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #2c5364;
    font-size: 1.2rem;
    opacity: 0.7;
}
.form-floating {
    position: relative;
}
.danger.text-danger {
    background: #ffeaea;
    border-radius: 0.7rem;
    padding: 0.7rem 1rem;
    margin-bottom: 1rem;
    color: #c0392b;
    font-weight: 500;
    text-align: center;
}
.signup-logo {
    display: block;
    margin: 0 auto 1.2rem auto;
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(44,83,100,0.10);
}
</style>
<div class="signup-container">
  <div class="card signup-card">
    <img src="naf.png" alt="Logo" class="signup-logo" />
    <h1 class="card-title text-center mb-1">Driver Registration</h1>
    <p class="card-text text-center">Please fill up the form below:</p>
    <?php
        if (isset($_GET['usser'])) {
            $error = $_GET['usser'];
            if ($error =="exist") {
                echo '<div class="danger text-danger">User already registered</div>';
            } elseif ($error=="pwdmatch") {
                echo '<div class="danger text-danger">Password does not match</div>';
            }
        }
    ?>
    <form method="POST" action="usersig.php" autocomplete="off">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="fname" name="fname" placeholder="Full Name" required>
            <label for="fname"><i class="input-icon bi bi-person"></i>Full Name</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="rank" name="rank" placeholder="Enter Rank" required>
            <label for="rank"><i class="input-icon bi bi-award"></i>Rank</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="snumber" name="snumber" placeholder="Enter Service Number" required>
            <label for="snumber"><i class="input-icon bi bi-hash"></i>Service Number</label>
        </div>
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="mail" name="mail" placeholder="Email" required>
            <label for="mail"><i class="input-icon bi bi-envelope"></i>Email</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="uname" name="uname" placeholder="Username" required>
            <label for="uname"><i class="input-icon bi bi-person-badge"></i>Username</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            <label for="password"><i class="input-icon bi bi-lock"></i>Password</label>
        </div>
        <div class="form-floating mb-4">
            <input type="password" class="form-control" id="c_password" name="c_password" placeholder="Confirm Password" required>
            <label for="c_password"><i class="input-icon bi bi-lock-fill"></i>Confirm Password</label>
        </div>
        <button class="signup-btn" type="submit" name="submit">Register</button>
    </form>
  </div>
</div>
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
