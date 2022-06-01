<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model{
    use HasFactory;
    protected $fillable = ['text', 'course_id', 'user_id'];

    public function course(): BelongsTo{
        return $this->belongsTo(Course::class);

    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

}
