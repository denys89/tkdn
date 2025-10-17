<?php

namespace App\Models;

use App\Traits\UsesUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, UsesUlid;

    protected $fillable = [
        'name',
        'code',
    ];

    protected $keyType = 'string';

    public $incrementing = false;
}
