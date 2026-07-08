<?php
session_start();
if ($_SESSION['role'] != 1) die("Admins only.");

$db = mysqli_connect('localhost', 'root', '', 'scholarship_db');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $amount = floatval($_POST['amount']);
    $desc = mysqli_real_escape_string($db, $_POST['description']);

    mysqli_query($db, "
      INSERT INTO Scholarship (Name, Amount, Description)
      VALUES ('$name', $amount, '$desc')
    ");
}

// Fetch all scholarships
$all = mysqli_query($db, "SELECT * FROM Scholarship");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Scholarships</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header"><h2>Manage Scholarships</h2></div>
<div class="container">
  <form method="post">
    <div class="input-group">
      <label>Name</label>
      <input name="name" required>
    </div>
    <div class="input-group">
      <label>Amount</label>
      <input type="number" step="0.01" name="amount" required>
    </div>
    <div class="input-group">
      <label>Description</label>
      <textarea name="description" rows="3" required></textarea>
    </div>
    <div class="input-group">
      <button class="btn" type="submit">Add Scholarship</button>
    </div>
  </form>

  <h3>Existing Scholarships</h3>
  <ul>
    <?php while ($s = mysqli_fetch_assoc($all)): ?>
      <li>
        <strong><?= htmlspecialchars($s['Name']) ?></strong> — $<?= $s['Amount'] ?><br>
        <em><?= htmlspecialchars($s['Description']) ?></em>
      </li>
    <?php endwhile ?>
  </ul>
  <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
