# 🛡️ Sentinel-Probe: Full-Stack Telemetry & Fingerprinting Suite

![Version](https://img.shields.io/badge/version-2.0.0-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)
![PHP](https://img.shields.io/badge/backend-PHP%207.4%2B-purple.svg)
![MySQL](https://img.shields.io/badge/database-MySQL-orange.svg)
![Bootstrap](https://img.shields.io/badge/frontend-Bootstrap%205-blueviolet.svg)

> **⚠️ AVISO LEGAL:** Este software ha sido desarrollado únicamente con fines educativos y de investigación en ciberseguridad. El autor no se hace responsable del uso indebido de esta herramienta.

## Resumen Técnico

**Sentinel-Probe** es un framework ligero de auditoría y análisis forense digital diseñado para capturar, exfiltrar y visualizar telemetría del lado del cliente en tiempo real.

El sistema consta de tres componentes principales:
1.  **Probe (Cliente):** Un payload JavaScript ofuscado que recolecta huellas digitales del dispositivo y geolocalización.
2.  **Logger (Backend):** Una API silenciosa en PHP que procesa y almacena los datos de forma asíncrona en MySQL.
3.  **Command Dashboard:** Una interfaz gráfica segura basada en Bootstrap 5 para la visualización, filtrado y análisis de los vectores capturados.

## Capacidades

### Motor de Captura
*   **Device Fingerprinting:** Identificación precisa de S.O. (Windows/Linux/Android/iOS), arquitectura y modelo de dispositivo móvil.
*   **Geo-Intelligence:** Triangulación de IP, País, Región y Ciudad mediante integración con APIs externas (IPAPI).
*   **Persistencia:** Uso de `fetch` con la directiva `keepalive` para garantizar la transmisión de datos incluso si el usuario cierra la pestaña inmediatamente.
*   **Compatibilidad CORS:** Configurado para recibir telemetría desde orígenes cruzados (Cross-Origin).

### Panel de Control (Dashboard)
*   **Seguridad:** Sistema de autenticación nativo protegido por contraseña.
*   **Visualización:** Tabla reactiva con iconos dinámicos según el tipo de dispositivo detectado.
*   **Herramientas:** Copiado de IP en un clic y enlace directo a Google Maps para rastreo de ubicación.

---

## 📂 Estructura del Proyecto

```text
Sentinel-Probe/
├── 📄 logger.php        # API Backend: Recibe los datos y los guarda en SQL.
├── 📄 dashboard.php     # Panel Admin: Interfaz visual para ver los datos.
├── 📄 index.html        # Payload Frontend: El archivo que ejecuta la captura.
├── 📄 database.sql      # Schema: Código para crear la tabla necesaria.
└── 📄 README.md         # Documentación.
