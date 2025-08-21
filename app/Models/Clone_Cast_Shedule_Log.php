<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clone_Cast_Shedule_Log extends Model
{
    use HasFactory;
    protected $table = 'clone_cast_shedule_log';
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['id'];
}
