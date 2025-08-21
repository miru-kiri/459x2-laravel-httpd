<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class X459x_Area extends Model
{
    use HasFactory;
    // protected $table = 'x459x_conf.area';
    protected $table;
    protected $primaryKey = 'areaid';
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = env('DB_PREFIX_X459X') . 'conf' . '.area';
    }
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['areaid'];
}
