<?php namespace App\Contracts;

interface AuthenticationServiceInterface
{    
    /**
     * Method register
     *
     * @param $validation array<string, string>
     *
     * @return void
     */
    public function register(array $validation);
}