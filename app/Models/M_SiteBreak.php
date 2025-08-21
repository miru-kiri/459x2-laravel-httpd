<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_SiteBreak extends Model
{
    use HasFactory;

    protected $table = "m_site_breaks";

    protected $fillable = [
        'site_id',
        'weekday',
        'break_start',
        'break_end',
    ];
}
