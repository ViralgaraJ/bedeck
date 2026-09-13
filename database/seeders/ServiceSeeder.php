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
                'Bedeck International is a systems integrator and distributor of petroleum equipment and accessories, providing comprehensive solutions for the petroleum and fuel industries. Our product portfolio includes metering skids, petroleum plants, pumping stations, fuel dispensing systems, and storage tanks.',
                'We also provide vapour recovery systems, on-site fuel unloading skids, dosing systems, and fuel systems for trucks and trailers. Our capabilities extend from engineering and system design to construction, turnkey installation, commissioning, and on-site training, providing customers with complete end-to-end solutions.'
            ],
            [
                'Process Equipment Installations and Supplies',
                'assets/images/services/process-equipment.webp',
                'Equipment services and process solutions for production environments.',
                'Bedeck International provides comprehensive process equipment solutions, offering high-quality equipment, services, and engineering solutions designed to maximize production efficiency and operational performance.',
                'Our portfolio includes specialized products and solutions for a wide range of industries, including power and energy, food and beverage, automotive and metalworking, chemical processing, biotechnology, pulp and paper, and petroleum refining. We support our customers with reliable equipment and tailored solutions to meet their specific process requirements.'
            ],
            [
                'MEP Services and Equipment Supplies',
                'assets/images/services/mep-services.webp',
                'Mechanical engineering, valves, pipes, instrumentation, pumps and control panels.',
                'Our extensive portfolio includes manual and control valves, steel pipes and fittings, safety valves, pressure and temperature measurement equipment, pumping equipment, control panels and switchgear, and industrial wiring.',
                'We provide reliable products and engineering solutions to support efficient, safe, and dependable operations across a wide range of industrial applications.'
            ],
            [
                'Energy Management Solutions',
                'assets/images/services/energy-management.webp',
                'Energy-saving and conservation services for homes and businesses.',
                'Bedeck International provides comprehensive Energy Management System (EMS) solutions designed to help homes and businesses improve energy efficiency, reduce power consumption, and achieve sustainable cost savings.',
                'Our energy management solutions focus on practical and cost-effective strategies for monitoring, controlling, and optimizing energy usage. We work with organizations to identify opportunities for energy conservation and implement tailored solutions that maximize energy efficiency while supporting their operational and financial objectives.',
                'Our goal is to provide realistic, reliable, and sustainable energy management solutions that help customers optimize energy consumption, reduce operating costs, and improve overall performance.'
                
            ],
            [
                'Building Management Solutions',
                'assets/images/services/building-management.webp',
                'Automation and control systems for building and facility services.',
                'Bedeck International provides advanced Building Management System (BMS) solutions for the intelligent monitoring, automation, and control of electrical, mechanical, and electromechanical services within modern facilities.',
                'With rapid advancements in technology and the growing adoption of smart automation, effective building management has become essential for improving operational efficiency, safety, comfort, and energy performance. Our integrated BMS solutions enable seamless control and monitoring of building systems, providing a smart and efficient approach to modern infrastructure management.',
                'We offer reliable and scalable automation solutions designed to meet the evolving needs of commercial, industrial, and institutional facilities.'
            ],
            [
                'Fuel Management Solutions',
                'assets/images/services/fuel-management.webp',
                'Fuel management systems, forecourt automation and commercial fuel handling.',
                'Bedeck International provides comprehensive Fuel Management System (FMS) solutions for organizations seeking greater accuracy, accountability, security, and productivity in managing their fuel assets.',
                'Our fuel management solutions integrate pump control and tank monitoring to provide complete visibility and control over fuel operations. The system enables efficient management of fuel dispensing, pump sales, tank stock levels, unit pricing, shift operations, pump attendants, and customer transactions.',
                'With accurate monitoring and real-time data, our solutions help organizations minimize fuel losses, improve operational efficiency, strengthen accountability, and optimize overall fuel management. Customers can achieve fuel reconciliation rates of over 99.5%, supporting greater control and confidence in their fuel operations.'
            ],
            [
                'Vastu Consultation Services',
                'assets/images/services/vastu-consultation.webp',
                'Vastu consultation and remedial corrections for property and buildings.',
                'Bedeck International offers professional Vastu consultation services designed to help create harmonious, balanced, and positive environments in homes and workplaces.',
                'Our approach involves analyzing the spatial characteristics of a property, identifying areas that may require attention, and providing practical recommendations for improvement. Based on the assessment, we propose suitable design modifications or remedial measures to enhance the overall balance and functionality of the space.',
                'Through our Vastu consultation services, we aim to help clients create more comfortable, harmonious, and positive living and working environments.'
                
            ],
        ];

        foreach ($services as $i => $service) {
            [$title, $icon, $summary] = $service;
            $body = implode("\n\n", array_slice($service, 3));

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
