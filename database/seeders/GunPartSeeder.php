<?php

namespace Database\Seeders;

use App\Models\Firearm;
use App\Models\GunPart;
use Illuminate\Database\Seeder;

class GunPartSeeder extends Seeder
{
    public function run(): void
    {
        $firearm = Firearm::where('slug', '9mm')->first();
        if (!$firearm) return;

        $parts = [
            [
                'slug' => 'frame',
                'name' => 'Frame / Lower Receiver',
                'description' => '<b>Frame / Lower Receiver</b> - Polymer lower. Houses trigger group, mag well, and grip. The serialized component.',
                'sort_order' => 1,
                'z_order' => 1,
                'image_path' => 'images/assemble/9mm/FRAME.png',
                'glow_image_path' => 'images/assemble/9mm/glow guide/FRAME-GLOW.png',
                'zone_x' => 55, 'zone_y' => 30, 'zone_w' => 420, 'zone_h' => 310,
            ],
            [
                'slug' => 'barrel',
                'name' => 'Barrel',
                'description' => '<b>Barrel</b> - Rifled steel barrel. Drops into the slide from the top.',
                'sort_order' => 2,
                'z_order' => 2,
                'image_path' => 'images/assemble/9mm/BARREL.png',
                'glow_image_path' => 'images/assemble/9mm/glow guide/BARREL-GLOW.png',
                'zone_x' => 112, 'zone_y' => 106, 'zone_w' => 270, 'zone_h' => 120,
            ],
            [
                'slug' => 'slide',
                'name' => 'Slide',
                'description' => '<b>Slide</b> - Steel slide with rear serrations. Rides on the frame rails.',
                'sort_order' => 3,
                'z_order' => 3,
                'image_path' => 'images/assemble/9mm/SLIDE.png',
                'glow_image_path' => 'images/assemble/9mm/glow guide/SLIDE-GLOW.png',
                'zone_x' => 42, 'zone_y' => 20, 'zone_w' => 510, 'zone_h' => 145,
            ],
            [
                'slug' => 'magazine',
                'name' => 'Magazine',
                'description' => '<b>Magazine</b> - Double-stack 9mm mag, 15-17 round capacity. Slides up into the grip from below.',
                'sort_order' => 4,
                'z_order' => 0,
                'image_path' => 'images/assemble/9mm/MAGAZINE.png',
                'glow_image_path' => 'images/assemble/9mm/glow guide/MAGAZINE-GLOW.png',
                'zone_x' => 230, 'zone_y' => 160, 'zone_w' => 130, 'zone_h' => 195,
            ],
        ];

        foreach ($parts as $data) {
            $data['firearm_id'] = $firearm->id;
            GunPart::create($data);
        }
    }
}
