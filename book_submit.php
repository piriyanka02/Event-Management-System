<?php
include 'config.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $event = trim($_POST['event']);
    $tickets = trim($_POST['tickets']);
    $message = trim($_POST['message']);

    if(empty($name) || empty($email) || empty($phone) || empty($event) || empty($tickets)){
        echo json_encode(["status"=>"error","message"=>"All required fields must be filled!"]);
        exit;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo json_encode(["status"=>"error","message"=>"Invalid email format!"]);
        exit;
    }

    if(!is_numeric($phone) || strlen($phone)!=10){
        echo json_encode(["status"=>"error","message"=>"Phone must be 10 digits!"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO event_bookings (name, email, phone, event, tickets, message) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $phone, $event, $tickets, $message);

    if($stmt->execute()){
        echo json_encode(["status"=>"success","message"=>"Event booked successfully!"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Failed to book event."]);
    }

    $stmt->close();
    $conn->close();
}
?>
