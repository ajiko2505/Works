<?php
include("header.php");
include("helpers.php");
require_once("database.php");

// Check if user is logged in
if (!isset($_SESSION['user_type'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$user_data = query_row("SELECT * FROM users WHERE id = ?", [$user_id]);

if (!$user_data) {
    header("Location: logout.php");
    exit;
}

$full_name = htmlspecialchars($user_data['Full_name'] ?? '', ENT_QUOTES, 'UTF-8');
$rank = htmlspecialchars($user_data['rank'] ?? '', ENT_QUOTES, 'UTF-8');
$snumber = htmlspecialchars($user_data['snumber'] ?? '', ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($user_data['email'] ?? '', ENT_QUOTES, 'UTF-8');
$username = htmlspecialchars($user_data['username'] ?? '', ENT_QUOTES, 'UTF-8');
$user_type = htmlspecialchars($user_data['user_type'] ?? 'user', ENT_QUOTES, 'UTF-8');
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
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .profile-card {
        background: #fff;
        border-radius: 1.2rem;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        animation: fadeInUp 0.6s ease-out;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #546de5 0%, #5f7df4 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 3rem;
        margin: 0 auto 1.5rem;
        box-shadow: 0 4px 12px rgba(84, 109, 229, 0.2);
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f2027;
        text-align: center;
        margin-bottom: 0.5rem;
    }

    .profile-role {
        text-align: center;
        color: #718096;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        text-transform: capitalize;
    }

    .profile-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 1.5rem 0;
    }

    .info-section {
        background: #fff;
        border-radius: 1.2rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #0f2027;
        margin-bottom: 1.5rem;
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

    .info-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .info-field {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.85rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .info-value {
        font-size: 1.1rem;
        color: #0f2027;
        font-weight: 500;
        word-break: break-all;
    }

    .edit-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #546de5 0%, #5f7df4 100%);
        color: #fff;
        text-decoration: none;
        border-radius: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }

    .edit-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(84, 109, 229, 0.3);
        color: #fff;
        text-decoration: none;
    }

    .logout-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: #ef4444;
        color: #fff;
        text-decoration: none;
        border-radius: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 1rem;
        margin-top: 1rem;
    }

    .logout-button:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        color: #fff;
        text-decoration: none;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 2rem 0;
        }

        .profile-title {
            font-size: 1.5rem;
        }

        .profile-grid {
            grid-template-columns: 1fr;
        }

        .info-group {
            grid-template-columns: 1fr;
        }
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

<div class="profile-header">
    <div class="profile-container">
        <h1 class="profile-title">
            <i class="bi bi-person-circle"></i> My Profile
        </h1>
        <p class="profile-subtitle">View and manage your account information</p>
    </div>
</div>

<div class="profile-container">
    <?php
    if (isset($_GET['usser'])) {
        $error = $_GET['usser'];
        if ($error == "exist") {
            echo '<div class="alert" style="background: #fee2e2; border: 1px solid #fca5a5; color: #7f1d1d; padding: 1rem; border-radius: 0.8rem; margin-bottom: 1.5rem; display: flex; gap: 0.75rem;">';
            echo '<i class="bi bi-exclamation-circle-fill"></i>';
            echo '<span>User already registered with this information</span>';
            echo '</div>';
        } elseif ($error == "pwdmatch") {
            echo '<div class="alert" style="background: #fee2e2; border: 1px solid #fca5a5; color: #7f1d1d; padding: 1rem; border-radius: 0.8rem; margin-bottom: 1.5rem; display: flex; gap: 0.75rem;">';
            echo '<i class="bi bi-exclamation-circle-fill"></i>';
            echo '<span>Passwords do not match</span>';
            echo '</div>';
        }
    }
    ?>

    <div class="profile-grid">
        <div class="profile-card">
            <div class="profile-avatar">
                <i class="bi bi-person-badge"></i>
            </div>
            <h2 class="profile-name"><?php echo $full_name; ?></h2>
            <p class="profile-role"><?php echo $user_type; ?></p>
            <div class="profile-divider"></div>
            <div class="action-buttons">
                <a href="profup.php" class="edit-button">
                    <i class="bi bi-pencil"></i> Edit Profile
                </a>
                <a href="logout.php" class="logout-button">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>

        <div class="profile-card">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #0f2027; margin-bottom: 1rem;">Quick Info</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8fafc; border-radius: 0.6rem;">
                    <i class="bi bi-envelope" style="color: #546de5; font-size: 1.2rem;"></i>
                    <div style="flex: 1;">
                        <div style="font-size: 0.8rem; color: #718096; font-weight: 600;">EMAIL</div>
                        <div style="color: #0f2027; font-weight: 500;"><?php echo $email; ?></div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8fafc; border-radius: 0.6rem;">
                    <i class="bi bi-person-badge" style="color: #546de5; font-size: 1.2rem;"></i>
                    <div style="flex: 1;">
                        <div style="font-size: 0.8rem; color: #718096; font-weight: 600;">USERNAME</div>
                        <div style="color: #0f2027; font-weight: 500;"><?php echo $username; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <h3 class="section-title">
            <i class="bi bi-shield-check"></i> Account Details
        </h3>
        <div class="info-group">
            <div class="info-field">
                <span class="info-label">
                    <i class="bi bi-person" style="color: #546de5; margin-right: 0.5rem;"></i> Full Name
                </span>
                <span class="info-value"><?php echo $full_name; ?></span>
            </div>
            <div class="info-field">
                <span class="info-label">
                    <i class="bi bi-award" style="color: #546de5; margin-right: 0.5rem;"></i> Rank
                </span>
                <span class="info-value"><?php echo $rank; ?></span>
            </div>
            <div class="info-field">
                <span class="info-label">
                    <i class="bi bi-hash" style="color: #546de5; margin-right: 0.5rem;"></i> Service Number
                </span>
                <span class="info-value"><?php echo $snumber; ?></span>
            </div>
            <div class="info-field">
                <span class="info-label">
                    <i class="bi bi-envelope" style="color: #546de5; margin-right: 0.5rem;"></i> Email Address
                </span>
                <span class="info-value"><?php echo $email; ?></span>
            </div>
            <div class="info-field">
                <span class="info-label">
                    <i class="bi bi-person-circle" style="color: #546de5; margin-right: 0.5rem;"></i> Username
                </span>
                <span class="info-value"><?php echo $username; ?></span>
            </div>
            <div class="info-field">
                <span class="info-label">
                    <i class="bi bi-shield-lock" style="color: #546de5; margin-right: 0.5rem;"></i> Account Type
                </span>
                <span class="info-value" style="text-transform: capitalize;"><?php echo $user_type; ?></span>
            </div>
        </div>
    </div>

</div>

<script src="bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
