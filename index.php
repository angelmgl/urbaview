<?php 

require "./config/config.php";

session_start();

// Si el usuario ya tiene una sesión iniciada y es admin redirige al dashboard
if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    header("Location: " . BASE_URL . "/admin/dashboard.php");
    exit;
} else if(isset($_SESSION['role']) && $_SESSION['role'] == "user") {
    header("Location: " . BASE_URL . "/u/" . $_SESSION['username']);
    exit;
} else {
    header("Location: " . BASE_URL . "/login.php");
    exit;
}