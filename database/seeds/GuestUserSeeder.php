<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\D_User;

class GuestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        D_User::firstOrCreate(
            ['email' => 'guest@example.com'],
            [
                'name' => '名無し',
                'name_furigana' => '名無し',
                'name_show' => '名無し',
                'password' => bcrypt('guest123'),
            ]
        );
    }
}
