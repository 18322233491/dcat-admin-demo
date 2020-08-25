<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Tourist extends Model
{
    use HasDateTimeFormatter;

    protected $fillable = ['name', 'sex', 'img'];
}
