<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'company_name' => 'Bedeck International',
            'company_tagline' => 'We bring you the best in the world.',
            'company_role' => 'Engineering Consultants',
            'established_year' => '2010',
            'hero_eyebrow' => 'Industrial equipment supplier and engineering consultant in Sri Lanka',
            'hero_heading' => 'Engineering solutions for industrial operations.',
            'hero_intro' => 'We bring you the best in the world. Bedeck International has supported Sri Lankan industrial requirements since 2010 with dependable engineering consultation, product sourcing and total industrial solutions.',
            'address' => '10/3, Salmal Place, Devala Road, Depanama, Pannipitiya 10230, Sri Lanka',
            'phone' => '+94 11 274 6006',
            'whatsapp' => '94771711440',
            'whatsapp_display' => '+94 77 171 1440',
            'email' => 'info@bedeckinternational.lk',
            'email_alt' => 'bedeck@sltnet.lk',
        ];

        foreach ($defaults as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
