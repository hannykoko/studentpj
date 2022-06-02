<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skills')->insert([
            [
                'name' => 'C++',
                'created_emp' => 111,
                'updated_emp' => 111,
            ],
            [
                'name' => 'Java',
                'created_emp' => 111,
                'updated_emp' => 111,
            ],
            [
                'name' => 'PHP',
                'created_emp' => 111,
                'updated_emp' => 111,
            ],
            [
                'name' => 'React',
                'created_emp' => 111,
                'updated_emp' => 111,
            ],
            [
                'name' => 'Android',
                'created_emp' => 111,
                'updated_emp' => 111,
            ],
            
            [
                'name' => 'Laravel',
                'created_emp' => 111,
                'updated_emp' => 111,
            ]
        ]);
    }
}
