<?php
include("header.php");
include("helpers.php");
include("csrf.php");
require_once("database.php");

// Check if user is logged in
if (!isset($_SESSION['user_type']) || !isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Validate CSRF token
    if (!validate_csrf($_POST['csrf_token'] ?? '')) {
        log_action('CSRF_VIOLATION', 'profile_update_attempt_failed', []);
        header("Location: profile.php?error=csrf");
        exit;
    }

    // Validate and sanitize inputs
    $email = validate_email($_POST['mail'] ?? '');
    $rank = validate_string($_POST['rank'] ?? '', 50);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$email) {
        log_action('FORM_VALIDATION_ERROR', 'invalid_email_format', []);
        header("Location: profile.php?error=invalid_email");
        exit;
    }

    if ($rank === false) {
        log_action('FORM_VALIDATION_ERROR', 'invalid_rank_format', []);
        header("Location: profile.php?error=invalid_rank");
        exit;
    }

    // Check if email is already in use by another user
    $existing_user = query_row(
        "SELECT id FROM users WHERE email = ? AND id != ?",
        [$email, $user_id]
    );

    if ($existing_user) {
        log_action('PROFILE_UPDATE_FAILED', 'email_already_in_use', ['user_id' => $user_id]);
        header("Location: profile.php?error=email_exists");
        exit;
    }

    // Update basic information
    $update_result = execute(
        "UPDATE users SET email = ?, rank = ? WHERE id = ?",
        [$email, $rank, $user_id]
    );

    if (!$update_result) {
        log_action('PROFILE_UPDATE_FAILED', 'database_error', ['user_id' => $user_id]);
        header("Location: profile.php?error=db_error");
        exit;
    }

    // If password change requested, validate and update
    if (!empty($password)) {
        if (empty($confirm_password)) {
            log_action('PROFILE_UPDATE', 'password_mismatch', ['user_id' => $user_id]);
            header("Location: profile.php?error=password_mismatch");
            exit;
        }

        if ($password !== $confirm_password) {
            log_action('PROFILE_UPDATE', 'password_mismatch_confirmed', ['user_id' => $user_id]);
            header("Location: profile.php?error=password_mismatch");
            exit;
        }

        $validation = validate_password($password);
        if ($validation !== true) {
            log_action('PROFILE_UPDATE', 'weak_password', ['user_id' => $user_id]);
            header("Location: profile.php?error=weak_password");
            exit;
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $pwd_result = execute(
            "UPDATE users SET password = ? WHERE id = ?",
            [$hashed_password, $user_id]
        );

        if (!$pwd_result) {
            log_action('PROFILE_UPDATE', 'password_update_failed', ['user_id' => $user_id]);
            header("Location: profile.php?error=pwd_update_failed");
            exit;
        }
    }

    log_action('PROFILE_UPDATED', 'profile_information_updated', [
        'user_id' => $user_id,
        'email_changed' => true,
        'rank_changed' => true,
        'password_changed' => !empty($password)
    ]);

    header("Location: profile.php?success=updated");
    exit;
}

// Get user data
$user_data = query_row("SELECT * FROM users WHERE id = ?", [$user_id]);

if (!$user_data) {
    header("Location: logout.php");
    exit;
}

$full_name = htmlspecialchars($user_data['Full_name'] ?? '', ENT_QUOTES, 'UTF-8');
$rank = htmlspecialchars($user_data['rank'] ?? '', ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($user_data['email'] ?? '', ENT_QUOTES, 'UTF-8');
?>

<style>
    .profile-header {
        background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
        color: #fff;
        padding: 3rem 0;
        margin-bottom: 2rem;
    }

    .profile-title {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .profile-subtitle {
        color: #cbd5e1;
        margin-top: 0.5rem;
    }

    .profile-container {
        max-width: 700px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .form-section {
        background: #fff;
        border-radius: 1.2rem;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #0f2027;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .section-title i {
        color: #546de5;
        font-size: 1.3rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: #0f2027;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .input-group-icon {
        position: relative;
    }

    .input-group-icon i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #546de5;
        font-size: 1.1rem;
        z-index: 1;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.8rem;
        font-size: 1rem;
        border: 1.5px solid #cbd5e1;
        border-radius: 0.8rem;
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #546de5;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(84, 109, 229, 0.1);
        outline: none;
    }

    .form-text-small {
        font-size: 0.8rem;
        color: #718096;
        margin-top: 0.3rem;
    }

    .password-update-section {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #e2e8f0;
    }

    .password-update-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #0f2027;
        margin-bottom: 1.5rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 0.8rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .alert-danger {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #7f1d1d;
    }

    .alert i {
        font-size: 1.2rem;
        flex-shrink: 0;
        margin-top: 0.1rem;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-update {
        flex: 1;
        padding: 0.875rem;
        background: linear-gradient(135deg, #546de5 0%, #5f7df4 100%);
        color: #fff;
        border: none;
        border-radius: 0.8rem;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(84, 109, 229, 0.3);
    }

    .btn-cancel {
        flex: 1;
        padding: 0.875rem;
        background: #e2e8f0;
        color: #0f2027;
        border: none;
        border-radius: 0.8rem;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-cancel:hover {
        background: #cbd5e1;
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 2rem 0;
        }

        .profile-title {
            font-size: 1.5rem;
        }

        .form-section {
            padding: 1.5rem;
        }

        .form-group-grid {
            grid-template-columns: 1fr;
        }

        .button-group {
            flex-direction: column;
        }
    }

</style>

<div class="profile-header">
    <div class="profile-container">
        <h1 class="profile-title">
            <i class="bi bi-pencil-square"></i> Edit Profile
        </h1>
        <p class="profile-subtitle">Update your account information and password</p>
    </div>
</div>

<div class="profile-container">
    <?php
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        $msg = '';
        
        if ($error == 'email_exists') {
            $msg = 'This email is already in use by another account';
        } elseif ($error == 'password_mismatch') {
            $msg = 'Passwords do not match. Please try again';
        } elseif ($error == 'weak_password') {
            $msg = 'Password must be at least 8 characters with uppercase, lowercase, and numbers';
        } elseif ($error == 'invalid_email') {
            $msg = 'Please provide a valid email address';
        } elseif ($error == 'invalid_rank') {
            $msg = 'Invalid rank format';
        } elseif ($error == 'csrf') {
            $msg = 'Security verification failed. Please try again';
        }
        
        if (!empty($msg)) {
            echo '<div class="alert alert-danger">';
            echo '<i class="bi bi-exclamation-circle-fill"></i>';
            echo '<span>' . htmlspecialchars($msg) . '</span>';
            echo '</div>';
        }
    }
    ?>

    <div class="form-section">
        <h2 class="section-title">
            <i class="bi bi-shield-check"></i> Personal Information
        </h2>

        <form method="POST" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">

            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-person" style="color: #546de5; margin-right: 0.5rem;"></i> Full Name
                </label>
                <input 
                    type="text" 
                    class="form-control" 
                    value="<?php echo $full_name; ?>" 
                    readonly 
                    style="background: #f0f4f8; cursor: not-allowed;"
                >
                <div class="form-text-small">Cannot be changed</div>
            </div>

            <div class="form-group-grid">
                <div class="form-group input-group-icon">
                    <label class="form-label">
                        <i class="bi bi-envelope" style="color: #546de5; margin-right: 0.5rem;"></i> Email
                    </label>
                    <i class="bi bi-envelope"></i>
                    <input 
                        type="email" 
                        name="mail" 
                        class="form-control" 
                        value="<?php echo $email; ?>" 
                        placeholder="your@email.com"
                        required
                    >
                </div>

                <div class="form-group input-group-icon">
                    <label class="form-label">
                        <i class="bi bi-award" style="color: #546de5; margin-right: 0.5rem;"></i> Rank
                    </label>
                    <i class="bi bi-award"></i>
                    <input 
                        type="text" 
                        name="rank" 
                        class="form-control" 
                        value="<?php echo $rank; ?>" 
                        placeholder="Your rank"
                        required
                    >
                </div>
            </div>

            <div class="password-update-section">
                <h3 class="password-update-title">
                    <i class="bi bi-lock" style="color: #546de5; margin-right: 0.5rem;"></i> Change Password (Optional)
                </h3>
                <p style="color: #718096; font-size: 0.9rem; margin-bottom: 1rem;">
                    Leave blank if you don't want to change your password
                </p>

                <div class="form-group-grid">
                    <div class="form-group input-group-icon">
                        <label class="form-label">
                            <i class="bi bi-lock" style="color: #546de5; margin-right: 0.5rem;"></i> New Password
                        </label>
                        <i class="bi bi-lock"></i>
                        <input 
                            type="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="New password"
                            autocomplete="new-password"
                        >
                        <div class="form-text-small">Min 8 chars: upper, lower, number</div>
                    </div>

                    <div class="form-group input-group-icon">
                        <label class="form-label">
                            <i class="bi bi-lock-fill" style="color: #546de5; margin-right: 0.5rem;"></i> Confirm Password
                        </label>
                        <i class="bi bi-lock-fill"></i>
                        <input 
                            type="password" 
                            name="confirm_password" 
                            class="form-control" 
                            placeholder="Confirm password"
                            autocomplete="new-password"
                        >
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button type="submit" name="submit" class="btn-update">
                    <i class="bi bi-check-circle"></i> Save Changes
                </button>
                <a href="profile.php" class="btn-cancel" style="text-decoration: none; color: inherit;">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>

</div>

<script src="bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
