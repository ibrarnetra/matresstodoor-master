<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\WeightClassSeeder;
use Database\Seeders\TaxRateSeeder;
use Database\Seeders\TaxClassSeeder;
use Database\Seeders\SubscriberPermissionSeeder;
use Database\Seeders\StoreSeeder;
use Database\Seeders\StockStatusSeeder;
use Database\Seeders\SliderPermissionSeeder;
use Database\Seeders\ShippingMethodSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\RoutePermissionSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\OrderStatusSeeder;
use Database\Seeders\OptionValueSeeder;
use Database\Seeders\OptionSeeder;
use Database\Seeders\ManufacturerSeeder;
use Database\Seeders\LoadingSheetPermissionSeeder;
use Database\Seeders\LengthClassSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\GeozoneSeeder;
use Database\Seeders\FaqPermissionSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\CustomerGroupSeeder;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\AttributeSeeder;
use Database\Seeders\AttributeGroupSeeder;
use Database\Seeders\AddressSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesAndPermissionsSeeder::class, ### IMPORTANT ###
            LanguageSeeder::class, ### IMPORTANT ###
            StoreSeeder::class, ### IMPORTANT ###
            SettingSeeder::class, ### IMPORTANT ###
            ManufacturerSeeder::class,
            CurrencySeeder::class, ### IMPORTANT ###
            StockStatusSeeder::class, ### IMPORTANT ###
            OrderStatusSeeder::class, ### IMPORTANT ###
            CustomerGroupSeeder::class, ### IMPORTANT ###
            TaxRateSeeder::class, ### IMPORTANT ###
            AttributeGroupSeeder::class,
            // AttributeSeeder::class,
            WeightClassSeeder::class, ### IMPORTANT ###
            LengthClassSeeder::class, ### IMPORTANT ###
            CategorySeeder::class,
            OptionSeeder::class,
            OptionValueSeeder::class,
            TaxClassSeeder::class, ### IMPORTANT ###
            GeozoneSeeder::class, ### IMPORTANT ###
            CustomerSeeder::class,
            AddressSeeder::class,
            ShippingMethodSeeder::class, ### IMPORTANT ###
            PaymentMethodSeeder::class, ### IMPORTANT ###
            RoutePermissionSeeder::class, ### IMPORTANT ###
            RoutePermissionSeeder::class, ### IMPORTANT ###
            LoadingSheetPermissionSeeder::class, ### IMPORTANT ###
            FaqPermissionSeeder::class, ### IMPORTANT ###
            SliderPermissionSeeder::class, ### IMPORTANT ###
            SubscriberPermissionSeeder::class, ### IMPORTANT ###
        ]);
    }
}
