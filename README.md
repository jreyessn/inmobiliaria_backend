## Levantar el sistema

Crear y configurar el archivo .env (copiar el .env.example) con las credenciales de la base de datos.

Correr el comando 
```
php artisan migrate:fresh --seed && php artisan passport:install
```