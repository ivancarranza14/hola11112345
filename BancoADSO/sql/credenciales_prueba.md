# Credenciales de prueba

Tu base de datos `db_banco_adso` ya traía sus propios clientes y cuentas
(no los de `siembra_datos.sql`). La tabla `usuarios` estaba vacía (sin
contraseñas), así que se creó un usuario de prueba para cada cuenta ya
existente, todos con la misma contraseña de prueba.

| Número de cuenta | Cliente              | Contraseña  | Saldo (antes de las pruebas) |
|-------------------|----------------------|-------------|-------------------------------|
| 10001              | Carlos Chinchia      | clave1234   | 80.000.000,00                 |
| 10002              | Ivan Carranza        | clave1234   | 10.000.000,00                 |
| 10003              | Jhosep Suarez        | clave1234   | 3.000.000,00                  |
| 10004              | Anabell Gonzales     | clave1234   | 450.000,00                    |
| 10005              | Sherman Sarmiento    | clave1234   | 100.000,00                    |

Nota: `siembra_datos.sql` (cuentas 1001-1005) ya NO aplica a tu base de
datos real; se deja solo como referencia de qué formato de INSERT usar.

Para la sustentación, un flujo probado y que funciona:
1. Inicia sesión con 10001 / clave1234.
2. Haz un retiro (por ejemplo 50.000) y revisa que el saldo baje.
3. Haz una transferencia a 10002 (por ejemplo 100.000) y revisa el
   historial de transferencias enviadas.
4. Cierra sesión, inicia con 10002 / clave1234 y confirma que el saldo
   subió exactamente lo transferido (demuestra la atomicidad, RF6).

Borra o no subas este archivo a un repositorio público si vas a compartir
el proyecto, ya que contiene una contraseña de prueba real de tu base de
datos.
