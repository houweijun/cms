<?php

namespace App\Models\Common;


class ComOption extends ComModel
{
    protected $table      = 'com_options';
    protected $dateFormat = 'U';
    protected $fillable   = ['name', 'options','description','created_at','update_at'];
    protected $guarded    = ['updated_at','created_at'];

    protected $casts = [
        'options'=>'array'
    ];
}