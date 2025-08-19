<?php
// book_submit.php
include 'config.php'; // Your DB connection

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $event = mysqli_real_escape_string($conn, $_POST['event']);
    $tickets = mysqli_real_escape_string($conn, $_POST['tickets']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if(empty($name) || empty($email) || empty($phone) || empty($event) || empty($tickets)){
        echo json_encode(["status"=>"error","message"=>"All required fields must be filled!"]);
        exit;
    }

    $sql = "INSERT INTO event_bookings (name, email, phone, event, tickets, message) VALUES ('$name','$email','$phone','$event','$tickets','$message')";

    if(mysqli_query($conn,$sql)){
        echo json_encode(["status"=>"success","message"=>"Event booked successfully!"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Failed to book event."]);
    }
}
?>
