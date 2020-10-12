<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComModel extends Model
{
    use SoftDeletes;
    public $timestamps    = true;
    protected $dateFormat = 'U';
    protected $dates      = ['deleted_at'];

}