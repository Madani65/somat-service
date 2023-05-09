<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitOfMeasure extends Seeder
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
                "code" => "gr",
                "short_name" => "Gr",
                "long_name" => "Gram",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 2,
                "code" => "kg",
                "short_name" => "Kg",
                "long_name" => "Kilo Gram",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 3,
                "code" => "ml",
                "short_name" => "mL",
                "long_name" => "Mili Liter",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 4,
                "code" => "l",
                "short_name" => "L",
                "long_name" => "Liter",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "id" => 5,
                "code" => "pcs",
                "short_name" => "Pcs",
                "long_name" => "Pieces",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
        ];
        DB::table("unit_of_measures")->insert($data);
    }
}
