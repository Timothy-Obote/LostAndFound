<?php
session_start();
include 'db_connect.php'; // Ensure database connection is correct

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error_message'] = "You must be logged in to submit an item.";
        header("Location: submit_item.php");
        exit;
    }

    // Fetch form data securely
    $user_id = $_SESSION['user_id'];
    $item_type = htmlspecialchars($_POST['itemType']);
    $itemName = htmlspecialchars($_POST['itemName']);
    $location = htmlspecialchars($_POST['location']);
    $date_time = htmlspecialchars($_POST['date_time']); // Updated field name
    $description = htmlspecialchars($_POST['description']);
    $phone_number = htmlspecialchars($_POST['phone_number']); // Fixed field name

    // File upload handling
    $targetDir = "uploads/";

    // Ensure the directory exists and is writable
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    if (!is_writable($targetDir)) {
        $_SESSION['error_message'] = "Upload directory is not writable.";
        header("Location: submit_item.php");
        exit;
    }

    // Check for file upload errors
    if ($_FILES["itemImage"]["error"] !== UPLOAD_ERR_OK) {
        $_SESSION['error_message'] = "File upload error: " . $_FILES["itemImage"]["error"];
        header("Location: submit_item.php");
        exit;
    }

    // Generate a unique file name
    $imageName = basename($_FILES["itemImage"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $imageName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allowed file types
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedTypes)) {
        $_SESSION['error_message'] = "Invalid file type. Only JPG, JPEG, PNG & GIF allowed.";
        header("Location: submit_item.php");
        exit;
    }

    // Validate image file
    if (!is_uploaded_file($_FILES["itemImage"]["tmp_name"])) {
        $_SESSION['error_message'] = "Invalid file upload.";
        header("Location: submit_item.php");
        exit;
    }

    $check = getimagesize($_FILES["itemImage"]["tmp_name"]);
    if ($check === false) {
        $_SESSION['error_message'] = "Uploaded file is not a valid image.";
        header("Location: submit_item.php");
        exit;
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES["itemImage"]["tmp_name"], $targetFilePath)) {
        // Insert item into the database
        $stmt = $conn->prepare("INSERT INTO lost_items (user_id, item_type, itemName, location, description, image, date_time, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            $_SESSION['error_message'] = "Database prepare error: " . $conn->error;
            header("Location: submit_item.php");
            exit;
        }

        $stmt->bind_param("isssssss", $user_id, $item_type, $itemName, $location, $description, $targetFilePath, $date_time, $phone_number);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Item uploaded successfully!";
        } else {
            $_SESSION['error_message'] = "Database error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error_message'] = "File upload failed. Debugging: " . print_r(error_get_last(), true);
    }

    // Redirect back to the form page
    header("Location: submit_item.php");
    exit;
}

$conn->close();
?>

<!-- Display Success or Error Messages on the Same Page -->
<?php
if (isset($_SESSION['success_message'])) {
    echo "<p style='color: green;'>" . $_SESSION['success_message'] . "</p>";
    unset($_SESSION['success_message']); // Clear message after displaying
}

if (isset($_SESSION['error_message'])) {
    echo "<p style='color: red;'>" . $_SESSION['error_message'] . "</p>";
    unset($_SESSION['error_message']); // Clear message after displaying
}
?>
