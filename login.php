<?php
include("header.php");
include("csrf.php");
?>

<style>
    .auth-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 90vh;
        padding: 2rem 0;
    }

    .auth-card {
        width: 100%;
        max-width: 480px;
        animation: fadeInUp 0.6s ease-out;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .auth-logo {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
        border-radius: 50%;
        color: #fff;
        font-size: 3rem;
        box-shadow: 0 8px 24px rgba(44, 83, 100, 0.2);
    }

    .auth-title {
        font-size: 2rem;
        font-weight: 700;
        color: #0f2027;
        margin-bottom: 0.5rem;
    }

    .auth-subtitle {
        color: #718096;
        font-size: 1rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .input-group-prepend-icon {
        position: relative;
    }

    .input-group-prepend-icon i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #546de5;
        font-size: 1.2rem;
        z-index: 1;
    }

    .input-group-prepend-icon .form-control {
        padding-left: 2.8rem;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        font-size: 1.05rem;
        border: 1.5px solid #cbd5e1;
        border-radius: 0.8rem;
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    .input-group-prepend-icon .form-control:focus {
        border-color: #546de5;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(84, 109, 229, 0.1);
    }

    .input-group-prepend-icon .form-control::placeholder {
        color: #cbd5e1;
    }

    .form-error {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #7f1d1d;
        padding: 0.875rem 1rem;
        border-radius: 0.8rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideIn 0.3s ease-out;
    }

    .form-error i {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .checkbox-group input[type="checkbox"] {
        width: 1.1rem;
        height: 1.1rem;
        cursor: pointer;
        accent-color: #546de5;
    }

    .checkbox-group label {
        cursor: pointer;
        color: #718096;
        margin: 0;
        user-select: none;
    }

    .forgot-link {
        color: #546de5;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .forgot-link:hover {
        text-decoration: underline;
    }

    .auth-button {
        width: 100%;
        padding: 0.875rem;
        border: none;
        border-radius: 0.8rem;
        font-size: 1.05rem;
        font-weight: 600;
        background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(44, 83, 100, 0.2);
        margin-bottom: 1rem;
    }

    .auth-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(44, 83, 100, 0.3);
    }

    .auth-button:active {
        transform: translateY(0);
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
        color: #cbd5e1;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .divider span {
        padding: 0 0.75rem;
        font-size: 0.85rem;
        color: #a0aec0;
    }

    .auth-links {
        text-align: center;
        padding-top: 1.5rem;
        border-top: 1px solid #e0e7ff;
    }

    .auth-links a {
        color: #546de5;
        text-decoration: none;
        font-weight: 600;
    }

    .auth-links a:hover {
        text-decoration: underline;
    }

    .auth-link-item {
        color: #718096;
        margin: 0 0.25rem;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

</style>

<div class="auth-container">
    <div class="card auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h1 class="auth-title">User Login</h1>
            <p class="auth-subtitle">Access AMVRS ARMED System</p>
        </div>

        <?php
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            if ($error == "pass" || $error == "user") {
                echo '<div class="form-error">';
                echo '<i class="bi bi-exclamation-circle-fill"></i>';
                echo '<span>Invalid username or password. Please try again</span>';
                echo '</div>';
            }
        }
        ?>

        <form method="POST" action="userlog.php" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">

            <div class="form-group input-group-prepend-icon">
                <i class="bi bi-person-circle"></i>
                <input 
                    type="text" 
                    class="form-control" 
                    id="email" 
                    name="email" 
                    placeholder="Username or Email" 
                    required
                    autocomplete="username"
                >
            </div>

            <div class="form-group input-group-prepend-icon">
                <i class="bi bi-lock"></i>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    placeholder="Password" 
                    required
                    autocomplete="current-password"
                >
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Remember me</label>
            </div>

            <button class="auth-button" type="submit" name="submit">
                <i class="bi bi-check-circle me-2"></i> Login Now
            </button>

            <div class="divider">
                <span>New to AMVRS</span>
            </div>

            <div class="auth-links">
                <a href="signup.php" class="d-flex align-items-center justify-content-center gap-2 mb-3">
                    <i class="bi bi-person-plus"></i> Create New Account
                </a>
                <p class="mb-0">
                    <a href="index.php">
                        <i class="bi bi-arrow-left me-1"></i> Go Back Home
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script src="bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
