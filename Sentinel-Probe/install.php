<?php
// ================= CONFIGURACIÓN =================
// Pon aquí los datos de tu servidor SQL
$servername = "CREDENCIAL"; // TU SERVIDOR DE LA BD AQUÍ
$username = "CREDENCIAL"; // TU USUARIO DE LA BD AQUÍ
$password = "CREDENCIAL"; // TU CONTRASEÑA DE LA BD AQUÍ
$dbname = "CREDENCIAL"; // NOMBRE DE LA BD AQUÍ
// =================================================

$message = "";
$status = "";

// Intentamos conectar e instalar
if (isset($_GET['run'])) {
    try {
        // 1. Conectar
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if ($conn->connect_error) {
            throw new Exception("Fallo de conexión: " . $conn->connect_error);
        }

        // 2. La consulta SQL para crear la tabla
        $sql = "CREATE TABLE IF NOT EXISTS visitors (
            id INT AUTO_INCREMENT PRIMARY KEY,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            device_type VARCHAR(50),
            os VARCHAR(50),
            browser VARCHAR(50),
            mobile_model VARCHAR(100),
            ip_address VARCHAR(45),
            country VARCHAR(100),
            region VARCHAR(100),
            city VARCHAR(100)
        )";

        // 3. Ejecutar
        if ($conn->query($sql) === TRUE) {
            $status = "success";
            $message = "✅ ¡Éxito! La tabla 'visitors' se ha creado (o ya existía).<br>Ya puedes borrar este archivo.";
        } else {
            throw new Exception("Error SQL: " . $conn->error);
        }

        $conn->close();

    } catch (Exception $e) {
        $status = "error";
        $message = "❌ Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador Sentinel-Probe</title>
    <style>
        body { font-family: sans-serif; background: #222; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: #333; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.5); text-align: center; max-width: 400px; }
        h1 { margin-top: 0; color: #3498db; }
        .btn { background: #27ae60; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; margin-top: 20px; transition: 0.3s; }
        .btn:hover { background: #219150; }
        .msg { margin-top: 20px; padding: 15px; border-radius: 5px; }
        .success { background: #27ae60; color: white; }
        .error { background: #c0392b; color: white; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Sentinel Installer</h1>
        <p>Este script configurará la base de datos automáticamente.</p>
        
        <?php if ($message == ""): ?>
            <a href="?run=true" class="btn">INSTALAR AHORA</a>
        <?php else: ?>
            <div class="msg <?php echo $status; ?>">
                <?php echo $message; ?>
            </div>
            <?php if ($status == 'success'): ?>
                <p style="font-size: 0.8em; color: #aaa; margin-top: 15px;">Por seguridad, borra el archivo <b>install.php</b> de tu servidor ahora.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
