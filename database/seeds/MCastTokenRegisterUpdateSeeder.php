<?php

namespace Database\Seeders;

use App\Models\M_Cast;
use Illuminate\Database\Seeder;

class MCastTokenRegisterUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $castDatas = M_Cast::whereNull(['username','password_reset_token','token_register'])->get();
        $time = now();
        foreach($castDatas as $castData) {
            while (true) {
                $token = \Str::random(20);
                if (!M_Cast::checkToken($token)) {
                    break;
                }
            }
            M_Cast::where('id',$castData->id)->update(['generate_link_register_at' => $time,'token_register'=>$token]);
        }
    }
}
