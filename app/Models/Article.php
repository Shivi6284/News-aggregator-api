<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title','content','author','source','category','url','published_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
