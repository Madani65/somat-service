<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolLevel extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "id" => 1,
                "name" => "I",
                "description" => "Kelas I",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 2,
                "name" => "II",
                "description" => "Kelas II",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 3,
                "name" => "III",
                "description" => "Kelas III",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 4,
                "name" => "IV",
                "description" => "Kelas IV",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 5,
                "name" => "V",
                "description" => "Kelas V",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 6,
                "name" => "VI",
                "description" => "Kelas VI",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 7,
                "name" => "VII",
                "description" => "Kelas VII",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 8,
                "name" => "VIII",
                "description" => "Kelas VIII",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 9,
                "name" => "IX",
                "description" => "Kelas IX",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 10,
                "name" => "X",
                "description" => "Kelas X",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 11,
                "name" => "XI",
                "description" => "Kelas XI",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 12,
                "name" => "XII",
                "description" => "Kelas XII",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]
        ];
        DB::table("school_levels")->insert($data);
    }
}
