<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Course extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'semester', 'professor', 'comment', 'user_id'];

    public function images() : HasMany{
        return $this->hasMany(Image::class);
    }

    public function user() : BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function slot() : HasMany{
        return $this->hasMany(Slot::class);
    }
}