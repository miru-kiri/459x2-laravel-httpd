<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class X459x_Cast_Image extends Model
{
    use HasFactory;
    // protected $table = 'x459x_image.staf_img';
    protected $table;
    protected $primaryKey = 'stimgid';
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = env('DB_PREFIX_X459X') . 'image' . '.staf_img';
    }
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['stimgid'];
}
