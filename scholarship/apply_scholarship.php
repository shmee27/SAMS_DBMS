<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 3) {
    die("Access denied. Students only.");
}

$db = mysqli_connect('localhost', 'root', '', 'scholarship_db');
$username = $_SESSION['username'];

$res = mysqli_query($db, "SELECT StudentID FROM student WHERE Email = '$username'");
$student = mysqli_fetch_assoc($res);
$student_id = $student['StudentID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scholarship_id = intval($_POST['scholarship_id']);
    $essay = mysqli_real_escape_string($db, $_POST['essay']);

    mysqli_query($db, "
      INSERT INTO Application (StudentID, ScholarshipID, EssayText)
      VALUES ($student_id, $scholarship_id, '$essay')
    ");

    echo "<p>Application submitted successfully!</p>";
}

$scholarships = mysqli_query($db, "
  SELECT ScholarshipID, Name, Amount
  FROM Scholarship
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apply for Scholarship</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header"><h2>Apply for a Scholarship</h2></div>
<div class="container">
<form method="post">
  <div class="input-group">
    <label>Scholarship</label>
    <select name="scholarship_id" required>
      <?php while ($s = mysqli_fetch_assoc($scholarships)): ?>
        <option value="<?= $s['ScholarshipID'] ?>">
          <?= htmlspecialchars($s['Name']) ?> — $<?= $s['Amount'] ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="input-group">
    <label>Essay</label>
    <textarea name="essay" rows="5" required></textarea>
  </div>
  <div class="input-group">
    <button type="submit" class="btn">Submit Application</button>
  </div>
</form>
<a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
