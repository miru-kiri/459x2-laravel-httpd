<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class X459x_Option extends Model
{
    use HasFactory;
    // protected $table = 'x459x_staff.option_m';
    protected $table;
    protected $primaryKey = 'opid';
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = env('DB_PREFIX_X459X') . 'staff' . '.option_m';
    }
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['opid'];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        'opid',
        'op1',
        'op2',
        'op3',
        'op4',
        'op5',
    ];
    /**
     * サイトに紐づくデータを取得
     *
     * @param object $query
     * @param array $filter
     * @return void
     */
    public function scopeFetchFilterData(object $query, array $filter)
    {
        return $query
            ->where('op18', 'none')
            ->when(!empty($filter['site_id']), function ($query) use ($filter) {
                if (is_array($filter['site_id'])) {
                    return $query->whereIn("op1", $filter['site_id']);
                }
                return $query->where("op1", $filter['site_id']);
            })
            ->select($this->defaultFetchColumns)
            ->get();
    }
    /**
     * データ更新
     *
     * @param object $query
     * @param array $parameter
     * @return bool
     */
    public function scopeUpsertData(object $query, array $parameter)
    {
        if (isset($parameter['id'])) {
            return $query
                ->where('opid', $parameter['id'])
                ->update([
                    "op1" => $parameter['site_id'],
                    "op3" => $parameter['name'],
                    "op18" => 'none',
                    "op19" => time()
                ]);
        } else {
            return $query
                ->insert([
                    "op1" => $parameter['site_id'],
                    "op3" => $parameter['name'],
                    "op18" => 'none',
                    // "op19" => time(),
                    "op20" => time()
                ]);
        }
    }
}
