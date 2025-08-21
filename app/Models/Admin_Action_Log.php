<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin_Action_Log extends Model
{
    use HasFactory;
    protected $table = 'admin_action_log';

    public $timestamps = false;
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['id'];
}
