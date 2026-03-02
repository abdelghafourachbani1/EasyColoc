<?php

namespace Database\Seeders;

use App\Models\Colocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultCategories = [
            'Rent',
            'Electricity',
            'Water',
            'Internet',
            'Food',
            'Other'
        ];

        $colocations = Colocation::all();

        foreach ($colocations as $colocation) {
            foreach ($defaultCategories as $name) {
                $colocation->categories()->create([
                    'name' => $name
                ]);
            }
        }
    }
}
