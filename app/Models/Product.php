<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function comments()  :MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getRouteKeyName(): string
    {
        return 'name';
    }
}
