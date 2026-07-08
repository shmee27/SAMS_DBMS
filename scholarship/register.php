<?php include('server.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="header">
  <h2>Register</h2>
</div>

<form method="post" action="register.php">
  <?php include('errors.php'); ?>
  <div class="input-group">
    <label>Username</label>
    <input type="text" name="username" value="<?php echo $username; ?>">
  </div>
  <div class="input-group">
    <label>Email</label>
    <input type="email" name="email" value="<?php echo $email; ?>">
  </div>
  <div class="input-group">
    <label>Password</label>
    <input type="password" name="password_1">
  </div>
  <div class="input-group">
    <label>Confirm Password</label>
    <input type="password" name="password_2">
  </div>
  <div class="input-group">
    <label>Role</label>
    <select name="role_id" required>
      <option value="1">Admin</option>
      <option value="2">Reviewer</option>
      <option value="3" selected>Student</option>
    </select>
  </div>
  <div class="input-group">
    <button type="submit" class="btn" name="reg_user">Register</button>
  </div>
  <p>Already a member? <a href="login.php">Sign in</a></p>
</form>
</body>
</html>
