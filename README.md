# Sistema de Gestión de Soporte Técnico y Tickets para Clientes

## Tabla de Contenidos
- [Descripción del Proyecto](#descripción-del-proyecto)
- [Estado del Proyecto](#estado-del-proyecto)
- [Objetivos](#objetivos)
- [Stack Tecnológico](#stack-tecnológico)
- [Modelos del Sistema](#modelos-del-sistema)
- [Prototipos de Interfaz](#prototipos-de-interfaz)
- [Funcionalidades Principales](#funcionalidades-principales)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Instalación y Ejecución](#instalación-y-ejecución)
- [Medidas de Seguridad](#medidas-de-seguridad)
- [Pruebas Funcionales y de Seguridad](#pruebas-funcionales-y-de-seguridad)
- [Credenciales de Prueba](#credenciales-de-prueba)
- [Capturas de Pantalla](#capturas-de-pantalla)
- [Encargados del Github](#encargados-del-github)
- [Limitaciones y Trabajo Futuro](#limitaciones-y-trabajo-futuro)

## Descripción del Proyecto

Este proyecto fue desarrollado para Morales Tech con el propósito de mejorar la gestión del servicio de soporte técnico. 

El sistema centraliza el registro y seguimiento de tickets, la gestión de inventario, el control de ventas y proporciona un portal para que los clientes consulten y soliciten cotizaciones de servicios.

## Estado del Proyecto

- Estado: Finalizado (versión funcional)
- Despliegue: Disponible en entorno cloud (InfinityFree)
- Acceso público: https://moralestech.xo.je/
- Versión: 1.0

## Objetivos

### Objetivo General
Optimizar la gestión del soporte técnico mediante un sistema web que permita el registro, seguimiento y control eficiente de incidencias, cotizaciones y ventas.

### Objetivos Específicos (SMART)
- Implementar un sistema de gestión de tickets con registro, seguimiento y control de estados (Recibido, En proceso, Listo para entrega, Completado).
- Desarrollar dos portales diferenciados (clientes y administradores) con autenticación segura basada en roles.
- Diseñar e implementar una base de datos relacional con más de 10 tablas interconectadas para la gestión integral del sistema.
- Implementar un sistema de cotización que permita asociar múltiples servicios a un equipo, generando automáticamente subtotal, IGV y total.
- Desarrollar un módulo de historial de equipos por cliente, permitiendo visualizar servicios realizados y seguimiento técnico.
- Implementar un sistema de recuperación de contraseña mediante validación de preguntas de seguridad.
- Diseñar e implementar una interfaz web responsive que se adapte a dispositivos móviles, tablets y escritorios, asegurando su correcta visualización en al menos tres resoluciones durante el desarrollo del sistema.

## Stack Tecnológico

| Capa          | Tecnología                  |
|---------------|-----------------------------|
| Frontend      | HTML5 + CSS3 + JavaScript   |
| Backend       | PHP                         |
| Base de datos | MySQL                       |
| Entorno       | XAMPP (Apache + MySQL)      |
| Herramientas  | Visual Studio Code + GitHub |

## Modelos del Sistema

El diseño de la base de datos se realizó en diferentes niveles para asegurar una correcta estructura, integridad de la información y escalabilidad del sistema.

### Diagrama Entidad-Relación (Modelo Conceptual – Notación Chen)
Representa las entidades principales del sistema y sus relaciones.

**Enlace:** [Ver Diagramas en Google Drive](https://drive.google.com/drive/folders/16gUmI9sODCDdvnKL1kYzFbFsX5C2YPbv)

### Modelo Lógico
Representa la estructura lógica de la base de datos, definiendo tablas, atributos y relaciones.

**Enlace:** [Ver Modelo Lógico en Figma](https://www.figma.com/board/cyBHNyJY4t1wcJznU8Fmjd/Modelo-L%C3%B3gico---Morales-Tech)

### Modelo Físico
Implementación real en base de datos MySQL mediante script SQL.

Ubicación: `docs/database/script.sql`

Este modelo permite la creación de tablas, relaciones y restricciones necesarias para el correcto funcionamiento del sistema.

## Prototipos de Interfaz

Durante el desarrollo del sistema se trabajaron dos versiones de prototipos de alta fidelidad, reflejando la evolución del diseño de la interfaz de usuario.

### Prototipo v1 - Alta Fidelidad (Figma)
Primer diseño visual del sistema con enfoque en la estructura inicial, funcionalidades básicas y experiencia de usuario planteada en la fase de análisis.

**Enlace:**  
https://www.figma.com/design/2CoKb19rchhnD5PpjKrTjQ/Morales-Tech---Integrador-II---v1?node-id=0-1

### Prototipo v2 – Alta Fidelidad (Figma)
Versión actualizada del sistema que incorpora mejoras funcionales y visuales respecto al prototipo inicial, incluyendo:

- Integración del módulo de ventas completo
- Soporte para ventas por ticket y por producto
- Generación de boletas en PDF
- Mejoras en la experiencia de usuario
- Ajustes en la arquitectura visual del sistema

Este prototipo representa el estado más cercano a la implementación final del sistema.

**Enlace:**  
https://www.figma.com/design/aacROpMdBOdo8JNwRwbyfo/Morales-Tech---Integrador-II---v2?node-id=0-1&t=0HkjUBEffHRkEPp0-1

## Funcionalidades Principales

### Página Pública (Landing Page)
- Accesible sin iniciar sesión en `index.php`.
- Visualización de servicios ofrecidos por Morales Tech.
- Consulta pública del estado de tickets (`consulta_tickets.php`):
  - Búsqueda de tickets en tiempo real conectada a la base de datos.
  - Visualización de información general del ticket (código, estado, equipo y servicios).
  - Implementación de medidas de seguridad que evitan exponer datos sensibles del cliente (DNI, nombre, etc.).

### Portal de Clientes (Privado)
- Requiere inicio de sesión.
- Visualización de tickets recientes con sus estados (Recibido, En proceso, Listo para entrega, Completado).
- Registro de **Nuevo Ticket** a través de un asistente por pasos:
  - Selección de dispositivo (PC de Escritorio o Laptop).
  - Descripción del problema y sistema operativo (obligatorio).
  - Campos adicionales para laptops (marca, modelo y serie - opcionales).
  - Selección de servicios básicos y adicionales.
  - Resumen final de cotización con desglose de precios, total e IGV.
- Sección **Mis Equipos** (`equipos_cliente.php`):
  - Visualización del historial de equipos registrados por el cliente.
  - Consulta de servicios asociados a cada equipo.
  - Seguimiento histórico de atenciones realizadas.

### Portal Administrativo (Privado)
- Acceso exclusivo mediante `login_staff.php` (solo correos con `@moralestechs.com`).
- Dashboard principal con resumen, KPIs y gráficos.
- **Módulo Tickets**: Visualización general, cambio de estado y registro manual de tickets.
- **Módulo Inventario**: Gestión de productos, stock y registro de nuevos items.
- **Módulo Ventas**:
  - Registro de ventas asociadas a tickets.
  - Registro de ventas por producto (sin ticket).
  - Generación de boleta de venta en formato PDF.
  - Descarga de boleta desde el sistema.
  - Integración completa con la base de datos.
  - Selección de método de pago (Efectivo, Yape, Transferencia).

### Generación de documentos PDF
- Generación de cotizaciones en PDF desde el portal cliente.
- Generación de boletas de venta en PDF desde el módulo administrativo.
- Descarga automática desde el sistema.
- Diseño basado en identidad corporativa.

## Estructura del Proyecto

```bash
Morales-Tech/
├── database/
│   ├── script.sql
├── docs/
│   ├── pruebas/
│   │   ├── informe_pruebas.pdf
│   ├── deployment/
│   │   ├── cloud/
│   │   │   ├── lighthouse/
│   ├── diagrams/
│   │   ├── modelo_er.png
│   │   ├── modelo_logico.jpg
│   │   ├── modelo_fisico.jpg
│   ├── database/
│   │   ├── script.sql
├── img/
├── admin_protect.php
├── client_protect.php
├── logout.php
├── logout_cliente.php
├── conexion.php
├── consulta_tickets.php
├── dashboard.php
├── equipos_cliente.php
├── index.php
├── inicio_clientes.php
├── inventario.php
├── login.php
├── login_staff.php
├── nueva_venta.php
├── nuevo_ticket.php
├── nuevo_ticket_cliente.php
├── recuperar_contra_staff.php
├── recuperar_contrasena.php
├── registro.php
├── registro_staff.php
├── tickets.php
├── tickets_cliente.php
├── ventas.php
├── script.js
├── styles.css
└── README.md
```

## Instalación y Ejecución

### Requisitos
- XAMPP instalado
- Apache y MySQL activos

### Pasos

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/JuanEztevan/Morales-Tech.git
   ```

2. Copiar la carpeta a `C:/xampp/htdocs/Morales-Tech`

3. Iniciar Apache y MySQL desde XAMPP.

4. Importar la base de datos:
   - Acceder a `http://localhost/phpmyadmin`
   - Crear base de datos `morales_tech`
   - Importar el archivo `docs/database/script.sql`

5. Acceder al sistema en: `http://localhost/Morales-Tech`

## Despliegue en Producción (Cloud)

El sistema Morales Tech se encuentra actualmente desplegado en un entorno de hosting gratuito mediante InfinityFree, permitiendo el acceso público vía internet.

Acceso al sistema:
`https://moralestech.xo.je/`

Este entorno permite validar el comportamiento real del sistema en producción, incluyendo pruebas de rendimiento, persistencia de datos y disponibilidad del servicio.

## Medidas de Seguridad
- Autenticación mediante sesiones PHP.
- Validación de formularios en frontend y backend.
- Restricción de acceso basada en roles (cliente y administrador).
- Validación de dominio para cuentas administrativas (`@moralestechs.com`).
- Implementación de **preguntas de seguridad (3)** durante el registro de clientes y administradores.
- Módulo de **recuperación de contraseña**:
  - Validación mediante correo o DNI.
  - Verificación de respuestas a preguntas de seguridad.
  - Restablecimiento de contraseña solo si todas las respuestas son correctas.
- Protección de rutas mediante archivos intermedios (`admin_protect.php` y `client_protect.php`).
- Restricción de acceso directo por URL a páginas privadas sin autenticación previa.
- Implementación de cierre de sesión seguro (logout) con destrucción completa de la sesión.
- Redirección automática al login en caso de sesión no válida o expirada.
- Control de acceso por roles con validación continua de sesión.
- Implementación de consultas públicas seguras que restringen la exposición de información sensible del cliente.

## Pruebas Funcionales y de Seguridad
Se realizaron 10 casos de prueba funcionales sobre los módulos principales del sistema, documentados y ejecutados en TestRail.

| Código | Nombre | Prioridad |
|---|---|---|
| CP01 | Registro de cliente con datos válidos | Alta |
| CP02 | Registro de administrador con dominio @moralestechs.com | Alta |
| CP03 | Inicio de sesión con credenciales inválidas | Alta |
| CP04 | Recuperación de contraseña mediante preguntas de seguridad | Media |
| CP05 | Creación completa de cotización y ticket mediante asistente por pasos | Crítica |
| CP06 | Validación del cálculo de cotización con IGV y total | Alta |
| CP07 | Visualización de tickets propios desde el portal cliente | Media |
| CP08 | Cambio de estado de ticket por parte del administrador | Alta |
| CP09 | Validación de acceso por roles | Crítica |
| CP10 | Consulta pública de ticket por código de seguimiento | Baja |

- **Herramienta:** TestRail  
- **Ciclos de prueba ejecutados:** 2  
- **Informe exportado:** `docs/pruebas/informe_pruebas.pdf`

### Pruebas de Rendimiento (Apache Benchmark)

Se realizaron pruebas de carga utilizando la herramienta Apache Benchmark (ab), incluida en el entorno XAMPP, con el objetivo de evaluar el rendimiento del sistema bajo múltiples solicitudes concurrentes.

- Herramienta: Apache Benchmark (ab)
- Entorno: Servidor local (Apache - XAMPP)
- Objetivo: Medir capacidad de respuesta y estabilidad del sistema

#### Escenario de prueba:
- Simulación de múltiples usuarios accediendo al sistema
- Pruebas sobre endpoints críticos (login, ventas, consulta de tickets)
- Evaluación con distintos niveles de concurrencia

#### Métricas evaluadas:
- Tiempo promedio de respuesta
- Requests por segundo
- Tiempo total de ejecución
- Capacidad de concurrencia

#### Resultado:
Las pruebas realizadas evidencian que el sistema presenta un rendimiento óptimo en entorno local, manteniendo tiempos de respuesta bajos y sin fallos en las solicitudes bajo condiciones de carga controladas.

### Pruebas de Rendimiento en Producción (Lighthouse)

Se realizaron pruebas de rendimiento en el entorno cloud utilizando Lighthouse (PageSpeed Insights), evaluando el comportamiento del sistema en versiones de escritorio y móvil.

- Reporte Desktop: docs/deployment/cloud/lighthouse/Reporte_Lighthouse_Desktop_MoralesTech.pdf
- Reporte Mobile: docs/deployment/cloud/lighthouse/Reporte_Lighthouse_Mobile_MoralesTech.pdf

## Credenciales de Prueba
El sistema se encuentra desplegado en un entorno cloud accesible públicamente.

Para acceder al sistema:

- **URL del sistema:** https://moralestech.xo.je/
- **Portal Cliente:** Acceder desde `login.php` y registrarse en el sistema para realizar pruebas completas del flujo de usuario.
- **Portal Administrador:** Acceder desde `login_staff.php` usando las siguientes credenciales:
  Usuario: demo@moralestechs.com  
  Contraseña: J0j0l10n!


## Capturas de Pantalla

### Landing Page
img/capturas/landing.png

### Consulta de Tickets
img/capturas/consulta_ticket.png

### Portal Cliente
img/capturas/cliente_dashboard.png">

### Cotización de Servicios
img/capturas/cotizacion.png

### Portal Administrativo
img/capturas/admin_dashboard.png

### Módulo de Ventas
img/capturas/ventas.png

## Encargados del GitHub
- Juan Esteban Carmona Rodríguez – Frontend y Backend
- Julio César Moisés Salazar Gutierrez – Backend
- Lyan Stefano Torres Coello – Backend

**Curso: Integrador II - Sistemas**  
**Profesor: José Andrés Valle Fuente**  
**Semestre:** 2026-1

## Limitaciones y Trabajo Futuro
- El sistema se encuentra desplegado en un entorno cloud (InfinityFree), sin embargo depende de un hosting gratuito con limitaciones de rendimiento y disponibilidad.
- No se incluye integración con pasarelas de pago en línea.
- La seguridad se basa en preguntas de seguridad, lo cual podría mejorarse hacia métodos más robustos (tokens, verificación por correo, autenticación multifactor).
- Implementación actual de generación de cotizaciones en PDF utilizando jsPDF.
- Rendimiento en dispositivos móviles inferior al de escritorio debido a limitaciones del hosting y optimización frontend.

- Mejoras futuras:
  - Migración a un entorno de hosting más robusto (VPS o cloud dedicado) para soportar mayor carga de usuarios.
  - Integración de pasarelas de pago en línea.
  - Implementación de autenticación multifactor (MFA).
  - Optimización del rendimiento en móviles (mejoras responsive y carga de recursos).
  - Integración avanzada del módulo de boletas con reportes de ventas.
  - Implementación de un módulo de accesibilidad que permita ajustar tamaño de texto, contraste y legibilidad para usuarios con discapacidad visual.
