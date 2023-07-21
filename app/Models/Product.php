<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = ['name'];

    public function users() :BelongsToMany
    {
        return $this->belongsToMany(User::class, 'product_user', 'product_id', 'user_id');
    }
    public function comments()  :MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getRouteKeyName(): string
    {
        return 'name';
    }
}
