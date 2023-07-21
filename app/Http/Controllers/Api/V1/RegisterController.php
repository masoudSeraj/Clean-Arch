<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\AuthenticationServiceInterface;
use App\Exceptions\QueryException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /**
     * Method register
     *
     * @param  Request  $request Including ['name', 'email', 'password', 'confirm_password'] in request
     * @return void
     */
    public function __construct(protected AuthenticationServiceInterface $authenticationServiceInterface)
    {

    }

    public function register(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        try {
            $this->authenticationServiceInterface->register($validated);
        } catch (QueryException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['success' => 'User added successfully'], Response::HTTP_OK);
    }
}
