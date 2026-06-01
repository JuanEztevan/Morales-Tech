# Sistema de Gestión de Soporte Técnico y Tickets para Clientes

## Tabla de Contenidos
- [Descripción del Proyecto](#descripción-del-proyecto)
- [Objetivos](#objetivos)
- [Stack Tecnológico](#stack-tecnológico)
- [Modelos del Sistema](#modelos-del-sistema)
- [Funcionalidades Principales](#funcionalidades-principales)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Instalación y Ejecución](#instalación-y-ejecución)
- [Medidas de Seguridad](#medidas-de-seguridad)
- [Credenciales de Prueba](#credenciales-de-prueba)
- [Capturas de Pantalla](#capturas-de-pantalla)
- [Encargados del Proyecto](#encargados-del-proyecto)
- [Limitaciones y Trabajo Futuro](#limitaciones-y-trabajo-futuro)

## Descripción del Proyecto

Este proyecto fue desarrollado para Morales Tech con el propósito de mejorar la gestión del servicio de soporte técnico. 

El sistema centraliza el registro y seguimiento de tickets, la gestión de inventario, el control de ventas y proporciona un portal para que los clientes consulten y soliciten cotizaciones de servicios.

## Objetivos

### Objetivo General
Optimizar la gestión del soporte técnico mediante un sistema web que permita el registro, seguimiento y control eficiente de incidencias, cotizaciones y ventas.

### Objetivos Específicos (SMART)
- Implementar un módulo completo de tickets con registro, asignación y seguimiento en al menos 5 estados diferentes.
- Desarrollar dos portales diferenciados (clientes y administradores) con autenticación por roles.
- Diseñar e implementar una base de datos con al menos 8 tablas interrelacionadas.
- Crear un flujo de cotización por pasos que integre selección de dispositivos, servicios y generación de resumen con IGV.

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

### Diagrama Entidad-Relación (Modelo Conceptual)
Representa las entidades principales del sistema y sus relaciones.

**Enlace:** [Ver Diagramas en Google Drive](https://drive.google.com/drive/folders/16gUmI9sODCDdvnKL1kYzFbFsX5C2YPbv)

### Modelo Lógico
Representa la estructura lógica de la base de datos, definiendo tablas, atributos y relaciones.

**Enlace:** [Ver Modelo Lógico en Figma](https://www.figma.com/board/cyBHNyJY4t1wcJznU8Fmjd/Modelo-L%C3%B3gico---Morales-Tech)

### Modelo Físico
Implementación real en base de datos MySQL mediante script SQL.

Ubicación: `docs/database/script.sql`

Este modelo permite la creación de tablas, relaciones y restricciones necesarias para el correcto funcionamiento del sistema.

## Funcionalidades Principales

### Página Pública (Landing Page)
- Accesible sin iniciar sesión en `index.php`.
- Visualización de servicios ofrecidos por Morales Tech.
- Consulta pública del estado de tickets (`consulta_tickets.php`).

### Portal de Clientes (Privado)
- Requiere inicio de sesión.
- Visualización de tickets recientes con sus estados (Recibido, En diagnóstico, En reparación, Completado).
- Registro de **Nuevo Ticket** a través de un asistente por pasos:
  - Selección de dispositivo (PC de Escritorio o Laptop).
  - Descripción del problema y sistema operativo (obligatorio).
  - Campos adicionales para laptops (marca, modelo y serie - opcionales).
  - Selección de servicios básicos y adicionales.
  - Resumen final de cotización con desglose de precios, total e IGV.

### Portal Administrativo (Privado)
- Acceso exclusivo mediante `login_staff.php` (solo correos con `@moralestechs.com`).
- Dashboard principal con resumen, KPIs y gráficos.
- **Módulo Tickets**: Visualización general, cambio de estado y registro manual de tickets.
- **Módulo Inventario**: Gestión de productos, stock y registro de nuevos items.
- **Módulo Ventas**: Registro de ventas por ticket o ventas independientes, con selección de método de pago (Efectivo, Yape, Transferencia).

## Estructura del Proyecto

```bash
Morales-Tech/
├── backend/
├── docs/
│   ├── diagrams/
│   │   ├── modelo_er.png
│   │   ├── modelo_logico.jpg
│   │   ├── modelo_fisico.jpg
│   ├── database/
│   │   ├── script.sql
├── img/
├── index.php
├── login.php
├── login_staff.php
├── registro.php
├── registro_staff.php
├── dashboard.php
├── inicio_clientes.php
├── inventario.php
├── ventas.php
├── tickets.php
├── tickets_cliente.php
├── nuevo_ticket.php
├── nuevo_ticket_cliente.php
├── nueva_venta.php
├── consulta_tickets.php
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
- Autenticación con sesiones PHP
- Validación de formularios en frontend y backend
- Restricción de acceso por roles
- Validación de dominio para cuentas administrativas (`@moralestechs.com`)

## Credenciales de Prueba

**Cliente:**
- Correo: `demo@gmail.com`
- Contraseña: `demo`

**Administrador:**
- Correo: `demo@moralestechs.com`
- Contraseña: `demo`

## Capturas de Pantalla

<img width="1874" height="988" alt="image" src="https://github.com/user-attachments/assets/b47b6d0b-5bec-4ad3-8051-b5b7db6379a0" />
<img width="1874" height="988" alt="image" src="https://github.com/user-attachments/assets/57ea19d8-63fd-4304-84d6-5096b0f7b027" />
<img width="1874" height="988" alt="image" src="https://github.com/user-attachments/assets/26caea33-0ec0-4a92-8659-ef368686f4b5" />

<img width="1874" height="988" alt="image" src="https://github.com/user-attachments/assets/9ec25c4a-24b2-4024-a279-5b57d0e89488" />
<img width="1874" height="988" alt="image" src="https://github.com/user-attachments/assets/db67af13-3387-42cd-bdd0-deefb4b9c89a" />
<img width="1874" height="988" alt="image" src="https://github.com/user-attachments/assets/3532f293-fd73-420c-b3cf-25d0d3fe8cb4" />
<img width="1874" height="988" alt="image" src="https://github.com/user-attachments/assets/bf90b6cc-2397-4c92-9c4d-9f5ae5ed22c2" />


## Encargados del GitHub
- Juan Esteban Carmona Rodríguez – Frontend
- Julio Moisés Salazar – Backend
- Lyan Torres Coello – Backend

**Curso: Integrador II - Sistemas**  
**Profesor: José Andrés Valle Fuente**  
**Semestre:** 2026-1

## Limitaciones y Trabajo Futuro
- Sistema diseñado para entorno local.
- No incluye pasarela de pagos real.
- Actualmente no se cuenta con un módulo automatizado de recuperación de contraseña (olvidé mi contraseña). En el portal de trabajadores, las credenciales son gestionadas de forma interna, por lo que en caso de olvido el usuario debe comunicarse con el área de TI mediante el correo ti@moralestechs.com o acercarse a soporte interno.
- Mejoras futuras: implementación de recuperación de contraseña mediante validación con pregunta de seguridad definida durante el registro, además de generación de reportes PDF y despliegue en producción.
