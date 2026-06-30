<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ModuleFirearmSeeder extends Seeder
{
    public function run(): void
    {
        // module_firearm pivot table no longer exists
        // Firearm-to-module assignments are handled through assessment_simulations
    }
}
