<?php

namespace Database\Seeders;

use App\Models\Firearm;
use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleFirearmSeeder extends Seeder
{
    public function run(): void
    {
        $modules = Module::pluck('id', 'module_key');
        $firearms = Firearm::pluck('id', 'slug');

        $pivot = [
            // Module 1: all 4 firearms shown as profiles
            ['module_id' => $modules['module-1'], 'firearm_id' => $firearms['9mm'], 'sort_order' => 0],
            ['module_id' => $modules['module-1'], 'firearm_id' => $firearms['45'], 'sort_order' => 1],
            ['module_id' => $modules['module-1'], 'firearm_id' => $firearms['38'], 'sort_order' => 2],
            ['module_id' => $modules['module-1'], 'firearm_id' => $firearms['shotgun'], 'sort_order' => 3],

            // Module 2: only 9mm has assembly parts
            ['module_id' => $modules['module-2'], 'firearm_id' => $firearms['9mm'], 'sort_order' => 0],

            // Module 3: 9mm and .45 for maintenance profiles
            ['module_id' => $modules['module-3'], 'firearm_id' => $firearms['9mm'], 'sort_order' => 0],
            ['module_id' => $modules['module-3'], 'firearm_id' => $firearms['45'], 'sort_order' => 1],
        ];

        DB::table('module_firearm')->insert($pivot);
    }
}
