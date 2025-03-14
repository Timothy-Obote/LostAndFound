<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

// Debug: Check database connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$sql = "SELECT id, username, email, profile_pic, registered_at, last_login FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

// Debug: Check if SQL query preparation failed
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User data not found.");
}

$profile_pic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'default.jpg';
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <style>
        /* General page styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    text-align: center;
    margin: 40px;
}

/* Main profile container */
.container {
    max-width: 400px;
    margin: auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

/* Profile header styling */
h1 {
    font-size: 24px;
    color: #333;
}

/* Profile picture section */
.profile-picture {
    margin-top: 15px;
}

.profile-picture img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #007BFF;
}

/* Profile sections (Email, Registered At, Last Login) */
.profile-section p {
    background: #e9ecef;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    font-size: 16px;
    color: #333;
    font-weight: bold;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>

        <div class="profile-picture">
            <img src="uploads/<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture">
        </div>

        <div class="profile-section">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Registered At:</strong> <?php echo htmlspecialchars($user['registered_at']); ?></p>
            <p><strong>Last Login:</strong> <?php echo htmlspecialchars($user['last_login']); ?></p>
        </div>
    </div>
</body>
</html>
