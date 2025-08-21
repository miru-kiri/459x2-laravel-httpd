<?php

namespace Database\Seeders;

use App\Models\M_Cast_Option;
use App\Models\X459x_Option;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MCastOptionSeeder extends Seeder
{
    protected $newColumns = [
        'id' => 'opid',
        'created_at'  => 'op20',
        'updated_at'  => 'op19',
        'deleted_at'  => 'op18',
        'site_id' => 'op1',
        'class' => 'op2',
        'name' => 'op3',
        'img' => 'op4',
        'type' => 'op5',
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();
            $datas = X459x_Option::where('op18', 'none')->get();
            $parameter = [];
            foreach ($datas as $data) {
                $param = [];
                foreach ($this->newColumns as $newKey => $oldKey) {
                    if ($newKey == 'deleted_at') {
                        $data->$oldKey = $data->$oldKey == 'none' ? 0 : 1;
                    }
                    $param[$newKey] = $data->$oldKey;
                }
                $parameter[] = $param;
            }
            M_Cast_Option::insert($parameter);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
