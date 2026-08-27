<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'Upstream and Downstream Petroleum Installations and Supplies',
                'assets/images/services/upstream-petroleum.webp',
                'Systems integration and distribution of petroleum equipment and accessories.',
                'Systems integration and distribution of petroleum equipment and accessories, including metering skids, plants, pumping stations, dispensing systems, storage tanks, unloading skids, dosing systems and fuel systems for trucks and trailers.',
            ],
            [
                'Process Equipment Installations and Supplies',
                'assets/images/services/process-equipment.webp',
                'Equipment services and process solutions for production environments.',
                'Equipment services and process solutions for production environments including power and energy, food and beverage, automotive, metalworking, chemical processes, biotechnology, pulp and paper and refinery applications.',
            ],
            [
                'MEP Services and Equipment Supplies',
                'assets/images/services/mep-services.webp',
                'Mechanical engineering, valves, pipes, instrumentation, pumps and control panels.',
                'Mechanical engineering, manual and control valves, steel pipes and fittings, pressure and temperature measurement equipment, pumps, control panels, switchgear and industrial wiring.',
            ],
            [
                'Energy Management Solutions',
                'assets/images/services/energy-management.webp',
                'Energy-saving and conservation services for homes and businesses.',
                'Energy-saving and conservation services for homes and businesses, focused on reducing power consumption, identifying conservation opportunities and supporting improved operating costs.',
            ],
            [
                'Building Management Solutions',
                'assets/images/services/building-management.webp',
                'Automation and control systems for building and facility services.',
                'Automation and control systems for monitoring and managing electrical, mechanical and electromechanical services in buildings and facilities.',
            ],
            [
                'Fuel Management Solutions',
                'assets/images/services/fuel-management.webp',
                'Fuel management systems, forecourt automation and commercial fuel handling.',
                'Fuel management systems, forecourt automation, smart dispensers and commercial fuel handling support for organizations requiring improved accuracy, accountability and security.',
            ],
            [
                'Vastu Consultation Services',
                'assets/images/services/vastu-consultation.webp',
                'Vastu consultation and remedial corrections for property and buildings.',
                'Vastu consultation and remedial corrections, including property selection, building plan review, home or workplace evaluation and corrective recommendations.',
            ],
        ];

        foreach ($services as $i => [$title, $icon, $summary, $body]) {
            Service::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'icon' => $icon,
                    'summary' => $summary,
                    'body' => $body,
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
