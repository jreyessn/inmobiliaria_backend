CreateAuditTable: create table `audit` (`id` bigint unsigned not null auto_increment primary key, `action` varchar(191) not null, `query` varchar(191) null, `user_id` bigint unsigned null, `model_type` varchar(191) not null, `model_id` bigint unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreateAuditTable: alter table `audit` add constraint `audit_user_id_foreign` foreign key (`user_id`) references `users` (`id`)
CreateAuditTable: alter table `audit` add index `audit_model_type_model_id_index`(`model_type`, `model_id`)
CreateImagesTable: create table `images` (`id` bigint unsigned not null auto_increment primary key, `model_type` varchar(191) not null, `model_id` bigint unsigned not null, `type` varchar(191) not null, `name` varchar(191) not null, `original_name` varchar(191) not null, `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreateImagesTable: alter table `images` add index `images_model_type_model_id_index`(`model_type`, `model_id`)
CreateMeasureUnitTable: create table `measure_units` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(191) not null, `description` varchar(191) null, `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreateTypeFurnitureTable: create table `type_furnitures` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(191) not null, `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreateUrbanitationTable: create table `urbanitations` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(191) not null, `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreateCustomerTable: create table `customers` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(191) not null, `email` varchar(191) null, `phone` varchar(191) null, `limit_credit` double(8, 2) not null default '0', `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreateDocumentTable: create table `documents` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(191) not null, `code` varchar(191) not null, `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreatePaymentMethodTable: create table `payment_methods` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(191) not null, `type` varchar(191) not null, `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreateFurnitureTable: create table `furniture` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(191) not null, `description` text null, `bathrooms` int not null default '0', `bedrooms` int not null default '0', `covered_garages` int not null default '0', `uncovered_garages` int not null default '0', `measure_unit_id` bigint unsigned not null, `area` varchar(191) null, `unit_price` double(8, 2) not null default '0', `sale_price` double(8, 2) not null default '0', `type_furniture_id` bigint unsigned not null, `city_id` bigint unsigned null, `postal_code` varchar(191) null, `region` varchar(191) null, `address` varchar(191) null, `street_number` varchar(191) null, `aditional_info_address` varchar(191) null, `flat` int not null default '0', `reference_address` varchar(191) null, `getter_user_id` bigint unsigned null, `agent_user_id` bigint unsigned null, `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreateFurnitureTable: alter table `furniture` add constraint `furniture_measure_unit_id_foreign` foreign key (`measure_unit_id`) references `measure_units` (`id`)
CreateFurnitureTable: alter table `furniture` add constraint `furniture_type_furniture_id_foreign` foreign key (`type_furniture_id`) references `type_furnitures` (`id`)
CreateFurnitureTable: alter table `furniture` add constraint `furniture_getter_user_id_foreign` foreign key (`getter_user_id`) references `users` (`id`)
CreateFurnitureTable: alter table `furniture` add constraint `furniture_agent_user_id_foreign` foreign key (`agent_user_id`) references `users` (`id`)
CreateSaleTable: create table `sales` (`id` bigint unsigned not null auto_increment primary key, `serie` varchar(191) null, `number` varchar(191) null, `furniture_id` bigint unsigned not null, `document_id` bigint unsigned not null, `customer_id` bigint unsigned not null, `payment_method_id` bigint unsigned not null, `subtotal` double(8, 2) not null default '0', `tax_percentage` double(8, 2) not null default '0', `total` double(8, 2) not null default '0', `note` text null, `is_credit` tinyint(1) not null default '0', `status` tinyint(1) not null default '0', `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreateSaleTable: alter table `sales` add constraint `sales_furniture_id_foreign` foreign key (`furniture_id`) references `furniture` (`id`)
CreateSaleTable: alter table `sales` add constraint `sales_document_id_foreign` foreign key (`document_id`) references `documents` (`id`)
CreateSaleTable: alter table `sales` add constraint `sales_customer_id_foreign` foreign key (`customer_id`) references `customers` (`id`)
CreateSaleTable: alter table `sales` add constraint `sales_payment_method_id_foreign` foreign key (`payment_method_id`) references `payment_methods` (`id`)
CreateCreditTable: create table `credits` (`id` bigint unsigned not null auto_increment primary key, `sale_id` bigint unsigned not null, `amount_anticipated` double(8, 2) not null default '0', `interest_percentage` double(8, 2) not null default '0', `total` double(8, 2) not null default '0', `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreateCreditTable: alter table `credits` add constraint `credits_sale_id_foreign` foreign key (`sale_id`) references `sales` (`id`)
CreateCreditCuoteTable: create table `credit_cuotes` (`id` bigint unsigned not null auto_increment primary key, `credit_id` bigint unsigned not null, `number_letter` varchar(191) null, `reference` varchar(100) null, `giro_at` timestamp null, `expiration_at` timestamp null, `total` double(8, 2) not null default '0', `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreateCreditCuoteTable: alter table `credit_cuotes` add constraint `credit_cuotes_credit_id_foreign` foreign key (`credit_id`) references `credits` (`id`)
CreateCreditPaymentTable: create table `credit_payments` (`id` bigint unsigned not null auto_increment primary key, `amount` double(8, 2) not null default '0', `credit_cuote_id` bigint unsigned not null, `payment_method_id` bigint unsigned not null, `note` text null, `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreateCreditPaymentTable: alter table `credit_payments` add constraint `credit_payments_credit_cuote_id_foreign` foreign key (`credit_cuote_id`) references `credit_cuotes` (`id`)
CreateCreditPaymentTable: alter table `credit_payments` add constraint `credit_payments_payment_method_id_foreign` foreign key (`payment_method_id`) references `payment_methods` (`id`)
CreateUsersPreferencesTable: create table `users_preferences` (`user_id` bigint unsigned not null, `key` varchar(191) not null, `value` varchar(191) null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
CreateUsersPreferencesTable: alter table `users_preferences` add constraint `users_preferences_user_id_foreign` foreign key (`user_id`) references `users` (`id`)
CreateUsersPreferencesTable: alter table `users_preferences` add primary key `users_preferences_key_primary`(`key`)
