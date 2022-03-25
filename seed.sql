Seeding: Database\Seeders\RoleSeeder

   Illuminate\Database\QueryException 

  SQLSTATE[42S02]: Base table or view not found: 1146 Table 'inmobiliaria_prod.roles' doesn't exist (SQL: select * from `roles` where `name` = Administrador and `guard_name` = web limit 1)

  at vendor/laravel/framework/src/Illuminate/Database/Connection.php:671
    667▕         // If an exception occurs when attempting to run a query, we'll format the error
    668▕         // message to include the bindings with SQL, which will make this exception a
    669▕         // lot more helpful to the developer instead of just the database's errors.
    670▕         catch (Exception $e) {
  ➜ 671▕             throw new QueryException(
    672▕                 $query, $this->prepareBindings($bindings), $e
    673▕             );
    674▕         }
    675▕

  • A table was not found: You might have forgotten to run your migrations. You can run your migrations using `php artisan migrate`. 
    https://laravel.com/docs/master/migrations#running-migrations

      [2m+13 vendor frames [22m
  14  database/seeders/RoleSeeder.php:17
      Spatie\Permission\Models\Role::create(["Administrador", "web"])

      [2m+8 vendor frames [22m
  23  database/seeders/DatabaseSeeder.php:20
      Illuminate\Database\Seeder::call("Database\Seeders\RoleSeeder")
