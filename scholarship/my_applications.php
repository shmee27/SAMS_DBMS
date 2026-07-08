<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 3) {
    die("Access denied.");
}

$db = mysqli_connect('localhost', 'root', '', 'scholarship_db');
$username = $_SESSION['username'];

$res = mysqli_query($db, "SELECT StudentID FROM student WHERE Email = '$username'");
$row = mysqli_fetch_assoc($res);
$student_id = $row['StudentID'];

$apps = mysqli_query($db, "
  SELECT a.ApplicationID, s.Name AS Scholarship, a.Status, a.ApplicationDate
  FROM Application a
  JOIN Scholarship s ON a.ScholarshipID = s.ScholarshipID
  WHERE a.StudentID = $student_id
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Applications</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header"><h2>My Scholarship Applications</h2></div>
<div class="container">
  <table>
    <tr>
      <th>Scholarship</th>
      <th>Status</th>
      <th>Date Applied</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($apps)): ?>
    <tr>
      <td><?= htmlspecialchars($row['Scholarship']) ?></td>
      <td><?= $row['Status'] ?></td>
      <td><?= date('Y-m-d', strtotime($row['ApplicationDate'])) ?></td>
    </tr>
    <?php endwhile ?>
  </table>
  <br><a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
