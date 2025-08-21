<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class X459x_Genre extends Model
{
    use HasFactory;
    // protected $table = 'x459x_conf.genre';
    protected $table;
    protected $primaryKey = 'genreid';
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = env('DB_PREFIX_X459X') . 'conf' . '.genre';
    }
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['genreid'];
}
