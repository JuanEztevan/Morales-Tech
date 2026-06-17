# Sistema de Gestión de Soporte Técnico y Tickets para Clientes

## Tabla de Contenidos
- [Descripción del Proyecto](#descripción-del-proyecto)
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

## Objetivos

### Objetivo General
Optimizar la gestión del soporte técnico mediante un sistema web que permita el registro, seguimiento y control eficiente de incidencias, cotizaciones y ventas.

### Objetivos Específicos (SMART)
- Implementar un sistema de gestión de tickets con registro, seguimiento y control de estados (Recibido, Diagnóstico, Reparación, Completado).
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

Durante el desarrollo del sistema se trabajaron dos versiones de prototipos, reflejando la evolución del diseño de la interfaz de usuario.

### Prototipo v1 – Alta Fidelidad (Figma)
Primer diseño visual del sistema con enfoque en la apariencia final y experiencia de usuario.

**Enlace:**  
https://www.figma.com/design/2CoKb19rchhnD5PpjKrTjQ/Morales-Tech---Integrador-II---v1?node-id=0-1

---

### Prototipo v2 – Baja Fidelidad (Balsamiq)
Wireframe estructural utilizado para replantear la distribución de componentes y mejorar la usabilidad.

Este prototipo sirvió como base para redefinir el diseño final implementado en el sistema.

**Enlace:**  
https://balsamiq.cloud/sty85pm/pppo8zi

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
- Visualización de tickets recientes con sus estados (Recibido, En diagnóstico, En reparación, Completado).
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
- Generación de cotización en formato PDF:
  - Descarga automática de la cotización desde el sistema.
  - Inclusión de datos del cliente (nombre, DNI, teléfono y RUC opcional).
  - Diseño del documento basado en la identidad corporativa de la empresa.
  - Cálculo automático de subtotal, IGV y total.

### Portal Administrativo (Privado)
- Acceso exclusivo mediante `login_staff.php` (solo correos con `@moralestechs.com`).
- Dashboard principal con resumen, KPIs y gráficos.
- **Módulo Tickets**: Visualización general, cambio de estado y registro manual de tickets.
- **Módulo Inventario**: Gestión de productos, stock y registro de nuevos items.
- **Módulo Ventas**: Registro de ventas por ticket o ventas independientes, con selección de método de pago (Efectivo, Yape, Transferencia).

## Estructura del Proyecto

```bash
Morales-Tech/
├── database/
│   ├── script.sql
├── docs/
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

## Credenciales de Prueba
Las credenciales de prueba han sido removidas por seguridad.

Para acceder al sistema:
- **Portal Cliente:** Registrarse desde `registro.php`
- **Portal Administrador:** Registrarse desde `registro_staff.php` 
  usando un correo con dominio `@moralestechs.com`

## Capturas de Pantalla

<img width="1874" height="988" alt="image" src="https://github.com/user-attachments/assets/b47b6d0b-5bec-4ad3-8051-b5b7db6379a0" />
<img width="1874" height="988" alt="image" src="https://github.com/user-attachments/assets/57ea19d8-63fd-4304-84d6-5096b0f7b027" />

<img width="1874" height="988" alt="image" src="https://github.com/user-attachments/assets/9ec25c4a-24b2-4024-a279-5b57d0e89488" />
<img width="1874" height="988" alt="image" src="https://github.com/user-attachments/assets/db67af13-3387-42cd-bdd0-deefb4b9c89a" />
<img width="1874" height="988" alt="image" src="https://github.com/user-attachments/assets/3532f293-fd73-420c-b3cf-25d0d3fe8cb4" />

## Encargados del GitHub
- Juan Esteban Carmona Rodríguez – Frontend
- Julio César Moisés Salazar Gutierrez – Backend
- Lyan Stefano Torres Coello – Backend

**Curso: Integrador II - Sistemas**  
**Profesor: José Andrés Valle Fuente**  
**Semestre:** 2026-1

## Limitaciones y Trabajo Futuro
- Sistema actualmente diseñado para entorno local.
- No incluye pasarela de pagos real.
- La seguridad se basa en preguntas de seguridad, lo cual podría mejorarse hacia métodos más robustos (tokens, verificación por correo, autenticación multifactor).
- Implementación actual de generación de cotizaciones en PDF utilizando jsPDF.
- Mejoras futuras:
  - Generación de boleta de venta en PDF al registrar una venta como completada en el sistema.
  - Integración del módulo de PDFs con el panel de administradores.
  - Implementación de un módulo de accesibilidad que permita ajustar tamaño de texto, contraste y legibilidad para usuarios con discapacidad visual.
