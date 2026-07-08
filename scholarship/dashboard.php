<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
  <h2>Welcome, <?= $_SESSION['username'] ?>!</h2>
</div>
<div class="container">
  <?php if ($_SESSION['role'] == 1): ?>
    <p>You are logged in as <strong>Admin</strong>.</p>
    <a href="manage_scholarships.php">Manage Scholarships</a>
  <?php elseif ($_SESSION['role'] == 2): ?>
    <p>You are logged in as <strong>Reviewer</strong>.</p>
    <a href="review_applications.php">Review Applications</a>
  <?php elseif ($_SESSION['role'] == 3): ?>
    <p>You are logged in as <strong>Student</strong>.</p>
    <a href="apply_scholarship.php">Apply for Scholarships</a>
  <?php endif; ?>
  <br><a href="logout.php">Logout</a>
</div>
</body>
</html>
