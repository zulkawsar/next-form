<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Field::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Field::insert([
            ['field_type' => "input"],
            ['field_type' => "textarea"],
            ['field_type' => "select"],
        ]);
    }
}
