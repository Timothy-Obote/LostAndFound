<?php
session_start();
include 'db_connect.php'; // Ensure this correctly connects to your DB

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to submit a lost item.");
    }

    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
    $item_type = $_POST['item_type'];
    $itemName = $_POST['itemName'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    // File upload handling
    $targetDir = "uploads/";
    $imageName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $imageName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allowed file types
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedTypes)) {
        die("Invalid file type. Only JPG, JPEG, PNG & GIF allowed.");
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
        // Insert item into the database
        $stmt = $conn->prepare("INSERT INTO lost_items (user_id, item_type, itemName, location, description, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $user_id, $item_type, $itemName, $location, $description, $targetFilePath);

        if ($stmt->execute()) {
            echo "Item uploaded successfully!";
            header("Location: index_loggedin.html");
        } else {
            echo "Database error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "File upload failed.";
    }
}

$conn->close();
?>
