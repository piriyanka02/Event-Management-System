<?php
include 'config.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if(empty($name) || empty($email) || empty($message)){
        echo json_encode(["status"=>"error","message"=>"All fields are required!"]);
        exit;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo json_encode(["status"=>"error","message"=>"Invalid email!"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if($stmt->execute()){
        echo json_encode(["status"=>"success","message"=>"Message sent successfully!"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Failed to send message."]);
    }

    $stmt->close();
    $conn->close();
}
?>
