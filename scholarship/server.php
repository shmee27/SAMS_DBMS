<?php
session_start();

// Initialize variables
$username = "";
$email = "";
$errors = array();

// Connect to database
$db = mysqli_connect('localhost', 'root', '', 'scholarship_db');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  $username   = mysqli_real_escape_string($db, $_POST['username']);
  $email      = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
  $role_id    = intval($_POST['role_id']);

  // Form validation
  if (empty($username)) array_push($errors, "Username is required");
  if (empty($email)) array_push($errors, "Email is required");
  if (empty($password_1)) array_push($errors, "Password is required");
  if ($password_1 != $password_2) array_push($errors, "Passwords do not match");

  // Check for existing user
  $user_check = mysqli_query($db, "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1");
  $user = mysqli_fetch_assoc($user_check);

  if ($user) {
    if ($user['username'] === $username) array_push($errors, "Username already exists");
    if ($user['email'] === $email) array_push($errors, "Email already exists");
  }

  // Register user
  if (count($errors) == 0) {
    $password = password_hash($password_1, PASSWORD_DEFAULT);
    mysqli_query($db, "
      INSERT INTO users (Username, Email, PasswordHash, RoleID)
      VALUES ('$username', '$email', '$password', $role_id)
    ");

    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role_id;

    // Get new UserID
    $uid_res = mysqli_query($db, "SELECT UserID FROM users WHERE username = '$username'");
    $uid_row = mysqli_fetch_assoc($uid_res);
    $_SESSION['user_id'] = $uid_row['UserID'];

    // Redirect by role
    if ($role_id == 3) {
      header('location: student_profile.php');
    } else {
      header('location: dashboard.php');
    }
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) array_push($errors, "Username is required");
  if (empty($password)) array_push($errors, "Password is required");

  if (count($errors) == 0) {
    $query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['PasswordHash'])) {
      $_SESSION['username'] = $user['Username'];
      $_SESSION['role'] = $user['RoleID'];
      $_SESSION['user_id'] = $user['UserID'];

      if ($user['RoleID'] == 3) {
        header('location: student_profile.php');
      } else {
        header('location: dashboard.php');
      }
    } else {
      array_push($errors, "Wrong username/password combination");
    }
  }
}
?>
