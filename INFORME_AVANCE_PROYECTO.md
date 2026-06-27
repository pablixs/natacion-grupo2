# INFORME DE AVANCE - PROYECTO SWIMMING POOL
## Análisis detallado contra las consignas del Trabajo Final

---

## 1. INTRODUCCIÓN Y OBJETIVO

El presente documento analiza el estado actual del proyecto "Swimming Pool" contra los requerimientos establecidos en el Trabajo Final de la cursada.

**Estado general:** El proyecto se encuentra en un estado INTERMEDIO-AVANZADO. Se han implementado las funcionalidades base de autenticación, gestión de usuarios (swimmers/coaches) y el panel de administración. Sin embargo, hay funcionalidades críticas pendientes.

---

## 2. ESTÁNDARES DE DESARROLLO Y CALIDAD

| Requerimiento | Estado | Detalle |
|---------------|--------|---------|
| **Versionado Git** | ✅ HECHO | Commits con convención `feat:` y `fix:` activos desde hace semanas |
| **Código en Inglés** | ✅ HECHO | Variables, funciones, tablas y clases están en inglés |
| **Principio DRY** | ⚠️ PARCIAL | Existe duplicación de lógica en `UserController` y `CoachController` (métodos `hasEmptyFields`, `executeRegistration`) |
| **Validación Frontend** | ✅ HECHO | Validaciones en JS en los formularios |
| **Validación Backend** | ✅ HECHO | Validaciones en PHP (email, password, phone, birth_date) |
| **Sesiones y permisos por rol** | ✅ HECHO | `checkAuth()` en BaseController filtra por role_id |

### Detalle de mejora pendiente en DRY:
- `UserController::hasEmptyFields()` y `CoachController::hasEmptyFields()` son casi idénticos
- `UserController::executeRegistration()` y `CoachController::executeRegistration()` comparten lógica
- Considerar refactorizar a un trait o clase base compartida

---

## 3. REQUERIMIENTOS TÉCNICOS (AJUSTES DE BASE)

### A. Refactorización del Registro de Swimmers

| Campo | Estado | Ubicación |
|-------|--------|-----------|
| **Confirmar Contraseña** | ✅ HECHO | `complete-register.view.php:43-47` con validación en `UserController:128-131` |
| **Campo birth_date** | ✅ HECHO | `complete-register.view.php:54-56` con validación en `UserController:108,338` |

### B. Punto de Entrada - Landing Page Institucional

| Aspecto | Estado | Observación |
|---------|--------|-------------|
| **Landing Page** | ⚠️ INCOMPLETA | `home.view.php` es solo un placeholder básico con mensaje de bienvenida |
| **Textos institucionales** | ❌ FALTANTE | No hay información del club |
| **Fotos/Estética** | ❌ FALTANTE | No hay imágenes institucionales |
| **Acceso a Login/Registro** | ✅ HECHO | Desde landing se puede acceder al campus virtual |

**Lo que falta:** Una landing pagepropia, completamente pública, con contenido institucional del club de natación (horarios, información, galería de fotos, etc.)

---

## 4. REQUERIMIENTOS FUNCIONALES POR ROL

### A. USUARIO ADMINISTRADOR

| Funcionalidad | Estado | Ubicación/Detalle |
|---------------|--------|-------------------|
| **Gestión de Staff (Crear Profesores)** | ✅ HECHO | `AdminController::registerCoachPost()` + vista `register-coach.view.php` |
| **Notificaciones por email al dar de alta profesor** | ✅ HECHO | `MailService::sendEmailCompleteProfileCoach()` envía link de registro |
| **Contraseña provisoria** | ⚠️ PARCIAL | La contraseña se hardcodea como "adminpassword" y se actualiza cuando el usuario completa su perfil. No se envía una contraseña provisoria real separada |
| **Gestión de Clases - CREAR** | ❌ FALTANTE | No existe Controller, Model ni View para crear clases |
| **Gestión de Clases - EDITAR** | ❌ FALTANTE | No existe funcionalidad |
| **Gestión de Clases - ELIMINAR** | ❌ FALTANTE | No existe funcionalidad |
| **Definir Día, Horario y Profesor** | ❌ FALTANTE | La tabla `lessons` existe en DB pero no hay lógica asociada |
| **Consulta General (ver todos los datos)** | ⚠️ PARCIAL | Vista de swimmers y coaches existe, pero no hay vista unificada |

**Lo que falta para Admin:**
- CRUD completo de `lessons` (crear, editar, eliminar clases)
- Definir para cada clase: día de la semana, horario (start_time, end_time), profesor asignado, nivel, capacidad

---

### B. USUARIO PROFESOR / COACH

| Funcionalidad | Estado | Ubicación/Detalle |
|---------------|--------|-------------------|
| **Gestión de Perfil (modificar datos personales)** | ⚠️ PARCIAL | La funcionalidad de registro está, pero no hay un "editar perfil" dedicado |
| **Actualizar contraseña** | ❌ FALTANTE | No existe vista/controlador para que un coach cambie su contraseña |
| **Listado de Alumnos en sus clases** | ❌ FALTANTE | No existe la lógica de filtrar nadadores por las clases del profesor |
| **Filtrar por día y horario** | ❌ FALTANTE | Necesita las clases creadas primero |

**Lo que falta para Coach:**
- Vista para editar su propio perfil
- Vista para ver solo sus alumnos (swimmers inscritos en sus lessons)
- Necesita que las lessons estén creadas para funcionar

---

### C. USUARIO SWIMMER (NADADOR)

| Funcionalidad | Estado | Ubicación/Detalle |
|---------------|--------|-------------------|
| **Panel Personal (modificar teléfono, dirección)** | ⚠️ PARCIAL | Registro funciona, pero no hay panel de edición post-registro |
| **Visualizar oferta de clases disponibles** | ❌ FALTANTE | No existe vista de oferta de clases |
| **Ordenadas por cronograma** | ❌ FALTANTE | No hay vista de schedule |
| **Realizar inscripción a clase** | ❌ FALTANTE | La tabla `bookings` existe pero no hay lógica para inscribirse |
| **Mostrar profesor responsable** | ❌ FALTANTE | Necesita la inscripción funcional |

**Lo que falta para Swimmer:**
- Panel de edición de perfil post-registro
- Vista pública de clases disponibles (con día, horario, nivel, profesor)
- Sistema de booking/enrollment a clases

---

## 5. ANÁLISIS DEL FLUJO DE DATOS

### Estructura de Base de Datos - Estado

| Relación | Estado | Observaciones |
|----------|--------|---------------|
| **Users ↔ Roles** | ✅ HECHO | Relación via `role_id` en tabla `users` |
| **Classes (lessons) ↔ Users (Coach)** | ⚠️ TABLA EXISTE, LÓGICA FALTANTE | Tabla `lessons` con `coach_id` pero sin CRUD |
| **Classes (lessons) ↔ Users (Swimmers)** | ⚠️ TABLA EXISTE, LÓGICA FALTANTE | Tabla `bookings` (enrollments) existe pero sin lógica de inscripción |

### Tablas en la DB:

```
✅ users          - Completa
✅ profiles       - Completa (con birth_date)
✅ roles          - Completa (Administrator, Coach, Swimmer)
✅ password_resets - Completa
✅ activity_log   - Completa
✅ lessons        - Estructura lista, SIN LÓGICA DE GESTIÓN
✅ bookings       - Estructura lista, SIN LÓGICA DE INSCRIPCIÓN
```

---

## RESUMEN EJECUTIVO

### ✅ LO QUE ESTÁ HECHO (65% del trabajo)

1. Sistema de autenticación completo (login, registro, recuperación de contraseña)
2. Gestión de usuarios (alta de swimmers y coaches con email de notificación)
3. Validaciones de datos en frontend y backend
4. Código en inglés con convención de commits
5. Estructura MVC funcionando
6. Roles y permisos separados (Admin, Coach, Swimmer)
7. Tablas de base de datos creadas
8. Dashboard administrativo con estadísticas y log de actividad

### ❌ LO QUE FALTA (35% del trabajo)

| Prioridad | Funcionalidad | Esfuerzo |
|-----------|---------------|----------|
| ALTA | Landing Page institucional pública | Medio |
| ALTA | CRUD de Gestión de Clases (Admin) | Alto |
| ALTA | Sistema de Inscripción a Clases (Swimmer) | Alto |
| MEDIA | Vista de alumnos por profesor (Coach) | Medio |
| MEDIA | Edición de perfil post-registro (todos los roles) | Bajo |
| BAJA | Cambio de contraseña para Coach | Bajo |

---

## RECOMENDACIONES

### 1. Landing Page Institucional
Crear una vista pública `landing.view.php` accesible sin autenticación, con:
- Historia del club
- Información de horarios de clases
- Galería de fotos
- Links a login/registro

### 2. Gestión de Clases (LesssonController)
El Admin necesita poder:
- `create`: Crear clase con día, hora, coach_id, nivel, capacidad
- `edit`: Modificar clase existente
- `delete`: Eliminar clase
- `index`: Ver todas las clases

### 3. Sistema de Booking/Enrollment
Para que swimmers puedan:
- Ver clases disponibles
- Inscribirse a una clase
- Ver sus clases inscritas

### 4. Vista Coach
Para que los coaches puedan:
- Ver sus clases asignadas
- Ver los swimmers inscritos en cada clase

---

*Informe generado: 01/06/2026*
*Proyecto: Swimming Pool - Grupo 2*