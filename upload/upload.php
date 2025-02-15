<?php
header('Content-Type: application/json');

if(isset($_FILES['lostItemImage'])) {
    $errors = array();

    // Retrieve file details
    $file_name = $_FILES['lostItemImage']['name'];
    $file_size = $_FILES['lostItemImage']['size'];
    $file_tmp  = $_FILES['lostItemImage']['tmp_name'];
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Allowed file extensions
    $allowed_ext = array("jpeg", "jpg", "png");

    // Validate file extension
    if (!in_array($file_ext, $allowed_ext)) {
        $errors[] = "Extension not allowed, please choose a JPEG or PNG file.";
    }

    // Validate file size (limit: 2 MB)
    if ($file_size > 2097152) {
        $errors[] = "File size must be less than 2 MB.";
    }

    // If no errors, move the uploaded file
    if(empty($errors)) {
        $upload_directory = "uploads/";
        // Ensure the uploads directory exists and is writable
        if(!is_dir($upload_directory)) {
            mkdir($upload_directory, 0755, true);
        }
        // Create a unique file name to avoid overwriting
        $new_file_name = time() . "_" . basename($file_name);
        $upload_path = $upload_directory . $new_file_name;

        if(move_uploaded_file($file_tmp, $upload_path)) {
            // Return the image URL as JSON
            echo json_encode(['imageUrl' => $upload_path]);
        } else {
            echo json_encode(['errors' => ['Failed to move uploaded file.']]);
        }
    } else {
        // Return validation errors as JSON
        echo json_encode(['errors' => $errors]);
    }
} else {
    echo json_encode(['errors' => ['No file was uploaded.']]);
}
?>
