<?php
// contact_submit.php
include 'config.php'; // Make sure this file contains your DB connection $conn

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if(empty($name) || empty($email) || empty($message)){
        echo json_encode(["status"=>"error","message"=>"All fields are required!"]);
        exit;
    }

    $sql = "INSERT INTO contact_messages (name, email, message) VALUES ('$name','$email','$message')";
    if(mysqli_query($conn,$sql)){
        echo json_encode(["status"=>"success","message"=>"Message sent successfully!"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Failed to send message."]);
    }
}
?>
