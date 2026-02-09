<?php
include("header.php");
include("database.php");
?>
<style>
    .dashboard-hero {
        background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
        color: #fff;
        padding: 3rem 0;
        margin-bottom: 2.5rem;
        border-radius: 1.5rem;
    }

    .dashboard-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .dashboard-title {
        font-size: 2rem;
        font-weight: 700;
    }

    .dashboard-subtitle {
        font-size: 1rem;
        opacity: 0.9;
    }

    .welcome-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1.5rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .vehicle-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .vehicle-status {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        margin: 1rem 0;
        font-size: 0.95rem;
    }

    .vehicle-status i {
        font-size: 1.2rem;
        min-width: 24px;
    }

    .status-available {
        color: #27ae60;
    }

    .status-reserved {
        color: #f39c12;
    }

    .vehicle-specs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin: 1rem 0;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 0.8rem;
    }

    .spec-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .spec-item i {
        color: #546de5;
        font-size: 1.1rem;
    }

    .login-hero {
        text-align: center;
        padding: 3rem 2rem;
    }

    .login-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .login-option {
        background: #fff;
        border-radius: 1.5rem;
        padding: 2.5rem 2rem;
        box-shadow: 0 4px 12px rgba(15, 32, 39, 0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }

    .login-option:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(15, 32, 39, 0.15);
    }

    .login-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
        border-radius: 50%;
        color: #fff;
        font-size: 3rem;
    }

    .login-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f2027;
        margin-bottom: 0.5rem;
    }

    .login-desc {
        color: #718096;
        font-size: 0.95rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-icon {
        font-size: 3rem;
        color: #cbd5e0;
        margin-bottom: 1rem;
    }

    .empty-title {
        font-size: 1.3rem;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: #718096;
    }
</style>

<div class="container py-5">
    <?php
    if (isset($_SESSION['user_type'])) {
        // User is logged in
        $user_id = $_SESSION['id'];

        // Get allocated vehicle info
        $allocated = query_row("SELECT * FROM vech_allocated WHERE user_id = ?", array($user_id));
        
        if ($allocated) {
            $duedate = $allocated['due_date'];
            $date = date('Y-m-d');
            if ($date > $duedate) {
                header("Location: chk.php");
                exit;
            }
        }

        // Display messages
        if (isset($_GET['user'])) {
            $error = $_GET['user'];
            if ($error == "request") {
                echo '<div class="alert alert-warning" role="alert">';
                echo '<i class="bi bi-exclamation-circle me-2"></i> User has a pending request</div>';
            } elseif ($error == "success") {
                echo '<div class="alert alert-success" role="alert">';
                echo '<i class="bi bi-check-circle me-2"></i> Request accepted! You will receive an email notification</div>';
            } elseif ($error == "admin") {
                echo '<div class="alert alert-danger" role="alert">';
                echo '<i class="bi bi-exclamation-triangle me-2"></i> Admin cannot make vehicle requests</div>';
            }
        }

        // Dashboard Hero
        echo '<div class="dashboard-hero mb-5">';
        echo '<div class="dashboard-header">';
        echo '<div>';
        echo '<h1 class="dashboard-title mb-2"><i class="bi bi-car-front"></i> Available Vehicles</h1>';
        echo '<p class="dashboard-subtitle">Select a vehicle to submit your request</p>';
        echo '</div>';
        echo '<div class="welcome-badge"><i class="bi bi-person-circle me-2"></i> ' . htmlspecialchars($_SESSION['user_type'] ?? 'User') . '</div>';
        echo '</div>';
        echo '</div>';

        // Get available vehicles
        $vehicles = query_all("SELECT * FROM vechicle WHERE status = 'free'");

        if (!empty($vehicles)) {
            echo '<div class="vehicle-grid">';
            foreach ($vehicles as $vehicle) {
                echo '<div class="card vehicle-card fade-in-up">';
                echo '<div class="card-header">';
                echo '<h5 class="mb-0">' . htmlspecialchars($vehicle['vech_name']) . '</h5>';
                echo '</div>';
                echo '<div class="card-body">';

                // Vehicle Image
                $image_path = 'images/' . htmlspecialchars($vehicle['vech_img']);
                echo '<img src="' . $image_path . '" alt="' . htmlspecialchars($vehicle['vech_name']) . '" class="vehicle-img mb-3">';

                // Status
                echo '<div class="vehicle-status status-available">';
                echo '<i class="bi bi-check-circle-fill"></i>';
                echo '<span>Available for Request</span>';
                echo '</div>';

                // Specs
                echo '<div class="vehicle-specs">';
                echo '<div class="spec-item"><i class="bi bi-palette"></i> <span>Color: ' . htmlspecialchars($vehicle['vech_color']) . '</span></div>';
                echo '<div class="spec-item"><i class="bi bi-tag"></i> <span>ID: ' . htmlspecialchars($vehicle['vech_id']) . '</span></div>';
                echo '<div class="spec-item"><i class="bi bi-basket"></i> <span>Type: ' . htmlspecialchars($vehicle['category']) . '</span></div>';
                echo '<div class="spec-item"><i class="bi bi-speedometer"></i> <span>Status: Available</span></div>';
                echo '</div>';

                // Description
                if (!empty($vehicle['vech_desc'])) {
                    echo '<p class="text-muted small mb-3">' . htmlspecialchars(substr($vehicle['vech_desc'], 0, 100)) . '...</p>';
                }

                // Actions
                echo '<div class="d-grid gap-2">';
                echo '<a href="preview.php?id=' . htmlspecialchars($vehicle['vech_id']) . '" class="btn btn-primary">';
                echo '<i class="bi bi-eye me-2"></i> View & Request';
                echo '</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<div class="card empty-state">';
            echo '<div class="empty-icon"><i class="bi bi-inbox"></i></div>';
            echo '<h4 class="empty-title">No Vehicles Available</h4>';
            echo '<p class="empty-text">Check back later for available vehicles.</p>';
            echo '</div>';
        }
    } else {
        // User not logged in - show login options
        echo '<div class="login-hero">';
        echo '<img src="naf.png" alt="AMVRS Logo" class="mb-4" style="max-width: 100px; height: auto;">';
        echo '<h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #0f2027;">AMVRS ARMED System</h1>';
        echo '<p class="text-muted" style="font-size: 1.1rem;">Automated Military Vehicle Request System</p>';
        echo '</div>';

        echo '<div class="login-grid">';

        // Driver Login Option
        echo '<a href="login.php" class="login-option">';
        echo '<div class="login-icon">';
        echo '<i class="bi bi-person-badge"></i>';
        echo '</div>';
        echo '<h3 class="login-title">Driver Login</h3>';
        echo '<p class="login-desc">Access your vehicle requests and manage your profile</p>';
        echo '</a>';

        // Admin Login Option
        echo '<a href="login.php" class="login-option">';
        echo '<div class="login-icon">';
        echo '<i class="bi bi-shield-lock"></i>';
        echo '</div>';
        echo '<h3 class="login-title">Admin Login</h3>';
        echo '<p class="login-desc">Manage vehicle fleet and approve requests</p>';
        echo '</a>';

        // Sign Up Option
        echo '<a href="signup.php" class="login-option">';
        echo '<div class="login-icon">';
        echo '<i class="bi bi-person-plus"></i>';
        echo '</div>';
        echo '<h3 class="login-title">New User</h3>';
        echo '<p class="login-desc">Create a new account to get started</p>';
        echo '</a>';

        echo '</div>';
    }
    ?>
</div>

<script src="bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
