<?php

namespace Tests\Unit;

use Tests\TestCase;


use App\Http\Requests\LoginUserRequest;
use App\Http\Controllers\AuthenticationController\AuthenticationController;
use App\Repositories\UserRepository\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Illuminate\Support\Str;

class IssueAuthTest extends TestCase
{
     use RefreshDatabase;

    protected $authController;
    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->authController = new AuthenticationController($this->userRepository);
    }
            /**
        * @runInSeparateProcess
        * @preserveGlobalState disabled
        */
    public function testIssueAuthTokenUserNotFound()
    {
        $request = new LoginUserRequest([
            'email' => 'nonexistent@example.com',
            'password' => 'password'
        ]);

        $this->userRepository->expects($this->once())
            ->method('getUserByEmail')
            ->willReturn(null);

        $response = $this->authController->issueAuthToken($request);

        $this->assertEquals(404, $response->status());
    }

    /**
        * @runInSeparateProcess
        * @preserveGlobalState disabled
    */
    public function testIssueAuthTokenInvalidPassword()
    {
        $user = new User();
        $user->password = Hash::make('correctpassword');

        $request = new LoginUserRequest([
            'email' => 'user@example.com',
            'password' => 'wrongpassword'
        ]);


        $this->userRepository->expects($this->once())
            ->method('getUserByEmail')
            ->with('user@example.com')
            ->willReturn($user);
        $response = $this->authController->issueAuthToken($request);

        $this->assertEquals(401, $response->status());
    }

    /**
        * @runInSeparateProcess
        * @preserveGlobalState disabled
    */
    public function testIssueAuthTokenSuccess()
        {
        // Mock request
        $request = new LoginUserRequest([
            'email' => 'user@example.com',
            'password' => 'correctpassword'
        ]);

        // Mock User model
        $userMock = Mockery::mock(User::class)->makePartial();
        $userMock->password = Hash::make('correctpassword');  // Hash password untuk pembandingan
        $userMock->shouldIgnoreMissing();

        $userMock->shouldReceive('createToken')
            ->once()
            ->andReturn((object) ['plainTextToken' => 'token123']);

        // Mock UserRepository
        $this->userRepository->expects($this->once())
            ->method('getUserByEmail')
            ->with('user@example.com')
            ->willReturn($userMock);  // Return mocked User

        // Mock BaseResponse facade
        $baseResponseMock = Mockery::mock('alias:App\Http\Controllers\BaseResponse');
        $baseResponseMock->shouldReceive('sendSuccessResponse')
            ->once()
            ->with([
                'access_token' => 'token123',
                'token_type' => 'Bearer'
            ], 'Token issued successfully', 200)
            ->andReturn((object) [
                'status' => 200,
                'message' => 'Token issued successfully',
                'data' => ['access_token' => 'token123', 'token_type' => 'Bearer']
            ]);

        $response = $this->authController->issueAuthToken($request);

        $this->assertEquals(200, $response->status);
        $this->assertEquals('Token issued successfully', $response->message);
    }


}