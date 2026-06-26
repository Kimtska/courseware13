<?php

namespace Database\Seeders;

use App\Models\Target;
use App\Models\TargetDetail;
use App\Models\TargetMode;
use Illuminate\Database\Seeder;

class TargetSeeder extends Seeder
{
    public function run(): void
    {
        $target = Target::create([
            'name' => 'Standard Target',
            'slug' => 'standard',
            'image_path' => 'images/targets/default-target.svg',
            'description' => 'Standard circular marksmanship target with five scoring zones.',
            'is_active' => true,
        ]);

        $zones = [
            ['name' => 'bullseye', 'display_name' => 'Bullseye', 'points' => 20, 'color' => '#dc2626', 'image_path' => 'images/targets/zones/bullseye.svg', 'sort_order' => 1],
            ['name' => 'alpha', 'display_name' => 'Alpha', 'points' => 10, 'color' => '#dc2626', 'image_path' => 'images/targets/zones/alpha.svg', 'sort_order' => 2],
            ['name' => 'bravo', 'display_name' => 'Bravo', 'points' => 5, 'color' => '#ffffff', 'image_path' => 'images/targets/zones/bravo.svg', 'sort_order' => 3],
            ['name' => 'charlie', 'display_name' => 'Charlie', 'points' => 3, 'color' => '#000000', 'image_path' => 'images/targets/zones/charlie.svg', 'sort_order' => 4],
            ['name' => 'delta', 'display_name' => 'Delta', 'points' => 1, 'color' => '#000000', 'image_path' => 'images/targets/zones/delta.svg', 'sort_order' => 5],
        ];

        foreach ($zones as $zone) {
            $target->details()->create($zone);
        }

        $modes = [
            ['name' => 'steady', 'display_name' => 'Steady', 'description' => 'Center target, pure accuracy'],
            ['name' => 'sideways', 'display_name' => 'Sideways', 'description' => 'Horizontal tracking target'],
            ['name' => 'around', 'display_name' => 'Around', 'description' => 'Random movement target'],
        ];

        foreach ($modes as $mode) {
            $target->modes()->create($mode);
        }
    }
}
