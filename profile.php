<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$sql = "SELECT id, username, email, phone, last_login FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

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

        /* Profile sections (Email, Phone, Last Login) */
        .profile-section p {
            background: #e9ecef;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>

        <div class="profile-section">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><strong>Last Login:</strong> <?php echo htmlspecialchars($user['last_login']); ?></p>
        </div>
    </div>
</body>
</html>
