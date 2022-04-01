## Levantar el sistema

- Crear y configurar el archivo .env (copiar el .env.example) con las credenciales de la base de datos
- Correr el comando **php artisan migrate:fresh --seed && php artisan passport:install**

## Migraciones en servidor compartido

Se habilitará la ruta **api/artisan** para correr las migraciones en el servidor compartido. Esta validará por medio de queryParams el comando a ejecutar y la contraseña de la base de datos (de esta manera, por lo menos se intenta evitar que la ruta sea de tan acceso publico, aunque sea una brecha de seguridad).

### Parametros GET que son aceptados:

| param      | description  | validation |
|----------------|-------------------------------|-----------------------------
| sentence | La sentencia a ejecutar. Ejemplo: **reset** o **migrate**| nullable |
| password  | Contraseña de base de datos. Debe coincidir con el .env  | required |
> **Nota:** La sentencia de enviarse nula, no ejecutará nada.
