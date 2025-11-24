<?php
// logger.php

// 1. Configuración de CORS (Permitir peticiones desde cualquier origen)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Si es una petición pre-flight (OPTIONS), terminamos aquí
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 2. Configuración de la Base de Datos
$servername = "CREDENCIAL"; // TU SERVIDOR DE LA BD AQUÍ
$username = "CREDENCIAL"; // TU USUARIO DE LA BD AQUÍ
$password = "CREDENCIAL"; // Asegúrate de que esta sea la correcta
$dbname = "CREDENCIAL"; // NOMBRE DE LA BD AQUÍ
$port = CREDENCIAL; // PUERTO DE LA BD AQUÍ

// 3. Recibir el JSON enviado por Javascript
$inputJSON = file_get_contents('php://input');
$data = json_decode($inputJSON, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "No data received"]);
    exit();
}

// 4. Conectar a MySQL
$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "DB Connection failed: " . $conn->connect_error]);
    exit();
}

// 5. Preparar la consulta (Prevenir inyección SQL)
$stmt = $conn->prepare("INSERT INTO visitors (timestamp, device_type, os, browser, mobile_model, ip_address, country, region, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Asignar variables (s = string)
$stmt->bind_param("sssssssss", 
    $data['timestamp'],
    $data['type'],
    $data['os'],
    $data['browser'],
    $data['mobileModel'],
    $data['ip'],
    $data['country'],
    $data['region'],
    $data['city']
);

// 6. Ejecutar y responder
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Data logged successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "SQL Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>