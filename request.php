<?php
include("header.php");
include("helpers.php");
require_once("database.php");

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.php");
    exit;
}
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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        border-color: #546de5;
        box-shadow: 0 4px 12px rgba(84, 109, 229, 0.1);
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #546de5;
    }

    .stat-label {
        color: #718096;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    .alert-info {
        background: #dbeafe;
        border: 1px solid #7dd3fc;
        color: #0c4a6e;
        padding: 1rem;
        border-radius: 0.8rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: #dcfce7;
        border: 1px solid #86efac;
        color: #166534;
    }

    .alert-warning {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        color: #92400e;
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

    .table {
        margin-bottom: 0;
    }

    .table thead {
        background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
        color: #fff;
    }

    .table th {
        border-bottom: 2px solid #546de5;
        padding: 1rem;
        font-weight: 600;
        text-align: left;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: background 0.2s ease;
    }

    .table tbody tr:hover {
        background: #f8fafc;
    }

    .badge-pending {
        background: #fef3c7;
        color: #92400e;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge-pending i {
        font-size: 0.9rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-view {
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #546de5 0%, #5f7df4 100%);
        color: #fff;
        text-decoration: none;
        border-radius: 0.6rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(84, 109, 229, 0.3);
        color: #fff;
        text-decoration: none;
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

    .no-requests {
        display: grid;
        place-content: center;
        min-height: 300px;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 0;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .table {
            font-size: 0.9rem;
        }

        .table th, .table td {
            padding: 0.75rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-view {
            width: 100%;
            justify-content: center;
        }
    }

</style>

<div class="page-header">
    <div class="requests-container">
        <h1 class="page-title">
            <i class="bi bi-inbox"></i> Vehicle Requests Management
        </h1>
        <p class="page-subtitle">Review and manage pending vehicle requests from drivers</p>
    </div>
</div>

<div class="requests-container">
    <?php
    // Display messages
    if (isset($_GET['req'])) {
        $req = $_GET['req'];
        if ($req == "msg" || $req == "msgp") {
            echo '<div class="alert-info alert-success">';
            echo '<i class="bi bi-check-circle"></i>';
            echo '<span>Vehicle request approved successfully. Email notification sent to driver</span>';
            echo '</div>';
        } elseif ($req == "msgf") {
            echo '<div class="alert-info alert-warning">';
            echo '<i class="bi bi-exclamation-circle"></i>';
            echo '<span>Failed to process request. Please check your network connection</span>';
            echo '</div>';
        } elseif ($req == "cancel") {
            echo '<div class="alert-info alert-success">';
            echo '<i class="bi bi-check-circle"></i>';
            echo '<span>Request has been cancelled successfully</span>';
            echo '</div>';
        }
    }

    // Get pending requests count
    $pending_count = query_count("SELECT COUNT(*) as cnt FROM request WHERE status = 'pending'");
    $total_count = query_count("SELECT COUNT(*) as cnt FROM request");

    // Display stats
    echo '<div class="stats-grid">';
    echo '<div class="stat-card">';
    echo '<div class="stat-number">' . htmlspecialchars($pending_count) . '</div>';
    echo '<div class="stat-label">Pending Requests</div>';
    echo '</div>';
    echo '<div class="stat-card">';
    echo '<div class="stat-number">' . htmlspecialchars($total_count) . '</div>';
    echo '<div class="stat-label">Total Requests</div>';
    echo '</div>';
    echo '</div>';

    // Get pending requests
    $requests = query_all("SELECT r.*, u.fname as driver_name FROM request r 
                          JOIN users u ON r.user_id = u.id 
                          WHERE r.status = 'pending' 
                          ORDER BY r.id DESC");

    echo '<div class="requests-section">';
    echo '<h2 class="section-title">';
    echo '<i class="bi bi-hourglass-split"></i> Pending Requests';
    echo '</h2>';

    if ($pending_count > 0) {
        echo '<div class="table-responsive">';
        echo '<table class="table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Request ID</th>';
        echo '<th>Vehicle Name</th>';
        echo '<th>Driver</th>';
        echo '<th>Mission</th>';
        echo '<th>Status</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($requests as $request) {
            echo '<tr>';
            echo '<td><strong>#' . htmlspecialchars($request['vech_id']) . '</strong></td>';
            echo '<td>' . htmlspecialchars($request['vech_name']) . '</td>';
            echo '<td>' . htmlspecialchars($request['username']) . '</td>';
            echo '<td>' . htmlspecialchars(substr($request['mission'], 0, 30)) . '...</td>';
            echo '<td><span class="badge-pending"><i class="bi bi-hourglass-split"></i> Pending</span></td>';
            echo '<td class="action-buttons">';
            echo '<a class="btn-view" href="reqpre.php?id=' . htmlspecialchars($request['vech_id']) . '&a=' . htmlspecialchars($request['user_id']) . '">';
            echo '<i class="bi bi-eye"></i> Review';
            echo '</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<div class="no-requests">';
        echo '<div class="empty-state">';
        echo '<div class="empty-state-icon">';
        echo '<i class="bi bi-inbox"></i>';
        echo '</div>';
        echo '<h3 class="empty-state-title">No Pending Requests</h3>';
        echo '<p class="empty-state-desc">All vehicle requests have been processed</p>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
    ?>

</div>

<script src="bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
