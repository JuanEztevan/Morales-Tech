# Sistema de Gestión de Soporte Técnico y Tickets para Morales Tech

Sistema web orientado a la gestión de incidencias, tickets, inventario y ventas para empresas de soporte técnico.

---

## Descripción del proyecto

Este proyecto se da principalmente porque muchas empresas de soporte técnico presentan problemas en la gestión de incidencias, ya que utilizan procesos manuales o herramientas no integradas. Esto afecta directamente a la organización, debido a que se genera pérdida de información, baja trazabilidad y falta de control sobre los tickets.

Por ello, se desarrolla un sistema web que permite centralizar procesos como registro de incidencias, seguimiento de tickets, gestión de inventario y ventas. Esto permite mejorar la organización, reducir errores y tener un mejor control del servicio brindado al cliente.

---

## Objetivo

El objetivo del sistema es optimizar la gestión del soporte técnico, ya que mediante procesos estructurados se logra mejorar la eficiencia operativa y la toma de decisiones dentro de la empresa.

---

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Frontend | HTML5 + CSS3 + JavaScript |
| Backend | PHP |
| Base de datos | MySQL |
| Entorno | XAMPP |
| Herramientas | VS Code + GitHub |

---

## Estructura del proyecto

```

Morales-Tech/
├── img/
├── index.php
├── login.php
├── login\_staff.php
├── registro.php
├── registro\_staff.php
├── dashboard.php
├── inicio\_clientes.php
├── inventario.php
├── ventas.php
├── tickets.php
├── tickets\_cliente.php
├── nuevo\_ticket.php
├── nuevo\_ticket\_cliente.php
├── nueva\_venta.php
├── consulta\_tickets.php
├── script.js
├── styles.css
└── README.md

```

---

## Funcionalidades principales

- Registro de usuarios  
- Inicio de sesión (clientes y administradores)  
- Creación de tickets  
- Consulta de tickets  
- Gestión de inventario  
- Registro de ventas  

Esto permite que la empresa trabaje de forma más ordenada, evitando errores y mejorando el control de la información.

---

## Seguridad y validaciones

Este sistema implementa controles básicos de seguridad, ya que es necesario proteger la información y evitar accesos no autorizados.

- Autenticación de usuarios  
- Manejo de sesiones (login / logout)  
- Validación de formularios  
- Restricción de acceso por roles  

**Regla importante:**  
Solo se pueden registrar administradores cuyos correos terminen en:

```

@morales-techs.com

```

Esto se da como medida de seguridad, ya que permite validar que solo personal autorizado acceda al sistema administrativo.

---

## Credenciales de acceso (DEMO)

### Cliente

```

Correo: <demo@gmail.com>
Contraseña: demo

```

Esto permite probar la funcionalidad de consulta de tickets y acceso al sistema como usuario cliente.

---

### Administrador

Los administradores deben registrarse cumpliendo la siguiente condición:

```

<correo@morales-techs.com>

````

Esto permite validar el acceso y evitar que cualquier usuario pueda ingresar como administrador.

---

## Ejecución del proyecto (modo local)

### Requisitos
- XAMPP instalado  
- Apache y MySQL activos  

### Pasos

```bash
1. Clonar el repositorio
git clone https://github.com/JuanEztevan/Morales-Tech.git

2. Copiar la carpeta en htdocs
C:/xampp/htdocs/

3. Iniciar Apache y MySQL desde XAMPP

4. Abrir en navegador
http://localhost/Morales-Tech
````

***

## Encargados del proyecto

Este repositorio es trabajado por:

- **Juan Esteban Carmona Rodríguez** → Encargado del frontend  
- **Julio Moisés Salazar** → Encargado del backend  
- **Lyan Torres Coello** → Encargado del backend  

Cada uno se enfoca en su área para avanzar de forma más ordenada en el desarrollo del sistema.


***

## Estado del proyecto

Actualmente el proyecto se encuentra en desarrollo, ya que se ha implementado principalmente el frontend y parte del backend en PHP. Sin embargo, aún se puede mejorar la integración con la base de datos y optimizar el rendimiento general.

***

## Conclusión

Este sistema permite mejorar la gestión del soporte técnico, ya que organiza los procesos, reduce errores y facilita el control de los tickets. Además, mejora la atención al cliente y aporta mayor orden dentro de la empresa.
