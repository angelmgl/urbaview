<?php

require '../../config/config.php';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

var_dump($user, $password);

// Preparar la consulta para obtener el usuario de la base de datos.
$stmt = $mydb->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Verificar si el usuario existe y, si es así, si la contraseña es correcta.
if ($user && password_verify($password, $user['password'])) {
    // La contraseña es correcta y el usuario existe.
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $username;

    // Actualizar el campo last_login para el usuario que ha iniciado sesión
    $updateStmt = $mydb->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
    $updateStmt->bind_param("i", $user['id']);
    $updateStmt->execute();
    $updateStmt->close();
    
    // Por ahora, redirigiremos al usuario al dashboard.
    header("Location: ../dashboard.php");
    exit;
} else {
    // No se encontró el usuario o la contraseña no es correcta.
    $_SESSION['error'] = "Usuario o contraseña incorrectos.";
    header('Location: ../login.php');
    exit();
}

$stmt->close();
$mydb->close();
