<?php

namespace Database\Seeders;

use App\Models\AssessmentSimulation;
use Illuminate\Database\Seeder;

class FirearmSeeder extends Seeder
{
    public function run(): void
    {
        $firearms = [
            [
                'slug' => '9mm',
                'name' => '9mm Pistol',
                'type' => 'Pistol',
                'caliber' => '9×19mm Parabellum',
                'mag_size' => 15,
                'image_url' => '/images/assets/9mm.png',
                'description' => 'Semi-automatic pistol chambered in 9×19mm Parabellum, the standard NATO pistol round. Known for its manageable recoil and high magazine capacity.',
            ],
            [
                'slug' => '45',
                'name' => '.45 Pistol',
                'type' => 'Pistol',
                'caliber' => '.45 ACP',
                'mag_size' => 7,
                'image_url' => '/images/assets/45.png',
                'description' => '1911-style semi-automatic pistol chambered in .45 ACP. Renowned for its stopping power and iconic design.',
            ],
            [
                'slug' => '38',
                'name' => '.38 Pistol Revolver',
                'type' => 'Revolver',
                'caliber' => '.38 Special',
                'mag_size' => 6,
                'image_url' => '/images/assets/38.png',
                'description' => 'Six-shot revolver chambered in .38 Special. Features a swing-out cylinder for loading and unloading.',
            ],
            [
                'slug' => 'shotgun',
                'name' => '12-Gauge Shotgun',
                'type' => 'Shotgun',
                'caliber' => '12-Gauge',
                'mag_size' => 5,
                'image_url' => '/images/assets/shotgun.png',
                'description' => 'Pump-action 12-gauge shotgun. Delivers a wide spread of projectiles, effective at close to medium range.',
            ],
        ];

        foreach ($firearms as $data) {
            AssessmentSimulation::create($data);
        }
    }
}
