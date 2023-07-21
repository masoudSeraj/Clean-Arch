<?php

namespace App\Rules;

use App\Models\Comment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CommentRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $count = Comment::where('user_id', request()->user->id)->where('commentable_id', request()->product->id)->count();
        // dd( $count);
        if ($count >= 2) {
            $fail('You have already commented 2 times on this Product!');
        }
    }
}
