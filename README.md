# Migrations

php artisan make:repository furniture/measure_unit --skip-migration
php artisan make:repository furniture/type_furniture --skip-migration
php artisan make:repository furniture/urbanitation --skip-migration
php artisan make:repository furniture/furniture --skip-migration
php artisan make:repository country/country --skip-migration
php artisan make:repository country/city --skip-migration
php artisan make:repository customer/customer --skip-migration
php artisan make:repository sale/sale --skip-migration
php artisan make:repository sale/document --skip-migration
php artisan make:repository sale/payment_method --skip-migration
php artisan make:repository sale/credit --skip-migration
php artisan make:repository sale/credit_cuote --skip-migration
php artisan make:repository sale/credit_payment --skip-migration

php artisan make:migration create_measure_unit_table 
php artisan make:migration create_type_furniture_table 
php artisan make:migration create_urbanitation_table 
php artisan make:migration create_country_table 
php artisan make:migration create_city_table 
php artisan make:migration create_customer_table 
php artisan make:migration create_document_table 
php artisan make:migration create_payment_method_table 
php artisan make:migration create_credit_table 
php artisan make:migration create_credit_cuote_table 
php artisan make:migration create_credit_payment_table 
php artisan make:migration create_furniture_table 
php artisan make:migration create_sale_table 