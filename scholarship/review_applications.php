<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 2) {
    header('location: login.php');
    exit();
}

include 'server.php'; // connect to DB

// Handle approve/reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['application_id'])) {
    $status = $_POST['action'] === 'approve' ? 'Approved' : 'Rejected';
    $appId = $_POST['application_id'];

    $sqlUpdate = "UPDATE applications SET status = ? WHERE id = ?";
    $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
    mysqli_stmt_bind_param($stmtUpdate, "si", $status, $appId);
    mysqli_stmt_execute($stmtUpdate);
}

// Fetch all applications with user and scholarship info
$sql = "SELECT 
            a.id AS app_id, 
            u.name AS student_name, 
            u.email, 
            s.title AS scholarship_title, 
            a.status, 
            a.applied_at
        FROM applications a
        JOIN users u ON a.user_id = u.id
        JOIN scholarships s ON a.scholarship_id = s.id
        ORDER BY a.applied_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Review Applications</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <h2>Review Scholarship Applications</h2>
</div>
<div class="container">
    <a href="dashboard.php">← Back to Dashboard</a><br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Student Name</th>
            <th>Email</th>
            <th>Scholarship</th>
            <th>Status</th>
            <th>Applied At</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['student_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['scholarship_title']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['applied_at']) ?></td>
            <td>
                <?php if ($row['status'] == 'Pending'): ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="application_id" value="<?= $row['app_id'] ?>">
                        <button type="submit" name="action" value="approve">Approve</button>
                        <button type="submit" name="action" value="reject">Reject</button>
                    </form>
                <?php else: ?>
                    <em><?= $row['status'] ?></em>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
