<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Schema::disableForeignKeyConstraints();

        $this->call([
            PermissionsTableSeeder::class,
            UserSeeder::class,
            AccountsGroupsSeeder::class,
            AssociatedAccountsSeeder::class,
            BankCountriesSeeder::class,
            BanksSeeder::class,
            BusinessTypesSeeder::class,
            CurrenciesSeeder::class,
            DocumentsSeeder::class,
            OrganizationsSeeder::class,
            PaymentConditionsSeeder::class,
            PaymentMethodsSeeder::class,
            RetentionCountriesSeeder::class,
            RetentionIndicatorsSeeder::class,
            RetentionTypesSeeder::class,
            SocietiesSeeder::class,
            ToleranceGroupsSeeder::class,
            FileSeeder::class,
            TreatmentsSeeder::class,
            TypeBankInterlocutorSeeder::class,
            GroupsSeeder::class,
            TreasuryGroupsSeeder::class,
            TypeProvidersSeeder::class,
        ]);

        // Schema::enableForeignKeyConstraints();
    }
}
