# 🛠️ Guía de Despliegue

Sigue estos pasos para desplegar la herramienta en tu servidor (VPS o Hosting Compartido).

📌 Paso 1: Configuración de la Base de Datos

Accede a tu gestor de base de datos (phpMyAdmin) y ejecuta el siguiente comando SQL para crear la tabla:

<pre> 
  CREATE TABLE IF NOT EXISTS visitors (
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
); 
</pre>

# Paso 2: Configurar el Logger (Backend)

Abre el archivo logger.php y edita la sección de configuración con las credenciales de tu base de datos:

<pre> 
 // logger.php
$servername = "localhost";      // Tu servidor (usualmente localhost)
$username = "tu_usuario_sql";   // Tu usuario de BD
$password = "tu_password_sql";  // Tu contraseña de BD
$dbname = "nombre_de_tu_db";    // El nombre de tu base de datos
</pre>

# Paso 3: Configurar el Dashboard
Abre el archivo dashboard.php y configura tanto el acceso a la BD como la contraseña para entrar al panel web:

<pre> 
// dashboard.php
$access_password = "admin123";  // <--- CAMBIA ESTO POR UNA CONTRASEÑA SEGURA
$db_password = "tu_password_sql"; // La misma contraseña de la BD del paso anterior
</pre>

# Paso 4: Configurar el Payload (Frontend)

Abre el archivo index.html. Busca la constante API_URL y coloca la dirección web pública donde subiste el archivo logger.php.

<pre> 
// index.html
// Ejemplo: https://mi-servidor.com/logger.php
const API_URL = 'https://TU_DOMINIO/logger.php';
</pre>

## ⚠️ Solución de Problemas (Troubleshooting)

| Error                       | Causa Probable                         | Solución                                                                 |
|-----------------------------|-----------------------------------------|--------------------------------------------------------------------------|
| Pantalla blanca en Dashboard | Errores de PHP ocultos.                 | El script incluye un modo de depuración. Si persiste, verifica tus credenciales SQL. |
| Error de CORS en consola     | El servidor bloquea peticiones externas. | Verifica que `logger.php` tenga los headers `Access-Control-Allow-Origin: *`. |
| IP desconocida (Unknown)     | Bloqueadores de anuncios.               | El script capturará datos del dispositivo, pero la IP puede ser bloqueada por extensiones como uBlock. |
| Mixed Content Error          | Protocolos diferentes.                  | Si tu `index.html` usa HTTPS, tu `API_URL` también debe ser HTTPS. |
