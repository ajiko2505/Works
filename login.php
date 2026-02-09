
<?php
include("header.php");
include("csrf.php");
?>
<style>
body {
    min-height: 100vh;
    background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
    background-attachment: fixed;
}
.login-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 90vh;
}
.card.login-card {
    width: 100%;
    max-width: 420px;
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
.login-card .card-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c5364;
    margin-bottom: 0.5rem;
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
.login-btn {
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
.login-btn:hover {
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
.login-logo {
    display: block;
    margin: 0 auto 1.2rem auto;
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(44,83,100,0.10);
}
.login-links {
    text-align: center;
    margin-top: 1.2rem;
    font-size: 1rem;
}
.login-links a {
    color: #2c5364;
    text-decoration: none;
    font-weight: 600;
    margin: 0 0.5rem;
    transition: color 0.2s;
}
.login-links a:hover {
    color: #0f2027;
    text-decoration: underline;
}
</style>
<div class="login-container">
  <div class="card login-card">
    <img src="naf.png" alt="Logo" class="login-logo" />
    <h1 class="card-title text-center mb-3">User Login</h1>
    <?php
      if (isset($_GET['error'])) {
        $value = $_GET['error'];
        if ($value=="pass" || $value=="user" ) {
          echo '<div class="danger text-danger">Invalid credentials</div>';
        }
      }
    ?>
    <form method="POST" action="userlog.php" autocomplete="off">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="email" name="email" placeholder="Username" required>
        <label for="email"><i class="input-icon bi bi-person"></i>Username</label>
      </div>
      <div class="form-floating mb-4">
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        <label for="password"><i class="input-icon bi bi-lock"></i>Password</label>
      </div>
      <button class="login-btn" type="submit" name="submit">Login</button>
    </form>
    <div class="login-links mt-3">
      <span>Don't have an account?</span>
      <a href="signup.php">Register Now!</a>
      <br />
      <a href="index.php">Go Back</a>
    </div>
  </div>
</div>
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
