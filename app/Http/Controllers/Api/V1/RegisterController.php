<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Exceptions\QueryException;
use App\Http\Controllers\Controller;
use Illuminate\Database\Events\QueryExecuted;
use Symfony\Component\HttpFoundation\Response;
use App\Contracts\AuthenticationServiceInterface;

class RegisterController extends Controller
{    
    /**
     * Method register
     *
     * @param Request $request Including ['name', 'email', 'password', 'confirm_password'] in request
     *
     * @return void
     */

    public function __construct(protected AuthenticationServiceInterface $authenticationServiceInterface)
    {
        
    }

    public function register(Request $request){
        $validated = $request->validate([
            'name' => 'required|min:4', 
            'email' => 'required|email', 
            'password' => 'required|confirmed', 
            'password_confirmation' => 'required'
        ]);
   
        try{
            $this->authenticationServiceInterface->register($validated);
        } catch(QueryException $e){
            return response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response('User added successfully', Response::HTTP_OK);
    }
}
