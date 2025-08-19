<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['login-email']);
    $password = trim($_POST['login-password']);

    // Validation
    if(empty($email) || empty($password)){
        echo "<script>alert('All fields are required for login!'); window.location='Register.html#login';</script>";
        exit();
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "<script>alert('Invalid email format!'); window.location='Register.html#login';</script>";
        exit();
    }

    // DB check
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $row = $result->fetch_assoc();

        if(password_verify($password, $row['password'])){
            // Login success
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];

            echo "<script>alert('Login Successful! Welcome ".$row['name']."'); window.location='index.html';</script>";
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location='Register.html#login';</script>";
            exit();
        }
    } else {
        echo "<script>alert('No account found with this email!'); window.location='Register.html#login';</script>";
        exit();
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: Register.html#login");
    exit();
}
?>
