<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends BaseModel
{
    protected $guarded = [];

    public function answers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}
