<?php

namespace Database\Seeders;

use App\Models\M_Cast;
use App\Models\X459x_Cast;
use Illuminate\Database\Seeder;

class MCastOptionUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oldCasts = X459x_Cast::get();
        foreach($oldCasts as $oldCast) {
            if(!empty($oldCast->st24)) {
                M_Cast::where('id',$oldCast->staffid)->update(['option'=>$oldCast->st24]);
            }
        }
    }
}
