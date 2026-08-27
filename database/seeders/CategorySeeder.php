<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['Metering Equipment', 'Flow meters, bulk meters, PD meters, gas meters and process measurement equipment for transfer, process and utility applications.'],
            ['Pumping Equipment', 'Oil, grease, transfer, diaphragm, hand and centrifugal pumps for workshops, plants, fuel systems and MEP requirements.'],
            ['Electronic Devices', 'Flow computers, batch controllers, grounding systems, pulse emitters, tag readers and forecourt control devices.'],
            ['Valves', 'Fuel valves, process valves, ball valves, gate valves, diaphragm valves, nozzles, limit switches and regulators.'],
            ['Pressure Measurement', 'Industrial pressure gauges, transmitters, switches, diaphragm seals and differential pressure measurement products.'],
            ['Level Measurement', 'Tank gauging, ultrasonic level gauges, level alarms, radar level instruments and tubular level gauges.'],
            ['Temperature Measurement', 'RTD assemblies, industrial thermometers, bimetal dial thermometers and digital temperature gauges.'],
            ['Software / Automation Systems', 'Terminal automation, building management, energy management and fuel management systems.'],
            ['Accessories', 'EV chargers, charging leads, filters, gas separators, siphons, bearing testers, tyre inflators and maintenance equipment.'],
            ['MEP Items', 'Steel pipes, fittings and power control panels for mechanical, electrical and plumbing projects.'],
            ['Vastu Remedies / Consultation', 'Vastu evaluation and consultation for property selection, building plans and remedial recommendations.'],
        ];

        foreach ($categories as $i => [$name, $description]) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $description,
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
