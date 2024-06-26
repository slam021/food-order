<?php

namespace Database\Seeders;

use DB;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        // RoleSeeder::truncate();
        DB::table('roles')->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            'waitress', 'chef', 'chasier', 'manager'
        ];

        foreach($data as $val){
            Role::insert([
                'name' => $val,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
