<?php
include("header.php");
include("helpers.php");
require_once("database.php");

// Check if user is logged in
if (!isset($_SESSION['user_type']) || !isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
?>

<style>
    .page-header {
        background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
        color: #fff;
        padding: 3rem 0;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-subtitle {
        color: #cbd5e1;
        margin-top: 0.5rem;
    }

    .requests-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 0.8rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-danger {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #7f1d1d;
    }

    .alert-success {
        background: #dcfce7;
        border: 1px solid #86efac;
        color: #166534;
    }

    .alert i {
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .requests-section {
        background: #fff;
        border-radius: 1.2rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 1.4rem;
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

    .request-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .request-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f0f4f8 100%);
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .request-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        border-color: #546de5;
    }

    .request-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }

    .request-id {
        font-size: 0.9rem;
        font-weight: 600;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .request-status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-approved {
        background: #dbeafe;
        color: #0c4a6e;
    }

    .badge-rejected {
        background: #fee2e2;
        color: #7f1d1d;
    }

    .badge-returned {
        background: #dcfce7;
        color: #166534;
    }

    .request-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #0f2027;
        margin-bottom: 0.75rem;
    }

    .request-details {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #475569;
        font-size: 0.95rem;
    }

    .detail-item i {
        color: #546de5;
        font-size: 1rem;
        width: 20px;
    }

    .request-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-action {
        flex: 1;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.6rem;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        text-decoration: none;
        min-height: 38px;
    }

    .btn-cancel {
        background: #ef4444;
        color: #fff;
    }

    .btn-cancel:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }

    .btn-return {
        background: #10b981;
        color: #fff;
    }

    .btn-return:hover {
        background: #059669;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state-icon {
        font-size: 3.5rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .empty-state-desc {
        color: #94a3b8;
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 0;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .request-cards {
            grid-template-columns: 1fr;
        }

        .request-actions {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
        }
    }

</style>

<div class="page-header">
    <div class="requests-container">
        <h1 class="page-title">
            <i class="bi bi-file-text"></i> My Vehicle Requests
        </h1>
        <p class="page-subtitle">View and manage your vehicle requests</p>
    </div>
</div>

<div class="requests-container">
    <?php
    // Display messages
    if (isset($_GET['req'])) {
        $req = $_GET['req'];
        
        if ($req == "success") {
            echo '<div class="alert alert-success">';
            echo '<i class="bi bi-check-circle"></i>';
            echo '<span>Vehicle request submitted successfully</span>';
            echo '</div>';
        } elseif ($req == "cancel") {
            echo '<div class="alert alert-success">';
            echo '<i class="bi bi-check-circle"></i>';
            echo '<span>Request cancelled successfully</span>';
            echo '</div>';
        } elseif ($req == "none") {
            echo '<div class="alert alert-danger">';
            echo '<i class="bi bi-exclamation-circle"></i>';
            echo '<span>Your pending request can only be cancelled, not modified</span>';
            echo '</div>';
        } elseif ($req == "app") {
            echo '<div class="alert alert-danger">';
            echo '<i class="bi bi-exclamation-circle"></i>';
            echo '<span>Cannot cancel approved request. Please return the vehicle</span>';
            echo '</div>';
        } elseif ($req == "msg") {
            echo '<div class="alert alert-danger">';
            echo '<i class="bi bi-exclamation-circle"></i>';
            echo '<span>Your approved request is overdue. Please return the vehicle immediately</span>';
            echo '</div>';
        }
    }

    // Get user's requests
    $requests = query_all(
        "SELECT * FROM request WHERE user_id = ? ORDER BY id DESC",
        [$user_id]
    );

    echo '<div class="requests-section">';
    echo '<h2 class="section-title">';
    echo '<i class="bi bi-list-check"></i> Your Requests';
    echo '</h2>';

    if (!empty($requests)) {
        echo '<div class="request-cards">';

        foreach ($requests as $request) {
            $status = htmlspecialchars($request['status'], ENT_QUOTES, 'UTF-8');
            $vech_id = htmlspecialchars($request['vech_id'], ENT_QUOTES, 'UTF-8');
            $vech_name = htmlspecialchars($request['vech_name'], ENT_QUOTES, 'UTF-8');
            $vech_col = htmlspecialchars($request['vech_col'], ENT_QUOTES, 'UTF-8');
            $return_date = htmlspecialchars($request['return_date'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
            $mission = htmlspecialchars(substr($request['mission'] ?? '', 0, 100), ENT_QUOTES, 'UTF-8');

            // Determine badge class
            $badge_class = 'badge-pending';
            $status_icon = 'hourglass-split';
            
            if ($status === 'approved') {
                $badge_class = 'badge-approved';
                $status_icon = 'check-circle-fill';
            } elseif ($status === 'rejected') {
                $badge_class = 'badge-rejected';
                $status_icon = 'x-circle-fill';
            } elseif ($status === 'returned') {
                $badge_class = 'badge-returned';
                $status_icon = 'check-double';
            }

            echo '<div class="request-card">';
            echo '<div class="request-card-header">';
            echo '<span class="request-id">Request #' . htmlspecialchars($request['id']) . '</span>';
            echo '<span class="request-status-badge ' . $badge_class . '">';
            echo '<i class="bi bi-' . $status_icon . '"></i>';
            echo ucfirst($status);
            echo '</span>';
            echo '</div>';

            echo '<h3 class="request-title">' . $vech_name . '</h3>';

            echo '<div class="request-details">';
            echo '<div class="detail-item">';
            echo '<i class="bi bi-palette"></i>';
            echo '<span><strong>Color:</strong> ' . $vech_col . '</span>';
            echo '</div>';
            echo '<div class="detail-item">';
            echo '<i class="bi bi-calendar-event"></i>';
            echo '<span><strong>Return Date:</strong> ' . $return_date . '</span>';
            echo '</div>';
            echo '<div class="detail-item">';
            echo '<i class="bi bi-briefcase"></i>';
            echo '<span><strong>Mission:</strong> ' . ($mission ? $mission . '...' : 'Not specified') . '</span>';
            echo '</div>';
            echo '</div>';

            echo '<div class="request-actions">';

            // Show action buttons based on status
            if ($status === 'pending') {
                echo '<a class="btn-action btn-cancel" href="canc.php?id=' . htmlspecialchars($vech_id) . '&a=s">';
                echo '<i class="bi bi-x-circle"></i> Cancel';
                echo '</a>';
            } elseif ($status === 'approved') {
                echo '<a class="btn-action btn-return" href="review.php?id=' . htmlspecialchars($vech_id) . '&a=s">';
                echo '<i class="bi bi-arrow-return-left"></i> Return Vehicle';
                echo '</a>';
            } elseif ($status === 'rejected') {
                echo '<span style="padding: 0.5rem 1rem; text-align: center; color: #718096;">Request was rejected</span>';
            } elseif ($status === 'returned') {
                echo '<span style="padding: 0.5rem 1rem; text-align: center; color: #10b981; font-weight: 600;">Vehicle returned</span>';
            }

            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
    } else {
        echo '<div class="empty-state">';
        echo '<div class="empty-state-icon">';
        echo '<i class="bi bi-inbox"></i>';
        echo '</div>';
        echo '<h3 class="empty-state-title">No Vehicle Requests</h3>';
        echo '<p class="empty-state-desc">You haven\'t submitted any vehicle requests yet</p>';
        echo '</div>';
    }

    echo '</div>';
    ?>

</div>

<script src="bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
