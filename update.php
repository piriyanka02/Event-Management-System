<?php
include 'config.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "SELECT * FROM event_bookings WHERE id=$id";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found!";
        exit();
    }
}

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Server-side validation
    if(empty($name) || empty($email) || empty($phone)){
        die("All fields are required!");
    }

    $sql = "UPDATE event_bookings SET name='$name', email='$email', phone='$phone' WHERE id=$id";
    if($conn->query($sql) === TRUE){
        header("Location: view.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Update Booking</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>">
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($row['phone']); ?>">
        </div>
        <button type="submit" name="update" class="btn btn-success">Update</button>
        <a href="view.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
