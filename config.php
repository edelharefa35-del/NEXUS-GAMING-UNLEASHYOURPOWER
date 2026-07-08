<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "nexus gaming";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Koneksi database gagal: " . $conn->connect_error]));
}
?>