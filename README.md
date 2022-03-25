## Migraciones en servidor compartido

Se habilitará la ruta **api/artisan** para correr las migraciones en el servidor compartido. Esta validará por medio de queryParams el comando a ejecutar y la contraseña de la base de datos (de esta manera, por lo menos se intenta evitar que la ruta sea de tan acceso publico, aunque sea una brecha de seguridad).

### Parametros GET que son aceptados:

| param      | description  | validation |
|----------------|-------------------------------|-----------------------------
| sentence | La sentencia a ejecutar. Ejemplo: **artisan** o **seed**| nullable |
| password  | Contraseña de base de datos. Debe coincidir con el .env  | required |
> **Nota:** La sentencia de enviarse nula, no ejecutará nada.
