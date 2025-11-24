<?php
session_start();

// ================= CONFIGURACIÓN =================
$access_password = "CREDENCIAL"; // CAMBIA ESTO POR TU CONTRASEÑA DE ACCESO AL PANEL

// Credenciales de la Base de Datos (Las mismas de logger.php)
$servername = "CREDENCIAL"; // TU SERVIDOR DE LA BD AQUÍ
$username = "CREDENCIAL"; // TU USUARIO DE LA BD AQUÍ
$db_password = "CREDENCIAL"; // TU CONTRASEÑA DE LA BD AQUÍ
$dbname = "CREDENCIAL"; // NOMBRE DE LA BD AQUÍ
$port = CREDENCIAL; // PUERTO DE LA BD AQUÍ
// =================================================

// Lógica de Login/Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['password'])) {
    if ($_POST['password'] === $access_password) {
        $_SESSION['loggedin'] = true;
    } else {
        $error = "Contraseña incorrecta";
    }
}

if (!isset($_SESSION['loggedin'])) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso Restringido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card { width: 100%; max-width: 400px; border: none; shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="card p-4 shadow-sm">
        <h3 class="text-center mb-4">🔐 Sentinel Panel</h3>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="post">
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Contraseña de acceso" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</body>
</html>
<?php
    exit();
}

// --- CONEXIÓN A BASE DE DATOS Y OBTENCIÓN DE DATOS ---
$conn = new mysqli($servername, $username, $db_password, $dbname, $port);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Obtener últimos 100 registros
$sql = "SELECT * FROM visitors ORDER BY timestamp DESC LIMIT 100";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sentinel Dashboard</title>
    <!-- Bootstrap 5 & FontAwesome Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-size: 0.9rem; }
        .navbar { background: #1a1d20; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .table th { font-weight: 600; color: #555; text-transform: uppercase; font-size: 0.8rem; }
        .device-icon { font-size: 1.2rem; margin-right: 5px; color: #555; }
        .os-badge { font-size: 0.75rem; }
        .ip-cell { font-family: monospace; font-weight: bold; color: #0d6efd; cursor: pointer; }
        .ip-cell:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid px-4">
            <span class="navbar-brand mb-0 h1"><i class="fas fa-user-secret me-2"></i>Sentinel Probe</span>
            <div class="d-flex">
                <a href="dashboard.php" class="btn btn-outline-light btn-sm me-2"><i class="fas fa-sync-alt"></i></a>
                <a href="?logout=true" class="btn btn-danger btn-sm">Salir</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 text-secondary">Registros Recientes</h5>
                <span class="badge bg-primary"><?php echo $result->num_rows; ?> Capturas</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Dispositivo</th>
                            <th>Sistema / Modelo</th>
                            <th>Navegador</th>
                            <th>IP Address</th>
                            <th>Ubicación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                // Determinación de Iconos
                                $osIcon = "fa-desktop";
                                $osClass = "bg-secondary";
                                $osLower = strtolower($row['os']);
                                
                                if (strpos($osLower, 'windows') !== false) { $osIcon = "fa-brands fa-windows"; $osClass = "bg-primary"; }
                                elseif (strpos($osLower, 'android') !== false) { $osIcon = "fa-brands fa-android"; $osClass = "bg-success"; }
                                elseif (strpos($osLower, 'mac') !== false || strpos($osLower, 'ios') !== false) { $osIcon = "fa-brands fa-apple"; $osClass = "bg-dark"; }
                                elseif (strpos($osLower, 'linux') !== false) { $osIcon = "fa-brands fa-linux"; $osClass = "bg-warning text-dark"; }

                                $deviceType = ($row['device_type'] == 'Mobile') ? '<i class="fas fa-mobile-alt text-primary"></i> Móvil' : '<i class="fas fa-laptop text-secondary"></i> PC';
                                
                                // Formatear fecha
                                $date = date("d M H:i:s", strtotime($row['timestamp']));
                                
                                // Link de Google Maps
                                $mapsLink = "https://www.google.com/maps/search/?api=1&query=" . urlencode($row['city'] . " " . $row['country']);
                        ?>
                        <tr>
                            <td class="text-muted"><?php echo $date; ?></td>
                            <td><?php echo $deviceType; ?></td>
                            <td>
                                <span class="badge <?php echo $osClass; ?> me-1"><i class="<?php echo $osIcon; ?>"></i> <?php echo htmlspecialchars($row['os']); ?></span>
                                <?php if($row['mobile_model'] && $row['mobile_model'] != 'N/A') echo "<br><small class='text-muted'>".$row['mobile_model']."</small>"; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['browser']); ?></td>
                            <td>
                                <span class="ip-cell" onclick="copyToClipboard('<?php echo $row['ip_address']; ?>')" title="Click para copiar">
                                    <?php echo $row['ip_address']; ?>
                                </span>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['city']); ?>, 
                                <strong><?php echo htmlspecialchars($row['country']); ?></strong>
                            </td>
                            <td>
                                <a href="<?php echo $mapsLink; ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Ver en Mapa">
                                    <i class="fas fa-map-marker-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center py-4'>No hay datos capturados aún.</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script simple para copiar IP -->
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('IP copiada: ' + text);
            }, function(err) {
                console.error('Error al copiar: ', err);
            });
        }
    </script>
</body>
</html>