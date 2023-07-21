<?php

namespace App\Services;

use App\Contracts\AuthenticationServiceInterface;
use App\Exceptions\QueryException;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthenticationService implements AuthenticationServiceInterface
{
    /**
     * Method register
     *
     * @param $validation $validation
     */
    public function register($validation): User|Throwable
    {
        try {
            $user = User::create([
                'name' => $validation['name'],
                'email' => $validation['email'],
                'password' => Hash::make($validation['password']),
            ]);

        } catch (Exception $e) {
            throw new QueryException('Something went wrong');
        }

       return $user;
    }
}
