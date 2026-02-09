
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
        max-width: 520px;
        animation: fadeInUp 0.6s ease-out;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .auth-logo {
        width: 90px;
        height: 90px;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
        border-radius: 50%;
        color: #fff;
        font-size: 2.5rem;
    }

    .auth-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #0f2027;
        margin-bottom: 0.5rem;
    }

    .auth-subtitle {
        color: #718096;
        font-size: 1rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group-inline {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .input-group-prepend-icon {
        position: relative;
    }

    .input-group-prepend-icon i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #546de5;
        font-size: 1.1rem;
    }

    .input-group-prepend-icon .form-control {
        padding-left: 2.5rem;
    }

    .password-strength {
        margin-top: 0.5rem;
        height: 4px;
        background: #e0e7ff;
        border-radius: 2px;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        width: 0;
        transition: width 0.3s ease, background-color 0.3s ease;
    }

    .form-text-small {
        font-size: 0.8rem;
        color: #a0aec0;
        margin-top: 0.3rem;
    }

    .link-to-login {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e0e7ff;
    }

    .link-to-login a {
        color: #546de5;
        text-decoration: none;
        font-weight: 600;
    }

    .link-to-login a:hover {
        text-decoration: underline;
    }

    .form-error {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #7f1d1d;
        padding: 0.75rem 1rem;
        border-radius: 0.8rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-error i {
        font-size: 1.2rem;
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

</style>

<div class="auth-container">
    <div class="card auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="bi bi-person-badge"></i>
            </div>
            <h1 class="auth-title">Create Account</h1>
            <p class="auth-subtitle">Join AMVRS ARMED System</p>
        </div>

        <?php
        if (isset($_GET['usser'])) {
            $error = $_GET['usser'];
            $message = '';
            $icon = 'exclamation-circle';

            if ($error == "exist") {
                $message = 'This username, email, or service number is already registered';
            } elseif ($error == "pwdmatch") {
                $message = 'Passwords do not match. Please try again';
            } elseif ($error == "pwdweak") {
                $message = 'Password must be at least 8 characters with uppercase, lowercase, and numbers';
            } elseif ($error == "invalid") {
                $message = 'Please fill in all required fields correctly';
            } elseif ($error == "dberror") {
                $message = 'Database error. Please try again later';
            }

            if (!empty($message)) {
                echo '<div class="form-error">';
                echo '<i class="bi bi-' . $icon . '"></i>';
                echo '<span>' . htmlspecialchars($message) . '</span>';
                echo '</div>';
            }
        }
        ?>

        <form method="POST" action="usersig.php" autocomplete="off" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">

            <div class="form-group-inline">
                <div class="form-group input-group-prepend-icon">
                    <i class="bi bi-person"></i>
                    <input type="text" class="form-control form-control-lg" id="fname" name="fname" placeholder="Full Name" required>
                    <div class="form-text-small">Your full name</div>
                </div>

                <div class="form-group input-group-prepend-icon">
                    <i class="bi bi-award"></i>
                    <input type="text" class="form-control form-control-lg" id="rank" name="rank" placeholder="Rank" required>
                    <div class="form-text-small">Military rank</div>
                </div>
            </div>

            <div class="form-group-inline">
                <div class="form-group input-group-prepend-icon">
                    <i class="bi bi-hash"></i>
                    <input type="text" class="form-control form-control-lg" id="snumber" name="snumber" placeholder="Service Number" required>
                    <div class="form-text-small">Service/ID number</div>
                </div>

                <div class="form-group input-group-prepend-icon">
                    <i class="bi bi-envelope"></i>
                    <input type="email" class="form-control form-control-lg" id="mail" name="mail" placeholder="Email" required>
                    <div class="form-text-small">Official email</div>
                </div>
            </div>

            <div class="form-group input-group-prepend-icon">
                <i class="bi bi-person-badge"></i>
                <input type="text" class="form-control form-control-lg" id="uname" name="uname" placeholder="Username" required>
                <div class="form-text-small">3-20 characters, alphanumeric</div>
            </div>

            <div class="form-group input-group-prepend-icon">
                <i class="bi bi-lock"></i>
                <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" required onkeyup="checkPasswordStrength(this.value)">
                <div class="password-strength">
                    <div class="password-strength-bar" id="strengthBar"></div>
                </div>
                <div class="form-text-small">Min 8 chars: upper, lower, number</div>
            </div>

            <div class="form-group input-group-prepend-icon">
                <i class="bi bi-lock-fill"></i>
                <input type="password" class="form-control form-control-lg" id="c_password" name="c_password" placeholder="Confirm Password" required>
            </div>

            <button class="btn btn-primary w-100 btn-lg py-2" type="submit" name="submit">
                <i class="bi bi-check-circle me-2"></i> Create Account
            </button>

            <div class="link-to-login">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </form>
    </div>
</div>

<script>
function checkPasswordStrength(password) {
    let strength = 0;
    const strengthBar = document.getElementById('strengthBar');
    
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    const colors = ['#e0e7ff', '#f87171', '#fbbf24', '#a3e635', '#22c55e'];
    const widths = ['0%', '20%', '40%', '60%', '100%'];
    
    strengthBar.style.width = widths[strength];
    strengthBar.style.backgroundColor = colors[strength];
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const pwd = document.getElementById('password').value;
    const cpwd = document.getElementById('c_password').value;
    
    if (pwd !== cpwd) {
        e.preventDefault();
        alert('Passwords do not match!');
    }
});
</script>

<script src="bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
