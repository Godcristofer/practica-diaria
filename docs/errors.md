# Manejo de errores y mensajes para el sistema de autenticación

Este documento describe las decisiones de diseño para el manejo de errores en el proceso de login y registro, y ejemplos de mensajes tanto para el cliente como para el servidor.

## Principios

- No revelar información sensible en mensajes de error (por ejemplo: no mostrar "usuario no existe" o "contraseña incorrecta" por separado).
- Mostrar mensajes útiles para el usuario, pero genéricos en casos de autenticación fallida: "Credenciales inválidas".
- Registrar detalles técnicos en logs del servidor (`error_log`) para facilitar la depuración sin exponer datos.
- Validar siempre en cliente y servidor.

## Comportamiento implementado

- El cliente valida campos requeridos, formato de correo y longitud mínima de contraseña. Los errores se muestran debajo del campo.
- Si la autenticación falla en el servidor, redirigimos al formulario de login con `?error=1` y el cliente presenta un mensaje genérico visible en la parte superior del formulario.
- Los errores de la base de datos o fallos en `prepare`/`execute` se registran con `error_log()` y se devuelve un mensaje genérico al usuario.

## Ejemplos de mensajes

- Error genérico de autenticación: "Credenciales inválidas o error en el servidor. Intenta de nuevo."
- Campo vacío: "Este campo es obligatorio"
- Correo inválido: "Ingrese un correo válido"
- Contraseña corta: "La contraseña debe tener al menos 6 caracteres"

## Sugerencias adicionales

- En producción, usar HTTPS y cookies de sesión con `HttpOnly` y `Secure`.
- Considerar límites de intentos y bloqueo temporal para prevenir ataques de fuerza bruta.
- Para mejorar UX, usar notificaciones amigables (ej. SweetAlert) para mensajes globales y mantener mensajes de campo inline para validación.
