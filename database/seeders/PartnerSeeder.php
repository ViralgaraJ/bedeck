<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            ['ISOIL Impianti S.p.A.', 'Italy', 'assets/optimized/isoil-logo.webp', 'https://www.isoilmeter.it/en'],
            ['OMC', 'Italy', 'assets/optimized/omc-logo.webp', 'https://www.omcavourresi.com/en/'],
            ['Fluidwell bv', 'Netherlands', 'assets/optimized/fluidwell-logo.webp', 'https://www.fluidwell.com/'],
            ['Hytek GB Ltd', 'United Kingdom', 'assets/optimized/hytek-logo.webp', 'https://hytekgb.com/'],
            ['PRO TECH / Progressive Technologies', 'India', 'assets/optimized/protech-logo.webp', 'https://www.protech-india.com/'],
            ['Accord Fuelling Services', 'India', 'assets/optimized/accord-logo.webp', 'https://accordfuelling.com/'],
            ['Excel Instruments', 'India', 'assets/optimized/excel-logo.webp', 'https://www.excelinstruments.com/'],
            ['UFLOW Automation', 'India', 'assets/optimized/uflow-logo.webp', 'https://www.uflowvalve.com/'],
        ];

        foreach ($partners as $i => [$name, $country, $logo, $website]) {
            Partner::updateOrCreate(
                ['name' => $name],
                [
                    'country' => $country,
                    'logo' => $logo,
                    'website' => $website,
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
