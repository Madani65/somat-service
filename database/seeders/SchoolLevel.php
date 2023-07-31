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
                "name" => "SD",
                "description" => "Kelas SD",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 2,
                "name" => "SMP",
                "description" => "Kelas SMP",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 3,
                "name" => "SMA",
                "description" => "Kelas SMA",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 4,
                "name" => "MI",
                "description" => "Kelas MI",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 5,
                "name" => "MTS",
                "description" => "Kelas MTS",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 6,
                "name" => "MA",
                "description" => "Kelas MA",
                "active_flag" => "Y",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]
        ];
        DB::table("school_levels")->insert($data);
    }
}
