<?php
session_start();

// Access control
if (!isset($_SESSION['username']) || $_SESSION['role'] != 3) {
    die("Access denied. Students only.");
}

$db = mysqli_connect('localhost', 'root', '', 'scholarship_db');
$username = $_SESSION['username'];

$result = mysqli_query($db, "SELECT UserID FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($result);
$user_id = $row['UserID'];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = mysqli_real_escape_string($db, $_POST['first_name']);
    $lname = mysqli_real_escape_string($db, $_POST['last_name']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $year = intval($_POST['enrollment_year']);
    $gpa = floatval($_POST['gpa']);
    $major = mysqli_real_escape_string($db, $_POST['major']);

    mysqli_query($db, "
        INSERT INTO student (FirstName, LastName, Email, Phone, DateOfBirth, Gender, Address, EnrollmentYear, GPA, Major)
        VALUES ('$fname', '$lname', '$username', '$phone', '$dob', '$gender', '$address', $year, $gpa, '$major')
        ON DUPLICATE KEY UPDATE
        FirstName='$fname', LastName='$lname', Phone='$phone', DateOfBirth='$dob', Gender='$gender',
        Address='$address', EnrollmentYear=$year, GPA=$gpa, Major='$major'
    ");

    $res = mysqli_query($db, "SELECT StudentID FROM student WHERE Email = '$username'");
    $student = mysqli_fetch_assoc($res);
    $student_id = $student['StudentID'];

    mysqli_query($db, "UPDATE users SET StudentID = $student_id WHERE UserID = $user_id");

    header("Location: apply_scholarship.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Profile Setup</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="header">
    <h2>Complete Your Student Profile</h2>
</div>

<div class="container">
    <form method="post">
        <div class="input-group">
            <label>First Name</label>
            <input type="text" name="first_name" required>
        </div>
        <div class="input-group">
            <label>Last Name</label>
            <input type="text" name="last_name" required>
        </div>
        <div class="input-group">
            <label>Phone</label>
            <input type="text" name="phone" required>
        </div>
        <div class="input-group">
            <label>Date of Birth</label>
            <input type="date" name="dob" required>
        </div>
        <div class="input-group">
            <label>Gender</label>
            <select name="gender" required>
                <option value="M">Male</option>
                <option value="F">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="input-group">
            <label>Address</label>
            <textarea name="address" rows="3" required></textarea>
        </div>
        <div class="input-group">
            <label>Enrollment Year</label>
            <input type="number" name="enrollment_year" required>
        </div>
        <div class="input-group">
            <label>GPA</label>
            <input type="text" name="gpa" required>
        </div>
        <div class="input-group">
            <label>Major</label>
            <input type="text" name="major" required>
        </div>
        <div class="input-group">
            <button type="submit" class="btn">Save and Proceed</button>
        </div>
    </form>
</div>
</body>
</html>
