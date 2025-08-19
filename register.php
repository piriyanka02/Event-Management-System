<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['reg-name']);
    $email = trim($_POST['reg-email']);
    $phone = trim($_POST['reg-phone']);
    $password = trim($_POST['reg-password']);

    // Validation
    if(empty($name) || empty($email) || empty($phone) || empty($password)){
        echo "<script>alert('All fields are required!'); window.location='Register.html';</script>";
        exit();
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "<script>alert('Invalid email format!'); window.location='Register.html';</script>";
        exit();
    }
    if(!is_numeric($phone) || strlen($phone) != 10){
        echo "<script>alert('Phone must be 10 digits!'); window.location='Register.html';</script>";
        exit();
    }

    // Check duplicate email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        echo "<script>alert('Email already exists!'); window.location='Register.html';</script>";
        exit();
    }

    // Hash password & insert user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name,email,phone,password) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $hashedPassword);
    if($stmt->execute()){
        echo "<script>alert('Registration Successful! You can now login.'); window.location='Register.html';</script>";
        exit();
    } else {
        echo "<script>alert('Error: ".$conn->error."'); window.location='Register.html';</script>";
        exit();
    }

    $conn->close();
} else {
    header("Location: Register.html");
    exit();
}
?>
