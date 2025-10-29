## Flujo de autenticación y manejo de sesiones

Este documento describe el flujo recomendado para el módulo de autenticación de la aplicación, incluyendo validación del lado cliente, validación y verificación segura en el servidor (con `password_hash`/`password_verify`), uso de consultas preparadas y manejo de sesiones.

### Objetivos

- Evitar inyecciones SQL usando consultas preparadas.
- Guardar contraseñas de forma segura usando `password_hash` y verificar con `password_verify`.
- Realizar validación de entrada antes de la autenticación.
- Iniciar y controlar sesiones para gestionar el acceso del usuario.
- Proveer pasos de prueba para verificar el flujo.

---

### Resumen del flujo

1. Cliente: el formulario de login (`html/iniciarSesion.html`) realiza validaciones básicas de entrada (campos obligatorios, formato de correo, longitud de contraseña) y muestra mensajes de error debajo de cada campo.
2. Cliente: si la validación cliente pasa, se envía el formulario al servidor (`php/login.php`) via POST.
3. Servidor: `php/login.php` valida y sanitiza entradas nuevamente. Si hay errores, devuelve mensajes (o redirige con errores en sesión).
4. Servidor: busca el usuario en la base de datos usando consultas preparadas.
5. Servidor: si el usuario existe, usa `password_verify($password, $hashEnBD)` para verificar la contraseña.
6. Servidor: si la verificación es correcta, iniciar sesión segura con `session_start()` y regenerar id de sesión (`session_regenerate_id(true)`), setear datos mínimos en `$_SESSION` y redirigir al área privada.
7. Servidor: si la verificación falla, retornar error sin indicar si el usuario existe (mensaje genérico como "Credenciales inválidas").

---

### Recomendaciones de seguridad

- Nunca almacenar contraseñas en texto plano.
- Usar `password_hash()` con el algoritmo por defecto. Para verificar, usar `password_verify()`.
- Usar conexiones seguras (HTTPS) para enviar credenciales.
- Configurar cabeceras y cookies seguras: `session_set_cookie_params(['httponly' => true, 'secure' => true, 'samesite' => 'Lax']);` antes de `session_start()` en producción.
- Regenerar id de sesión al iniciar sesión y destruir la sesión al cerrar sesión.

---

### Ejemplo de código (PHP + MySQLi — consultas preparadas)

php/login.php (ejemplo simplificado)

```php
<?php
// ejemplo: php/login.php
require_once __DIR__ . '/../BD/db.php'; // adapta la ruta a tu configuracion

// validación básica en servidor
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// validar entradas
if (empty($email) || empty($password)) {
    // manejar error (ej. redirigir con mensaje)
    exit('Credenciales inválidas');
}

// conectar a BD (usando mysqli como ejemplo)
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    error_log('DB connect error: ' . $mysqli->connect_error);
    exit('Error interno');
}

$stmt = $mysqli->prepare('SELECT id, password_hash FROM usuarios WHERE email = ? LIMIT 1');
if (!$stmt) {
    error_log('Prepare failed: ' . $mysqli->error);
    exit('Error interno');
}

$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    // no exponer si el email no existe
    $stmt->close();
    exit('Credenciales inválidas');
}

$stmt->bind_result($userId, $hash);
$stmt->fetch();

// verificar contraseña
if (!password_verify($password, $hash)) {
    // fallo
    $stmt->close();
    exit('Credenciales inválidas');
}

$stmt->close();

// inicio de sesión seguro
session_start();
session_regenerate_id(true);
$_SESSION['user_id'] = $userId;
// almacenar solo lo necesario

// redirigir al panel
header('Location: /html/index.html');
exit();
```

---

### Ejemplo breve con PDO (más recomendado)

```php
<?php
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$stmt = $pdo->prepare('SELECT id, password_hash FROM usuarios WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row || !password_verify($password, $row['password_hash'])) {
    exit('Credenciales inválidas');
}

session_start();
session_regenerate_id(true);
$_SESSION['user_id'] = $row['id'];
```

---

### Validaciones recomendadas en `js/form.js` (cliente)

- Mostrar mensajes de error debajo de cada campo.
- Validar: email con regex simple, contraseña con longitud mínima (ej. 8), campos obligatorios.
- Evitar enviar datos si hay errores de validación.

Ejemplo corto (pseudocódigo):

```js
// obtener elementos, validar y mostrar errores en elementos <small class="error">...
// si pasa, form.submit();
```

---

### Pruebas y verificación (pasos)

1. Prueba de validación cliente: enviar campos vacíos y verificar que se muestran mensajes debajo de cada campo.
2. Prueba de formato de correo: enviar `usuario@` y verificar bloqueo cliente.
3. Prueba de credenciales inválidas: usar email existente con contraseña incorrecta — servidor debe responder con mensaje genérico.
4. Prueba de credenciales válidas: usar email y contraseña correctos — verificar que se crea sesión (`$_SESSION['user_id']`) y que `session_id()` cambia tras inicio.
5. Prueba de seguridad: intentar inyección SQL en el campo email y verificar que no afecta la consulta.
6. Revisar cookies de sesión: `HttpOnly`, `Secure` (en HTTPS) y `SameSite`.

---

### Archivo(s) relacionados en el proyecto

- `php/login.php` — punto de entrada del login.
- `php/logout.php` — debe destruir la sesión y redirigir.
- `html/iniciarSesion.html` — formulario cliente.
- `js/form.js` — validaciones y mensajes de error en cliente.

---

Si quieres, puedo:

- 1) Implementar directamente las validaciones en `js/form.js` y mejorar `html/iniciarSesion.html` (UI y mensajes).
- 2) Actualizar `php/login.php` para usar el código de ejemplo con consultas preparadas y `password_verify`.
- 3) Añadir un archivo de pruebas `docs/tests/auth-tests.md` con casos y comandos curl/selenium básicos.

Dime cuál de las opciones prefieres y lo implemento con commits reales.
