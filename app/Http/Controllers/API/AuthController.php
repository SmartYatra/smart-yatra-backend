<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use DB;

class AuthController extends BaseController
{
    /**
     * Register API
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'c_password' => 'required|same:password',
                'user_type' => 'required|in:user,admin,driver',
            ]);

            if ($validator->fails()) {
                return $this->sendError(
                    'Validation Error: ',
                    [$validator->errors()],
                    400
                );
            }
            $input = $request->all();

            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => bcrypt($input['password']),
                'type' => $input['user_type']
            ]);


            $success = [
                'token' => $user->createToken('MyApp')->accessToken,
                'name' => $user->name,
                'user_type' => $user->type,
            ];
            DB::commit();
            return $this->sendResponse($success, 'User registered successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError(
                'An error occurred while processing your request.',
                [$e->getMessage()],
                500
            );
        }
    }



    /**
     * Login API
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError(
                    'Validation Error: ',
                    [$validator->errors()],
                    400
                );
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $success = [
                    'token' => $user->createToken('MyApp')->accessToken,
                    'name' => $user->name,
                    'type' => $user->type,
                ];

                return $this->sendResponse($success, 'User logged in successfully.');
            } else {
                return $this->sendError(
                    'Unauthorized.',
                    ['error' => 'Invalid email or password.'],
                    401
                );
            }
        } catch (\Exception $e) {
            return $this->sendError(
                'An error occurred while processing your request.',
                [$e->getMessage()],
                500
            );
        }
    }
}
