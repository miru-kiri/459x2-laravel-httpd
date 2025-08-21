<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'type', 'user_id'];

    public const TEMPLATE_TYPES = [
        1 => 'キャストブログ',
        2 => '店長ブログ',
        3 => 'ショップニュース',
    ];
}
