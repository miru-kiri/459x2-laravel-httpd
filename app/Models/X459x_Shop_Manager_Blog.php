<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class X459x_Shop_Manager_Blog extends Model
{
    use HasFactory;
    // protected $table = 'x459x_blog.site';
    protected $table;
    protected $primaryKey = 'sibid';
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = env('DB_PREFIX_X459X') . 'blog' . '.site';
    }
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['sibid'];
}
