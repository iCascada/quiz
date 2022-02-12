<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public const USER = 1;
    public const ADMIN = 2;
    public const MODERATOR = 3;
}
