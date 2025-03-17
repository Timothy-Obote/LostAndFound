<?php
include 'db_connect.php';

// Fetch identification cards
$sql = "SELECT itemName, location, date_time, image, description FROM lost_items WHERE item_type='electronics'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronics</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Electronics</h1>
    <div class="items-container">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="card">
                <img src="<?php echo $row['image']; ?>" alt="Lost Item">
                <h3><?php echo $row['itemName']; ?></h3>
                <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                <p><strong>Date:</strong> <?php echo $row['date_time']; ?></p>
                <p><?php echo $row['description']; ?></p>
            </div>
        <?php } ?>
    </div>

    <style>
        .items-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .card {
            width: 250px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            margin: 10px;
            text-align: center;
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>

</body>
</html>
