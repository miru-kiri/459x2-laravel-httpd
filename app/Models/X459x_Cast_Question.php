<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class X459x_Cast_Question extends Model
{
    use HasFactory;
    // protected $table = 'x459x_staff.profile_m';
    protected $table;
    protected $primaryKey = 'profid';
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = env('DB_PREFIX_X459X') . 'staff' . '.profile_m';
    }
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['profid'];
}
