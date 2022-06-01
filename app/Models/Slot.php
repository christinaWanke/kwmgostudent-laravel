<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Slot extends Model
{
    use HasFactory;
    protected $fillable = ['day', 'from', 'to', 'isBooked', 'course_id'];

    public function course() : BelongsTo{
        return $this->belongsTo(Course::class);
    }

    public function users() : BelongsToMany{
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /*public function slotBooked() : HasMany {
        return $this->hasMany(SlotUser::class);
    }*/
}
