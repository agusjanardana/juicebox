<?php

namespace App\Http\Controllers\AuthenticationController;

use App\Http\Controllers\Controller;
use Illuminate\Broadcasting\Broadcasters\NullBroadcaster;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendEmailJob;

use App\Repositories\UserRepository\UserRepositoryInterface;

class AuthenticationController extends Controller
{

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function issueAuthToken(LoginUserRequest $request)
    {

        try {
            $user = $this->userRepository->getUserByEmail($request->email);
            if (!$user) {
                return BaseResponse::sendError(null, "'User not found'", 404);
            }

            if (!password_verify($request->password, $user->password)) {
                return BaseResponse::sendError(null, 'Invalid password', 401);
            }

            $token = $user->createToken('authToken')->plainTextToken;
            return BaseResponse::sendSuccessResponse(['access_token' => $token, "token_type" => "Bearer"], 'Token issued successfully', 200);
        } catch (\Exception $e) {
            return BaseResponse::throw($e);
        }
    }

    public function issueLogoutToken(Request $request)
    {
        try {
            // breare token
            $token = $request->bearerToken();

            // Memastikan token ada dan terautentikasi
            if (!$token || !Auth::guard('sanctum')->check()) {
                return BaseResponse::sendError('Unauthorized', null, 401);
            }

            Auth::user()->tokens()->delete();

            return BaseResponse::sendSuccessResponse(null, 'Token revoked successfully', 200);
        } catch (\Exception $e) {
            return BaseResponse::throw($e);
        }
    }

      /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            if (!$id) {
                return BaseResponse::sendError('User id is required', null, 400);
            }

            $user = $this->userRepository->getById($id);
            if (!$user) {
                return BaseResponse::sendError('User not found', null, 404);
            }
            return BaseResponse::sendSuccessResponse(new UserResource($user), 'success', 200);
        } catch (\Exception $e) {
            return BaseResponse::throw($e);
        }
    }

    public function issueRegister(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $dataReq = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now()
            ];
            $user = $this->userRepository->store($dataReq);


            // Send email
            $dataEmail = [
                "to" => $request->email];
            SendEmailJob::dispatch($dataEmail)->delay(now()->addSeconds(5000));

            return BaseResponse::sendSuccessResponse(new UserResource($user), 'User created successfully', 200);
        } catch (\Exception $e) {
            return BaseResponse::rollback($e);
        }
    }


}