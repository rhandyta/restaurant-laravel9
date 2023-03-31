<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterStoreRequest;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterStoreRequest $request)
    {
        try {
            $firstname = trim($request->input('firstname'));
            $lastname = trim($request->input('lastname'));
            $middlename = $request->input('middlename') == null ? null : trim($request->input('middlename'));
            $email = trim($request->input('email'));
            $telephone = trim($request->input('telephone'));
            $address = trim($request->input('address'));
            $password = $request->input('password');

            User::create([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'middlename' => $middlename,
                'email' => $email,
                'telephone' => $telephone,
                'address' => $address,
                'password' => Hash::make($password),
                'roles' => 'customer',
            ]);

            return response()->json([
                'status_code' => 201,
                'messages' => 'user has been created'
            ], 201);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status_code' => 409,
                    'messages' => 'User already exists'
                ], 409);
            } else {
                return response()->json([
                    'status_code' => $e->getCode(),
                    'messages' => $e->getMessage(),
                ], $e->getCode());
            }
        } catch (Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
