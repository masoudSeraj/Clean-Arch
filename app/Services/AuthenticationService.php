<?php namespace App\Services;

use Exception;
use Throwable;
use App\Models\User;
use App\Exceptions\QueryException;
use Illuminate\Support\Facades\Hash;
use App\Contracts\AuthenticationServiceInterface;

class AuthenticationService implements AuthenticationServiceInterface
{    
    /**
     * Method register
     *
     * @param $validation $validation 
     *
     * @return User | Throwable
     */
    public function register($validation) :User | Throwable
    {
        try{
            $user = User::create([
                'name' => $validation['name'],
                'email' => $validation['email'],
                'password' => Hash::make($validation['password'])
            ]);

        } catch(Exception $e) {
            throw new QueryException('Something went wrong');
        }
        
       return $user;
    }
}
