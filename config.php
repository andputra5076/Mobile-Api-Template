<?php
$host = 'localhost'; // Ganti dengan host database Anda
$user = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda
$database = 'sisantren_db'; // Ganti dengan nama database Anda

$mysqli = new mysqli($host, $user, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $mysqli->connect_error]));
}
?>
