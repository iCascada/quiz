<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $guarded = [];

    public function questions()
    {
        return $this->hasMany(Question::class);
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
