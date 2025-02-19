<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $itemStatus = $_POST['itemStatus']; // Lost or Found
    $itemType = $_POST['itemType']; // Item Type
    $itemName = $_POST['itemName']; // Item Name
    $location = $_POST['location']; // Location
    $dateTime = $_POST['dateTime']; // Date and Time
    $description = $_POST['description']; // Description
    $phoneNumber = $_POST['phoneNumber']; // Phone Number

    // Handle file upload
    if (isset($_FILES['itemImage']) && $_FILES['itemImage']['error'] == 0) {
        $uploadDir = 'uploads/'; // Directory to store uploaded images
        $uploadFile = $uploadDir . basename($_FILES['itemImage']['name']);

        // Check if the directory exists, if not, create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES['itemImage']['tmp_name'], $uploadFile)) {
            $imagePath = $uploadFile;
        } else {
            $imagePath = 'Error uploading file';
        }
    }

    // Process the data (e.g., save to a database or display confirmation)
    echo "<h2>Submission Received</h2>";
    echo "<p><strong>Item Status:</strong> $itemStatus</p>";
    echo "<p><strong>Item Type:</strong> $itemType</p>";
    echo "<p><strong>Item Name:</strong> $itemName</p>";
    echo "<p><strong>Location:</strong> $location</p>";
    echo "<p><strong>Date and Time:</strong> $dateTime</p>";
    echo "<p><strong>Description:</strong> $description</p>";
    echo "<p><strong>Phone Number:</strong> $phoneNumber</p>";

    // Display the uploaded image
    if (isset($imagePath)) {
        echo "<p><strong>Uploaded Image:</strong><br><img src='$imagePath' alt='Uploaded Image' width='300'></p>";
    }
} else {
    echo "<p>No data received.</p>";
}
?>
